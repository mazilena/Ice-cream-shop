<?php
session_start();
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'tms');

try {
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS, [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"
    ]);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection error: " . $e->getMessage());
}

// -------------------- [ Default Date Range - Current Month ] --------------------
$from_date = date('Y-m-01'); // 1st day of current month
$to_date = date('Y-m-t');   // Last day of current month

if (isset($_POST['from_date']) && isset($_POST['to_date'])) {
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
}

// -------------------- [ Package-Wise Booking Report ] --------------------
$stmt = $conn->prepare("
    SELECT p.PackageName, COUNT(b.BookingId) AS total_bookings
    FROM tblbooking b
    JOIN tbltourpackages p ON b.PackageId = p.PackageId
    WHERE DATE(b.RegDate) BETWEEN ? AND ?
    GROUP BY p.PackageName
");
$stmt->execute([$from_date, $to_date]);
$packageBookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

// -------------------- [ Monthly Booking Report with Most Booked Package ] --------------------
$stmt = $conn->prepare("
    SELECT 
        DATE_FORMAT(b.RegDate, '%Y-%m') AS month, 
        COUNT(b.BookingId) AS total_bookings,
        (SELECT p.PackageName 
         FROM tblbooking b2
         JOIN tbltourpackages p ON b2.PackageId = p.PackageId
         WHERE DATE_FORMAT(b2.RegDate, '%Y-%m') = DATE_FORMAT(b.RegDate, '%Y-%m')
         GROUP BY p.PackageName
         ORDER BY COUNT(b2.BookingId) DESC
         LIMIT 1) AS top_package
    FROM tblbooking b
    WHERE DATE(b.RegDate) BETWEEN ? AND ?
    GROUP BY month
    ORDER BY month DESC
");
$stmt->execute([$from_date, $to_date]);
$monthlyBookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

// -------------------- [ Date Range Booking Report ] --------------------
// -------------------- [ Date Range Booking Report ] --------------------
$stmt = $conn->prepare("
    SELECT DATE(b.RegDate) AS booking_date, p.PackageName, COUNT(b.BookingId) AS total_bookings
    FROM tblbooking b
    JOIN tbltourpackages p ON b.PackageId = p.PackageId
    WHERE DATE(b.RegDate) BETWEEN ? AND ?
    GROUP BY booking_date, p.PackageName
    ORDER BY booking_date, total_bookings DESC
");
$stmt->execute([$from_date, $to_date]);
$dateRangeBookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Reports</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawCharts);

        function drawCharts() {
            drawPackageBookingsChart();
            drawMonthlyBookingsChart();
        }

        function drawPackageBookingsChart() {
            var data = google.visualization.arrayToDataTable([
                ['Package Name', 'Total Bookings'],
                <?php foreach ($packageBookings as $row) {
                    echo "['" . $row['PackageName'] . "', " . $row['total_bookings'] . "],";
                } ?>
            ]);

            var options = { title: 'Package-Wise Bookings Report' };
            var chart = new google.visualization.PieChart(document.getElementById('packageBookingsChart'));
            chart.draw(data, options);
        }

        function drawMonthlyBookingsChart() {
            var data = google.visualization.arrayToDataTable([
                ['Month', 'Total Bookings'],
                <?php foreach ($monthlyBookings as $row) {
                    echo "['" . $row['month'] . "', " . $row['total_bookings'] . "],";
                } ?>
            ]);

            var options = { title: 'Monthly Bookings Report' };
            var chart = new google.visualization.ColumnChart(document.getElementById('monthlyBookingsChart'));
            chart.draw(data, options);
        }
    </script>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        .container { width: 80%; margin: auto; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        table, th, td { border: 1px solid #ddd; padding: 10px; }
        th { background-color: #f4f4f4; }
    </style>
</head>
<body>
<button onclick="goBack()" id="backButton"> Go Back</button>

<script>
    function goBack() {
        window.history.back();
    }
</script>

<style>
    #backButton {
        position: absolute;
        top: 10px;
        left: 10px;
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
        background-color: #007BFF;
        color: white;
        border: none;
        border-radius: 5px;
    }

    #backButton:hover {
        background-color: #0056b3;
    }
</style>


<script>
    function goBack() {
        window.history.back();
    }
</script>


    <div class="container">
        <h2>Booking Reports</h2>

        <!-- Date Range Booking Report -->
        <h3>Filter Reports by Date Range</h3>
        <form method="POST">
            <label>From: <input type="date" name="from_date" value="<?= $from_date ?>" required></label>
            <label>To: <input type="date" name="to_date" value="<?= $to_date ?>" required></label>
            <button type="submit">Filter</button>
        </form>

        <!-- Package-Wise Booking Report -->
        <h3>Package-Wise Booking Report</h3>
        <div id="packageBookingsChart" style="width: 100%; height: 400px;"></div>
        <table>
            <tr><th>Package Name</th><th>Total Bookings</th></tr>
            <?php foreach ($packageBookings as $row) { ?>
                <tr><td><?= $row['PackageName']; ?></td><td><?= $row['total_bookings']; ?></td></tr>
            <?php } ?>
        </table>

        <!-- Monthly Booking Report -->
        <h3>Monthly Booking Report</h3>
        <div id="monthlyBookingsChart" style="width: 100%; height: 400px;"></div>
        <table>
            <tr><th>Month</th><th>Total Bookings</th><th>Most Booked Package</th></tr>
            <?php foreach ($monthlyBookings as $row) { ?>
                <tr>
                    <td><?= $row['month']; ?></td>
                    <td><?= $row['total_bookings']; ?></td>
                    <td><?= $row['top_package'] ? $row['top_package'] : 'N/A'; ?></td>
                </tr>
            <?php } ?>
        </table>

        <!-- Date Range Booking Report -->
        <!-- Date Range Booking Report -->
        <h3>Date Range Booking Report</h3>
<div id="dateRangeBookingsChart" style="width: 100%; height: 400px;"></div>

<table>
    <tr>
        <th>Date</th>
        <th>Package Name</th>
        <th>Total Bookings</th>
    </tr>
    <?php foreach ($dateRangeBookings as $row) { ?>
        <tr>
            <td><?= $row['booking_date']; ?></td>
            <td><?= $row['PackageName']; ?></td>
            <td><?= $row['total_bookings']; ?></td>
        </tr>
    <?php } ?>
</table>


    </div>
    <script>
    google.charts.setOnLoadCallback(drawDateRangeBookingsChart);

    function drawDateRangeBookingsChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('date', 'Date');

        // Dynamically get unique package names for column headers
        var packageNames = [];
        <?php foreach ($dateRangeBookings as $row) { ?>
            if (!packageNames.includes("<?= $row['PackageName'] ?>")) {
                packageNames.push("<?= $row['PackageName'] ?>");
                data.addColumn('number', "<?= $row['PackageName'] ?>");
            }
        <?php } ?>

        // Prepare data by grouping by date
        var bookingsData = {};
        <?php foreach ($dateRangeBookings as $row) { ?>
            var dateStr = "<?= $row['booking_date'] ?>";
            var packageName = "<?= $row['PackageName'] ?>";
            var count = <?= $row['total_bookings'] ?>;
            
            var dateObj = new Date(dateStr);
            var dateKey = dateObj.toISOString().split('T')[0]; // Convert date to YYYY-MM-DD format

            if (!bookingsData[dateKey]) {
                bookingsData[dateKey] = { date: dateObj };
                packageNames.forEach(pkg => bookingsData[dateKey][pkg] = 0);
            }
            bookingsData[dateKey][packageName] = count;
        <?php } ?>

        // Convert bookingsData to an array for Google Charts
        var finalData = Object.values(bookingsData).map(row => {
            return [row.date, ...packageNames.map(pkg => row[pkg] || 0)];
        });

        // Add rows to the chart data
        data.addRows(finalData);

        var options = {
            title: 'Date Range Booking Report (Package Wise)',
            hAxis: { title: 'Date', format: 'yyyy-MM-dd' },
            vAxis: { title: 'Total Bookings' },
            isStacked: true,
            legend: { position: 'top' }
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('dateRangeBookingsChart'));
        chart.draw(data, options);
    }
</script>


</body>
</html>
