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

// Handle form submission for creating a new brand
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the brand name from the form
    $brandName = trim($_POST['brandName']);

    // Validate the brand name
    if (!empty($brandName)) {
        // Check if the brand already exists in the database
        $checkQuery = "SELECT COUNT(*) FROM tblbrands WHERE BrandName = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("s", $brandName);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            echo "<script>
                    alert('Error: The brand name already exists.');
                  </script>";
        } else {
            // Prepare an SQL query to insert the brand into the tblbrands table
            $query = "INSERT INTO tblbrands (BrandName) VALUES (?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $brandName);

            if ($stmt->execute()) {
                echo "<script>
                        alert('Brand successfully added!');
                        window.location.href = 'manage-brand.php'; // Reload the page
                      </script>";
            } else {
                echo "<script>
                        alert('Error adding brand. Please try again.');
                      </script>";
            }
            $stmt->close();
        }
    } else {
        echo "<script>
                alert('Brand name cannot be empty.');
              </script>";
    }

    $conn->close();
}

// Get the logged-in admin's username
$adminUsername = $_SESSION['admin_username'];
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
    <!-- DATA TABLES -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Custom CSS -->
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
                <div class="container p-5">
                    <h2>Create Brand</h2>
                    <hr>
                    <!-- Create Brand Panel -->
                    <div class="card">
                        <div class="card-header">
                            <h5>Create New Brand</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="brandName" class="form-label">Brand Name</label>
                                    <input type="text" class="form-control" id="brandName" name="brandName" placeholder="Enter Brand Name" required>
                                </div>
                                <button type="submit" class="btn btn-success">Add</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JavaScript Libraries -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
</body>
</html>
