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

// Check if booking ID is passed in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Invalid Booking ID";
    exit();
}

$bookingId = $_GET['id'];

// Fetch booking details from the database
$query = "
    SELECT 
        b.BookingNumber, 
        b.FromDate, 
        b.ToDate, 
        b.Status, 
        b.PostingDate, 
        DATEDIFF(b.ToDate, b.FromDate) AS TotalDays, 
        v.PricePerDay, 
        (DATEDIFF(b.ToDate, b.FromDate) * v.PricePerDay) AS GrandTotal,
        v.VehiclesTitle,
        u.FullName, 
        u.EmailId, 
        u.ContactNo, 
        u.Address, 
        u.City, 
        u.Country
    FROM 
        tblbooking b
    JOIN 
        tblvehicles v ON b.VehicleId = v.id
    JOIN 
        tblusers u ON b.userEmail = u.EmailId
    WHERE 
        b.id = ?
";


$stmt = $conn->prepare($query);
$stmt->bind_param("i", $bookingId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "No Booking Found!";
    exit();
}

$bookingDetails = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | New Booking</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="css/styles.css">

    <style>
        .invoice-title {
            color: red;
            text-align: center;
            margin-bottom: 20px;
        }

        .action-buttons, .print-button {
            margin-top: 20px;
            text-align: center;
        }

        /* Hide everything except the invoice when printing */
        @media print {
            body * {
                visibility: hidden;
            }
            .print-area, .print-area * {
                visibility: visible;
            }
            .print-area {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
            }

            /* Hide action buttons when printing */
            .action-buttons,
            .print-button {
                display: none !important;
            }
        }
    </style>
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
                <div class="container p-5">
                    <h2>Booking Details</h2>
                    <div class="container mt-4 print-area">
                        <h1 class="invoice-title">#<?php echo htmlspecialchars($bookingDetails['BookingNumber']); ?> Booking Details</h1>

                        <!-- User Details Table -->
                        <table class="table table-bordered">
                            <thead class="table-secondary">
                                <tr>
                                    <th colspan="4">User Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Booking No.</strong></td>
                                    <td>#<?php echo htmlspecialchars($bookingDetails['BookingNumber']); ?></td>
                                    <td><strong>Name</strong></td>
                                    <td><?php echo htmlspecialchars($bookingDetails['FullName']); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Email Id</strong></td>
                                    <td><?php echo htmlspecialchars($bookingDetails['EmailId']); ?></td>
                                    <td><strong>Contact No</strong></td>
                                    <td><?php echo htmlspecialchars($bookingDetails['ContactNo']); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Address</strong></td>
                                    <td><?php echo htmlspecialchars($bookingDetails['Address']); ?></td>
                                    <td><strong>City</strong></td>
                                    <td><?php echo htmlspecialchars($bookingDetails['City']); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Country</strong></td>
                                    <td><?php echo htmlspecialchars($bookingDetails['Country']); ?></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                            <thead class="table-secondary">
                                <tr>
                                    <th colspan="4">Booking Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Vehicle Name</strong></td>
                                    <td><?php echo htmlspecialchars($bookingDetails['VehiclesTitle']); ?></td>
                                    <td><strong>Booking Date</strong></td>
                                    <td><?php echo htmlspecialchars($bookingDetails['PostingDate']); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>From Date</strong></td>
                                    <td><?php echo htmlspecialchars($bookingDetails['FromDate']); ?></td>
                                    <td><strong>To Date</strong></td>
                                    <td><?php echo htmlspecialchars($bookingDetails['ToDate']); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Total Days</strong></td>
                                    <td><?php echo htmlspecialchars($bookingDetails['TotalDays']); ?></td>
                                    <td><strong>Rent Per Day</strong></td>
                                    <td><?php echo htmlspecialchars($bookingDetails['PricePerDay']); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Grand Total</strong></td>
                                    <td><?php echo htmlspecialchars($bookingDetails['GrandTotal']); ?></td>
                                    <td><strong>Booking Status</strong></td>
                                    <td><?php echo ($bookingDetails['Status'] == 0) ? "Not Confirmed Yet" : (($bookingDetails['Status'] == 1) ? "Confirmed" : "Cancelled"); ?></td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            <button class="btn btn-success" onclick="updateStatus(1, <?php echo $bookingId; ?>)">Confirm Booking</button>
                            <button class="btn btn-danger" onclick="updateStatus(2, <?php echo $bookingId; ?>)">Cancel Booking</button>
                        </div>

                        <!-- Print Button -->
                        <div class="print-button">
                            <button class="btn btn-secondary" onclick="printInvoice()">Print</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function printInvoice() {
            window.print();
        }

        function updateStatus(status, bookingId) {
        const action = status === 1 ? 'confirm' : 'cancel';
        const confirmMessage = `Are you sure you want to ${action} this booking?`;
        if (confirm(confirmMessage)) {
            // Send request to update status
            fetch(`update_status.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ status: status, id: bookingId }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(`Booking ${action}ed successfully!`);
                    location.reload(); // Reload the page to reflect changes
                } else {
                    alert(`Failed to ${action} booking. Please try again.`);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }
    </script>
</body>
</html>
