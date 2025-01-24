<?php
// Start session
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_username'])) {
    // Redirect to login page if not logged in
    header("Location: index.php");
    exit();
}

// Get the logged-in admin's username
$adminUsername = $_SESSION['admin_username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | Registered Users</title>
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
        <div class="row">
            <!-- Sidebar -->
            <?php include('includes/sidebar.php');?>

            <!-- Content Section -->
            <div class="col-md-9 col-sm-12">
                <div class=" p-5">
                    <h2>Registered Users</h2>
                    <hr>

                    <!-- Manage Vehicles Panel -->
                    <div class="">
                        <div class="">
                            <h5>Registered Users</h5>
                        </div>
                        <div class="">
                            <table id="vehicleTable" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                        <th>Address</th>
                                        <th>Reg date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Tharindu Sathsara</td>
                                        <td>min@fj.com</td>
                                        <td>0729610887</td>
                                        <td>Hi iwant to know that</td>
                                        <td>2019-10-20</td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Minidu Sathsara</td>
                                        <td>min@fj.com</td>
                                        <td>0729610887</td>
                                        <td>Hi iwant to know that</td>
                                        <td>
                                            <a href="#" class="text-danger"><i class="fas fa-trash-alt"></i> Delete</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Minidu Sathsara</td>
                                        <td>min@fj.com</td>
                                        <td>0729610887</td>
                                        <td>Hi iwant to know that</td>
                                        <td>
                                            <a href="#" class="text-danger"><i class="fas fa-trash-alt"></i> Delete</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>Minidu Sathsara</td>
                                        <td>min@fj.com</td>
                                        <td>0729610887</td>
                                        <td>Hi iwant to know that</td>
                                        <td>
                                            <a href="#" class="text-danger"><i class="fas fa-trash-alt"></i> Delete</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td>Minidu Sathsara</td>
                                        <td>min@fj.com</td>
                                        <td>0729610887</td>
                                        <td>Hi iwant to know that</td>
                                        <td>
                                            <a href="#" class="text-danger"><i class="fas fa-trash-alt"></i> Delete</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>6</td>
                                        <td>Minidu Sathsara</td>
                                        <td>min@fj.com</td>
                                        <td>0729610887</td>
                                        <td>Hi iwant to know that</td>
                                        <td>
                                            <a href="#" class="text-danger"><i class="fas fa-trash-alt"></i> Delete</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>7</td>
                                        <td>Minidu Sathsara</td>
                                        <td>min@fj.com</td>
                                        <td>0729610887</td>
                                        <td>Hi iwant to know that</td>
                                        <td>
                                            <a href="#" class="text-danger"><i class="fas fa-trash-alt"></i> Delete</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>8</td>
                                        <td>Minidu Sathsara</td>
                                        <td>min@fj.com</td>
                                        <td>0729610887</td>
                                        <td>Hi iwant to know that</td>
                                        <td>
                                            <a href="#" class="text-danger"><i class="fas fa-trash-alt"></i> Delete</a>
                                        </td>
                                    </tr>
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
