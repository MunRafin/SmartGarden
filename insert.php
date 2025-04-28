<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "smartgarden";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$temp = $_POST['temperature'];
$hum = $_POST['humidity'];
$soil = $_POST['soil'];
$air = $_POST['airquality'];
$plantheight = $_POST['plantheight'];

$sql = "INSERT INTO SensorData (temperature, humidity, soil, airquality, plantheight)
VALUES ('$temp', '$hum', '$soil', '$air', '$plantheight')";

if ($conn->query($sql) === TRUE) {
  echo "New record created successfully";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
