<?php
// Start session
session_start();
$adminUsername = $_SESSION['admin_username'];

// Check if the admin is logged in
if (!isset($_SESSION['admin_username'])) {
    // Redirect to login page if not logged in
    header("Location: index.php");
    exit();
}

// Include database configuration
include('includes/dbcon.php');

// Fetch counts from the database
// Fetch counts from the database
try {
    // Total Registered Users
    $query = $conn->prepare("SELECT COUNT(*) FROM tblusers");
    $query->execute();
    $query->bind_result($totalUsers);
    $query->fetch();
    $query->close();

    // Total Listed Vehicles
    $query = $conn->prepare("SELECT COUNT(*) FROM tblvehicles");
    $query->execute();
    $query->bind_result($totalVehicles);
    $query->fetch();
    $query->close();

    // Total Bookings (status = 0)
    $query = $conn->prepare("SELECT COUNT(*) FROM tblbooking WHERE status = 0");
    $query->execute();
    $query->bind_result($totalBookings);
    $query->fetch();
    $query->close();

    // Total Listed Brands
    $query = $conn->prepare("SELECT COUNT(*) FROM tblbrands");
    $query->execute();
    $query->bind_result($totalBrands);
    $query->fetch();
    $query->close();

    // Total Queries (status = 0)
    $query = $conn->prepare("SELECT COUNT(*) FROM tblcontactusquery WHERE status = 0");
    $query->execute();
    $query->bind_result($totalQueries);
    $query->fetch();
    $query->close();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <!-- Header -->
    <?php include('includes/header.php'); ?>

    <!-- Main Layout -->
    <div class="container-fluid">
        <div class="row" style="height:100vh">
            <!-- Sidebar -->
            <?php include('includes/sidebar.php'); ?>

            <!-- Content Section -->
            <div class="col-md-9 col-sm-12">
                <div class="p-5">
                    <h2>Dashboard</h2>
                    <hr>
                    <div class="row">
                        <!-- Panel 1: Registered Users (Blue Background) -->
                        <div class="col-md-3 col-sm-6 p-3">
                            <div class="card text-white shadow-sm">
                                <div class="card-body bg-primary">
                                    <h5 class="card-title text-center">Registered Users</h5>
                                    <h3 class="card-text text-center"><?php echo $totalUsers; ?></h3>
                                </div>
                                <div class="card-footer text-center">
                                    <a href="registered-users.php" class="btn btn-link">See More &gt;</a>
                                </div>
                            </div>
                        </div>

                        <!-- Panel 2: Listed Vehicles (Green Background) -->
                        <div class="col-md-3 col-sm-6 p-3">
                            <div class="card text-white shadow-sm">
                                <div class="card-body bg-success">
                                    <h5 class="card-title text-center">Listed Vehicles</h5>
                                    <h3 class="card-text text-center"><?php echo $totalVehicles; ?></h3>
                                </div>
                                <div class="card-footer text-center">
                                    <a href="manage-vehicle.php" class="btn btn-link">See More &gt;</a>
                                </div>
                            </div>
                        </div>

                        <!-- Panel 3: Total Bookings (Yellow Background) -->
                        <div class="col-md-3 col-sm-6 p-3">
                            <div class="card text-white shadow-sm">
                                <div class="card-body bg-warning">
                                    <h5 class="card-title text-center">Total Bookings</h5>
                                    <h3 class="card-text text-center"><?php echo $totalBookings; ?></h3>
                                </div>
                                <div class="card-footer text-center">
                                    <a href="new-bookings.php" class="btn btn-link">See More &gt;</a>
                                </div>
                            </div>
                        </div>

                        <!-- Panel 4: Listed Brands (Light Blue Background) -->
                        <div class="col-md-3 col-sm-6 p-3">
                            <div class="card text-white shadow-sm">
                                <div class="card-body bg-info">
                                    <h5 class="card-title text-center">Listed Brands</h5>
                                    <h3 class="card-text text-center"><?php echo $totalBrands; ?></h3>
                                </div>
                                <div class="card-footer text-center">
                                    <a href="manage-brand.php" class="btn btn-link">See More &gt;</a>
                                </div>
                            </div>
                        </div>

                        <!-- Panel 5: Queries (Red Background) -->
                        <div class="col-md-3 col-sm-6 p-3">
                            <div class="card text-white shadow-sm">
                                <div class="card-body bg-danger">
                                    <h5 class="card-title text-center">Queries</h5>
                                    <h3 class="card-text text-center"><?php echo $totalQueries; ?></h3>
                                </div>
                                <div class="card-footer text-center">
                                    <a href="manage-inquiries.php" class="btn btn-link">See More &gt;</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
