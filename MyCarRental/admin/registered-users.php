
<?php
// Start session
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_username'])) {
    // Redirect to login page if not logged in
    header("Location: index.php");
    exit();
}

$adminUsername = $_SESSION['admin_username'];

// Include the database configuration
include('includes/dbcon.php');

// Initialize an empty array to store user data
$users = [];

// Fetch registered users from the database
try {
    $query = $conn->prepare("SELECT id, FullName, EmailId, ContactNo, Address, RegDate FROM tblusers");
    $query->execute();
    $result = $query->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $users[] = $row; // Add each user to the users array
    }

    $query->close();
} catch (Exception $e) {
    echo "Error fetching data: " . $e->getMessage();
}
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
    <?php include('includes/header.php'); ?>

    <!-- Main Layout -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?php include('includes/sidebar.php'); ?>

            <!-- Content Section -->
            <div class="col-md-9 col-sm-12">
                <div class="p-5">
                    <h2>Registered Users</h2>
                    <hr>

                    <!-- Registered Users Table -->
                    <div>
                        <h5>Registered Users</h5>
                        <div>
                            <table id="vehicleTable" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                        <th>Address</th>
                                        <th>Reg Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($users)) : ?>
                                        <?php foreach ($users as $user) : ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($user['id']); ?></td>
                                                <td><?php echo htmlspecialchars($user['FullName']); ?></td>
                                                <td><?php echo htmlspecialchars($user['EmailId']); ?></td>
                                                <td><?php echo htmlspecialchars($user['ContactNo']); ?></td>
                                                <td><?php echo htmlspecialchars($user['Address']); ?></td>
                                                <td><?php echo htmlspecialchars($user['RegDate']); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <tr>
                                            <td colspan="6" class="text-center">No registered users found.</td>
                                        </tr>
                                    <?php endif; ?>
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
