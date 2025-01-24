<?php
// Start session
session_start();
include('includes/dbcon.php'); // Ensure this file contains the correct database connection setup

// Check if the admin is logged in
if (!isset($_SESSION['admin_username'])) {
    // Redirect to login page if not logged in
    header("Location: index.php");
    exit();
}

// Get the logged-in admin's username
$adminUsername = $_SESSION['admin_username'];

// Fetch brands data from the database
$query = "SELECT id, BrandName, CreationDate, UpdationDate FROM tblbrands";
$result = $conn->query($query);
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
                <div class="p-5">
                    <h2>Manage Vehicle Brands</h2>
                    <hr>

                    <!-- Manage Vehicles Panel -->
                    <div>
                        <h5>Manage Vehicle Brands</h5>
                        <?php if (isset($_SESSION['success'])): ?>
                                <div id="alertSuccess" class="alert alert-success">
                                    <?php 
                                        echo $_SESSION['success']; 
                                        unset($_SESSION['success']); 
                                    ?>
                                </div>
                            <?php endif; ?>

                            <?php if (isset($_SESSION['error'])): ?>
                                <div id="alertError" class="alert alert-danger">
                                    <?php 
                                        echo $_SESSION['error']; 
                                        unset($_SESSION['error']); 
                                    ?>
                                </div>
                            <?php endif; ?>


                        <table id="vehicleTable" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Brand Name</th>
                                    <th>Creation Date</th>
                                    <th>Updation Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result->num_rows > 0) {
                                    // Fetch each row and display in the table
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($row['BrandName']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['CreationDate']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['UpdationDate']) . "</td>";
                                        echo "<td>
                                                <a href='edit-brand.php?id=" . urlencode($row['id']) . "' class='text-primary'>
                                                    <i class='fas fa-edit'></i> Edit
                                                </a> | 
                                                <a href='delete-brand.php?id=" . urlencode($row['id']) . "' 
                                                class='text-danger' 
                                                onclick='return confirm(\"Are you sure you want to delete this brand?\")'>
                                                    <i class='fas fa-trash-alt'></i> Delete
                                                </a>
                                            </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4'>No brands found</td></tr>";
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

        // Hide success message after 3 seconds
    setTimeout(() => {
        const successAlert = document.getElementById('alertSuccess');
        if (successAlert) {
            successAlert.style.transition = "opacity 0.5s ease";
            successAlert.style.opacity = "0";
            setTimeout(() => successAlert.remove(), 500); // Remove the element after fade-out
        }
    }, 3000);

    // Hide error message after 3 seconds
    setTimeout(() => {
        const errorAlert = document.getElementById('alertError');
        if (errorAlert) {
            errorAlert.style.transition = "opacity 0.5s ease";
            errorAlert.style.opacity = "0";
            setTimeout(() => errorAlert.remove(), 500); // Remove the element after fade-out
        }
    }, 3000);
    </script>
</body>
</html>
