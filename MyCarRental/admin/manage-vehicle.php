<?php
// Start session
session_start();

// Include database connection
include('includes/dbcon.php');

// Check if the admin is logged in
if (!isset($_SESSION['admin_username'])) {
    // Redirect to login page if not logged in
    header("Location: index.php");
    exit();
}

// Get the logged-in admin's username
$adminUsername = $_SESSION['admin_username'];


if (isset($_SESSION['success_message'])) {
    echo "<script>alert('" . $_SESSION['success_message'] . "');</script>";
    unset($_SESSION['success_message']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | Manage Vehicles</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="css/styles.css">
    
    <!-- Correct DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <?php include('includes/header.php');?>

    <!-- Main Layout -->
    <div class="container-fluid">
        <div class="row" style="height:100vh">
            <!-- Sidebar -->
            <?php include('includes/sidebar.php');?>

            <!-- Content Section -->
            <div class="col-md-9 col-sm-12">
                <div class=" p-5">
                    <h2>Manage Vehicles</h2>
                    <hr>

                    <!-- Manage Vehicles Panel -->
                    <div class="">
                        <div class="">
                            <h5>Vehicles</h5>
                        </div>
                        <div class="">
                            <table id="vehicleTable" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Vehicle Name</th>
                                        <th>Brand</th>
                                        <th>Price per day</th>
                                        <th>Fuel type</th>
                                        <th>Model year</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $sql = "SELECT id, VehiclesTitle, VehiclesBrand, PricePerDay, FuelType, ModelYear FROM tblvehicles";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $row['id'] . "</td>";
                                            echo "<td>" . htmlspecialchars($row['VehiclesTitle']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['VehiclesBrand']) . "</td>";
                                            echo "<td>Rs." . htmlspecialchars($row['PricePerDay']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['FuelType']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['ModelYear']) . "</td>";
                                            echo "<td>
                                                    <a href='edit-vehicle.php?id=" . $row['id'] . "' class='text-primary'><i class='fas fa-edit'></i> Edit</a>
                                                    <a href='delete-vehicle.php?id=" . $row['id'] . "' class='text-danger' onclick='return confirm(\"Are you sure you want to delete this vehicle?\");'>
                                                        <i class='fas fa-trash-alt'></i> Delete
                                                    </a>
                                                </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='7' class='text-center'>No vehicles found</td></tr>";
                                    }

                                    $conn->close();
                                    ?>

                            </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery (Required for DataTables) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JavaScript Libraries -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

    <!-- DataTables Initialization -->
    <script>
        $(document).ready(function() {
            $('#vehicleTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true
            });
        });
    </script>
</body>
</html>
