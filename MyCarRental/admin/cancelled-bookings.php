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

// Include database connection
include('includes/dbcon.php');

// Query to fetch booking details along with vehicle title
$query = "
    SELECT 
        b.id AS BookingId,
        b.BookingNumber,
        b.FromDate,
        b.ToDate,
        b.Status,
        b.PostingDate,
        v.VehiclesTitle,
        u.FullName
    FROM 
        tblbooking b
    JOIN 
        tblvehicles v ON b.VehicleId = v.id
    JOIN 
        tblusers u ON b.userEmail = u.EmailId
    WHERE 
        b.Status = 2
    ORDER BY 
        b.PostingDate DESC
";

$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | Cancelled Bookings</title>
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
                    <h2>Cancelled Bookings</h2>
                    <hr>

                    <!-- Manage Vehicles Panel -->
                    <div>
                        <div>
                            <h5>Cancelled Bookings</h5>
                        </div>
                        <div>
                            <table id="vehicleTable" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Booking Number</th>
                                        <th>Vehicle</th>
                                        <th>From Date</th>
                                        <th>To Date</th>
                                        <th>Status</th>
                                        <th>Posting Date</th>
                                        <th>View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($row['BookingId']); ?></td>
                                                <td><?php echo htmlspecialchars($row['FullName']); ?></td>
                                                <td><?php echo htmlspecialchars($row['BookingNumber']); ?></td>
                                                <td><?php echo htmlspecialchars($row['VehiclesTitle']); ?></td>
                                                <td><?php echo htmlspecialchars($row['FromDate']); ?></td>
                                                <td><?php echo htmlspecialchars($row['ToDate']); ?></td>
                                                <td>
                                                    <?php echo $row['Status'] == 0 ? 'Not Confirmed' : 'Confirmed'; ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($row['PostingDate']); ?></td>
                                                <td>
                                                    <a href="deleted-bookings-details.php?id=<?php echo htmlspecialchars($row['BookingId']); ?>" class="text-danger">View</a>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        echo '<tr><td colspan="9">No bookings found</td></tr>';
                                    }
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
