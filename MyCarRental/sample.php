<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    // Redirect to login if not logged in
    header('Location: login.php');
    exit();
}

// Database connection
include('includes/config.php');

// Fetch bookings for the logged-in user
$userEmail = $_SESSION['user_email']; // Assuming email is stored in the session
$query = "
    SELECT 
        b.BookingNumber, b.FromDate, b.ToDate, b.Status, 
        v.VehiclesTitle, v.PricePerDay, v.Vimage1,
        DATEDIFF(b.ToDate, b.FromDate) AS TotalDays,
        (DATEDIFF(b.ToDate, b.FromDate) * v.RentPerDay) AS GrandTotal
    FROM 
        tblbooking b
    JOIN 
        tblvehicles v ON b.VehicleId = v.id
    WHERE 
        b.userEmail = ?
    ORDER BY 
        b.PostingDate DESC
";
$stmt = $conn->prepare($query);
$stmt->execute([$userEmail]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vihanga Auto | My Bookings</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>

<?php include('includes/header.php'); ?>

<header class="profile-header position-relative">
    <div class="overlay"></div>
    <div class="header-content text-center text-light">
        <h1 class="fw-bold">My Bookings</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="index.php" class="text-light text-decoration-underline">Home</a></li>
                <li> > </li>
                <li class="breadcrumb-item active text-light" aria-current="page">My Bookings</li>
            </ol>
        </nav>
    </div>
</header>

<!-- My Bookings Section -->
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h4 class="mb-4">My Bookings</h4>

            <?php if (!empty($bookings)): ?>
                <?php foreach ($bookings as $booking): ?>
                    <div class="card mb-4">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="admin/uploads/<?php echo htmlspecialchars($booking['Vimage1']); ?>" 
                                     class="img-fluid rounded-start" alt="<?php echo htmlspecialchars($booking['VehiclesTitle']); ?>">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($booking['VehiclesTitle']); ?></h5>
                                    <p class="card-text"><strong>Booking Number:</strong> <?php echo htmlspecialchars($booking['BookingNumber']); ?></p>
                                    <p class="card-text"><strong>From:</strong> <?php echo htmlspecialchars($booking['FromDate']); ?> 
                                    <strong>To:</strong> <?php echo htmlspecialchars($booking['ToDate']); ?></p>
                                    <p class="card-text">
                                        <strong>Status:</strong>
                                        <span class="badge 
                                            <?php echo ($booking['Status'] == 0) ? 'bg-warning text-dark' : 
                                                        (($booking['Status'] == 1) ? 'bg-success' : 'bg-danger'); ?>">
                                            <?php echo ($booking['Status'] == 0) ? 'Not Confirmed' : 
                                                        (($booking['Status'] == 1) ? 'Confirmed' : 'Cancelled'); ?>
                                        </span>
                                    </p>
                                    <p class="card-text"><strong>Price per Day:</strong> $<?php echo htmlspecialchars($booking['RentPerDay']); ?></p>
                                    <p class="card-text"><strong>Total Days:</strong> <?php echo htmlspecialchars($booking['TotalDays']); ?></p>
                                    <p class="card-text"><strong>Grand Total:</strong> $<?php echo htmlspecialchars($booking['GrandTotal']); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">You have no bookings yet.</p>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
