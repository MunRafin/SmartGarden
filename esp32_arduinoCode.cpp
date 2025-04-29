#include <WiFi.h>
#include <HTTPClient.h>
#include "DHT.h"

// WiFi Credentials
const char* ssid = "ABC";
const char* password = "1234567890";

// Server URL
const char* serverName = "http://localhost/SmartGarden/.php"; 

// Sensor Pins
#define DHTPIN 4
#define DHTTYPE DHT11
#define SOIL_MOISTURE_PIN 34
#define MQ04_PIN 35  // Changed from MQ135_PIN to MQ04_PIN
#define TRIG_PIN 5
#define ECHO_PIN 18
#define FAN_RELAY_PIN 25
#define PUMP_RELAY_PIN 26

#define SENSOR_HEIGHT_CM 50.0 // Adjust to your setup

// Thresholds
#define TEMP_THRESHOLD 30   // Temperature to turn ON fan
#define SOIL_THRESHOLD 500  // Soil moisture value to turn ON pump
#define PUMP_RUN_TIME 5000  // milliseconds

DHT dht(DHTPIN, DHTTYPE);

void setup() {
  Serial.begin(115200);
  
  pinMode(SOIL_MOISTURE_PIN, INPUT);
  pinMode(MQ04_PIN, INPUT);  // Changed pin name here
  pinMode(TRIG_PIN, OUTPUT);
  pinMode(ECHO_PIN, INPUT);
  pinMode(FAN_RELAY_PIN, OUTPUT);
  pinMode(PUMP_RELAY_PIN, OUTPUT);

  dht.begin();

  WiFi.begin(ssid, password);
  Serial.println("Connecting to WiFi...");
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.print(".");
  }
  Serial.println("\nConnected!");
}

void loop() {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;

    float temp = dht.readTemperature();
    float hum = dht.readHumidity();
    int soilMoisture = analogRead(SOIL_MOISTURE_PIN);
    int mq04Value = analogRead(MQ04_PIN);  // Changed variable name

    // Plant Height Measurement
    long duration;
    float distance;
    float plantHeight;

    digitalWrite(TRIG_PIN, LOW);
    delayMicroseconds(2);
    digitalWrite(TRIG_PIN, HIGH);
    delayMicroseconds(10);
    digitalWrite(TRIG_PIN, LOW);

    duration = pulseIn(ECHO_PIN, HIGH);
    distance = duration * 0.034 / 2; // cm

    plantHeight = SENSOR_HEIGHT_CM - distance;
    if (plantHeight < 0) {
      plantHeight = 0;
    }

    // Control Fan
    if (temp >= TEMP_THRESHOLD) {
      digitalWrite(FAN_RELAY_PIN, LOW); // Assuming LOW = ON for relay
    } else {
      digitalWrite(FAN_RELAY_PIN, HIGH); // OFF
    }

    // Control Pump
    if (soilMoisture >= SOIL_THRESHOLD) {
      digitalWrite(PUMP_RELAY_PIN, LOW); // ON
      delay(PUMP_RUN_TIME);
      digitalWrite(PUMP_RELAY_PIN, HIGH); // OFF
    }

    // Send data
    String httpRequestData = "temperature=" + String(temp) +
                             "&humidity=" + String(hum) +
                             "&soil=" + String(soilMoisture) +
                             "&airquality=" + String(mq04Value) +  // Updated field
                             "&plantheight=" + String(plantHeight);

    http.begin(serverName);
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");

    int httpResponseCode = http.POST(httpRequestData);
    Serial.print("HTTP Response Code: ");
    Serial.println(httpResponseCode);

    http.end();
  } else {
    Serial.println("WiFi Disconnected!");
  }

  delay(10000); // Send data every 10 seconds (adjust if needed)
}
