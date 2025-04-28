<?php
// Turn on error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect to MySQL
$conn = new mysqli('localhost', 'root', '', 'smartgarden');

// Check connection
if ($conn->connect_error) {
    die('Connection Failed : ' . $conn->connect_error);
}

// Fetch sensor data
$sql = "SELECT * FROM sensor_data ORDER BY timestamp DESC";
$result = $conn->query($sql);

$data = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Prepare PHP arrays for JavaScript
$labels = [];
$temp_values = [];
$humidity_values = [];
$soil_values = [];
$airquality_values = [];
$plantheight_values = [];

foreach($data as $row) {
    $labels[] = $row['timestamp'];
    $temp_values[] = $row['temperature'];
    $humidity_values[] = $row['humidity'];
    $soil_values[] = $row['soil'];
    $airquality_values[] = $row['airquality'];
    $plantheight_values[] = $row['plantheight'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Smart Garden Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            scroll-behavior: smooth;
        }
        .sidebar {
            width: 250px;
            background-color: #2c5f2d;
            padding: 20px;
            height: 100%;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            z-index: 1000; /* Added z-index */
        }

        .welcome-message {
            text-align: center;
            margin-bottom: 30px;
            width: 100%;
        }

        .welcome-message h4 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .welcome-message h5 {
            font-size: 18px;
            font-weight: lighter;
        }

        .sidebar a {
            text-decoration: none;
            color: white;
            font-size: 18px;
            margin-bottom: 10px;
            padding: 10px;
            width: 100%;
            display: block;
            text-align: center;
            background-color: #3a7c44;
            border-radius: 5px;
            margin-right: 20px;
            transition: background-color 0.3s ease;
        }

        .sidebar a:hover {
            background-color: #97bc62;
        }

        .content {
            margin-left: 250px; /* Increased from 220px */
            padding: 30px;
            position: relative;
            z-index: 1;
        }

        header {
            position: fixed;
            width: calc(100% - 250px); /* Account for sidebar width */
            left: 250px; /* Match sidebar width */
            top: 0;
            background: rgb(132, 232, 133);
            color: white;
            height: 50px;
            line-height: 50px;
            padding: 0 30px;
            font-weight: bold;
            z-index: 999; /* Ensure it's below sidebar */
        }

        footer {
            background: rgb(3, 41, 4);
            color: white;
            text-align: center;
            padding: 10px;
            margin-top: 30px;
            margin-left: 250px; /* Match sidebar width */
        }

        /* Added spacing for sections */
        section {
            margin-top: 30px;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
<div class="sidebar">
    <div class="welcome-message">
        <h4>IUB Smart Garden</h4>
        <h5>Welcome, Mr Fuad Chasha</h5>
    </div>
    <a href="#overview">Dashboard</a>
    <a href="#temperature">Temperature</a>
    <a href="#humidity">Humidity</a>
    <a href="#soil">Soil Moisture</a>
    <a href="#airquality">Air Quality</a>
    <a href="#plantheight">Plant Height</a>
</div>

<div class="content">

    <!-- Overview Section -->
    <section id="overview">
        <h2>Dashboard Overview</h2>
        <div class="row">
            <div class="col-md-4"><div class="card p-3 mb-3 shadow-sm">Latest Temperature: <?php echo $temp_values[0] ?? '-'; ?> °C</div></div>
            <div class="col-md-4"><div class="card p-3 mb-3 shadow-sm">Latest Humidity: <?php echo $humidity_values[0] ?? '-'; ?> %</div></div>
            <div class="col-md-4"><div class="card p-3 mb-3 shadow-sm">Latest Soil Moisture: <?php echo $soil_values[0] ?? '-'; ?> %</div></div>
            <div class="col-md-4"><div class="card p-3 mb-3 shadow-sm">Latest Air Quality: <?php echo $airquality_values[0] ?? '-'; ?></div></div>
            <div class="col-md-4"><div class="card p-3 mb-3 shadow-sm">Latest Plant Height: <?php echo $plantheight_values[0] ?? '-'; ?> cm</div></div>
        </div>
    </section>

    <hr>

    <!-- Temperature Section -->
    <section id="temperature">
        <h2>Temperature Data</h2>
        <canvas id="tempChart"></canvas>
        <table class="table table-striped mt-3">
            <thead><tr><th>Timestamp</th><th>Temperature (°C)</th></tr></thead>
            <tbody>
                <?php foreach($data as $row): ?>
                    <tr><td><?php echo $row['timestamp']; ?></td><td><?php echo $row['temperature']; ?></td></tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <hr>

    <!-- Humidity Section -->
    <section id="humidity">
        <h2>Humidity Data</h2>
        <canvas id="humidityChart"></canvas>
        <table class="table table-striped mt-3">
            <thead><tr><th>Timestamp</th><th>Humidity (%)</th></tr></thead>
            <tbody>
                <?php foreach($data as $row): ?>
                    <tr><td><?php echo $row['timestamp']; ?></td><td><?php echo $row['humidity']; ?></td></tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <hr>

    <!-- Soil Moisture Section -->
    <section id="soil">
        <h2>Soil Moisture Data</h2>
        <canvas id="soilChart"></canvas>
        <table class="table table-striped mt-3">
            <thead><tr><th>Timestamp</th><th>Soil Moisture (%)</th></tr></thead>
            <tbody>
                <?php foreach($data as $row): ?>
                    <tr><td><?php echo $row['timestamp']; ?></td><td><?php echo $row['soil']; ?></td></tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <hr>

    <!-- Air Quality Section -->
    <section id="airquality">
        <h2>Air Quality Data</h2>
        <canvas id="airChart"></canvas>
        <table class="table table-striped mt-3">
            <thead><tr><th>Timestamp</th><th>Air Quality</th></tr></thead>
            <tbody>
                <?php foreach($data as $row): ?>
                    <tr><td><?php echo $row['timestamp']; ?></td><td><?php echo $row['airquality']; ?></td></tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <hr>

    <!-- Plant Height Section -->
    <section id="plantheight">
        <h2>Plant Height Data</h2>
        <canvas id="plantChart"></canvas>
        <table class="table table-striped mt-3">
            <thead><tr><th>Timestamp</th><th>Plant Height (cm)</th></tr></thead>
            <tbody>
                <?php foreach($data as $row): ?>
                    <tr><td><?php echo $row['timestamp']; ?></td><td><?php echo $row['plantheight']; ?></td></tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

</div>

<footer>
    Smart Garden Project © 2025
</footer>

<script>
// Data from PHP
const labels = <?php echo json_encode($labels); ?>;
const tempData = <?php echo json_encode($temp_values); ?>;
const humidityData = <?php echo json_encode($humidity_values); ?>;
const soilData = <?php echo json_encode($soil_values); ?>;
const airData = <?php echo json_encode($airquality_values); ?>;
const plantData = <?php echo json_encode($plantheight_values); ?>;

// Chart functions
const createChart = (id, label, data, color) => {
    new Chart(document.getElementById(id), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: label,
                backgroundColor: color,
                borderColor: color,
                data: data,
                fill: false,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: { display: true },
                y: { display: true }
            }
        }
    });
};

createChart('tempChart', 'Temperature (°C)', tempData, 'red');
createChart('humidityChart', 'Humidity (%)', humidityData, 'blue');
createChart('soilChart', 'Soil Moisture (%)', soilData, 'green');
createChart('airChart', 'Air Quality', airData, 'purple');
createChart('plantChart', 'Plant Height (cm)', plantData, 'orange');
</script>

</body>
</html>
