<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
    <link rel="stylesheet" type="text/css" href="../css/admin_style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Times New Roman', Times, serif;
            text-decoration: none;
        }
        .main-container a {
            text-decoration: none !important;
        }
        .table th, .table td {
            text-align: center;
            vertical-align: middle;
            padding: 12px;
            font-size: 16px;
        }
        .table th {
            color: black;
            font-size: 25px;
        }
        .table td {
            font-size: 18px;
        }
        .table-striped tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }
        .table-hover tbody tr:hover {
            background-color: #e91e63;
        }
        .table {
            border-radius: 35px;
            overflow: hidden;
            border: 1px solid #ddd;
        }
        .filters { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
        .filters select, .filters input { padding: 8px; width: 180px; }
        .filters button { background-color: blueviolet; color: white; border: none; padding: 8px 15px; cursor: pointer; border-radius: 5px; }
        .filters button:hover { background-color: rgb(228, 131, 179); }
    </style>
</head>
<body>
    <div class="main-container">
        <?php include '../components/admin_header.php'; ?>
        <section class="user-container">
            <div class="heading">
                <h1>Sales Report</h1>
                <img src="../image/separator-img.png">
            </div>
            <div class="container mt-5">
                <form id="filter-form" class="filters mb-3" method="GET">
                    <label for="">From:</label>
                    <input type="date" name="from_date" id="from_date" class="form-control">

                    <label for="">To:</label>
                    <input type="date" name="to_date" id="to_date" class="form-control">

                    <select name="month" id="month" class="form-control">
                        <option value="">Select Month</option>
                        <?php for ($m = 1; $m <= 12; $m++) { echo "<option value='$m'>" . date('F', mktime(0, 0, 0, $m, 1)) . "</option>"; } ?>
                    </select>

                    <select name="year" id="year" class="form-control">
                        <option value="">Select Year</option>
                        <?php for ($y = date('Y'); $y >= 2000; $y--) { echo "<option value='$y'>$y</option>"; } ?>
                    </select>

                    <button type="button" id="apply-filter">Apply Filter</button>
                </form>

                <div id="sales-data" class="text-center">
                    <p class="text-info">Loading sales data...</p>
                </div>
            </div>
        </section>
    </div>
    
    <script>
    $(document).ready(function() {
        function fetchData() {
            $("#sales-data").html("<p class='text-info'>Fetching sales data...</p>");
            $.ajax({
                url: "fetch_records.php",
                type: "GET",
                data: $("#filter-form").serialize(),
                success: function(response) {
                    $("#sales-data").html(response);
                },
                error: function() {
                    $("#sales-data").html("<p class='text-danger'>Failed to fetch data!</p>");
                }
            });
        }

        function validateFilters() {
            let fromDate = $("#from_date").val();
            let toDate = $("#to_date").val();
            let month = $("#month").val();
            let year = $("#year").val();
            let errorMsg = $("#error-msg");

            if ((fromDate || toDate) && (month || year)) {
                errorMsg.text("You cannot apply 'From-To' with any other filter!").css("color", "red");
                return false;
            } else {
                errorMsg.text("");
                return true;
            }
        }

        $("#from_date, #to_date").on("change", function() {
            if ($("#from_date").val() || $("#to_date").val()) {
                $("#month, #year").prop("disabled", true).val(""); // Disable and clear other filters
            } else {
                $("#month, #year").prop("disabled", false);
            }
        });

        $("#month, #year").on("change", function() {
            if ($("#month").val() || $("#year").val()) {
                $("#from_date, #to_date").prop("disabled", true).val(""); // Disable and clear date filters
            } else {
                $("#from_date, #to_date").prop("disabled", false);
            }
        });

        $("#apply-filter").on("click", function() {
            if (validateFilters()) {
                fetchData();
            }
        });

        fetchData(); // Load data on page load
    });
</script>

<p id="error-msg" class="text-center mt-2">Pls use 1 filter at a time for perfect result</p>

</body>
</html>


