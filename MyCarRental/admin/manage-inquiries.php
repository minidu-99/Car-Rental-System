<?php
// Start session
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_username'])) {
    // Redirect to login page if not logged in
    header("Location: index.php");
    exit();
}

// Include database connection
include('includes/dbcon.php');

// Get the logged-in admin's username
$adminUsername = $_SESSION['admin_username'];

// Fetch inquiries from the database
$query = "SELECT * FROM tblcontactusquery ORDER BY PostingDate DESC";
$result = mysqli_query($conn, $query);
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
    <?php include('includes/header.php'); ?>

    <!-- Main Layout -->
    <div class="container-fluid">
        <div class="row" style="height:100vh;">
            <!-- Sidebar -->
            <?php include('includes/sidebar.php'); ?>

            <!-- Content Section -->
            <div class="col-md-9 col-sm-12">
                <div class="p-5">
                    <h2>Manage Inquiries</h2>
                    <hr>

                    <!-- Manage Inquiries Panel -->
                    <div>
                        <h5>Inquiries</h5>
                        <table id="inquiryTable" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone Number</th>
                                    <th>Message</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['EmailId']); ?></td>
                                        <td><?php echo htmlspecialchars($row['ContactNumber']); ?></td>
                                        <td><?php echo htmlspecialchars($row['Message']); ?></td>
                                        <td>
                                            <?php if ($row['status'] == 0) { ?>
                                                <a href="#" class="text-danger change-status" data-id="<?php echo $row['id']; ?>">Pending</a>
                                            <?php } else { ?>
                                                <span class="text-success">Read</span>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
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
            $('#inquiryTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true
            });

            // Handle status change
            $('.change-status').on('click', function(e) {
                e.preventDefault();
                const inquiryId = $(this).data('id');
                if (confirm('Do you really want to mark this as read?')) {
                    // Send AJAX request to update the status
                    $.ajax({
                        url: 'update_inquiry_status.php',
                        type: 'POST',
                        data: { id: inquiryId },
                        success: function(response) {
                            if (response === 'success') {
                                alert('Inquiry marked as read.');
                                location.reload(); // Reload the page to update the table
                            } else {
                                alert('Failed to update the status. Please try again.');
                            }
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
