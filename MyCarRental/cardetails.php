<?php
session_start();

include('includes/dbcon.php');

// Check if the 'id' parameter is set in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $carId = intval($_GET['id']);

    // Query to fetch car details from the database
    $query = "SELECT * FROM tblvehicles WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $carId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $car = $result->fetch_assoc();
    } else {
        header('Location: index.php');
        exit();
    }
} else {
    header('Location: index.php');
    exit();
}

// Handle Booking Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the user is logged in
    if (!isset($_SESSION['email'])) {

       // User is not logged in, set alert message in the session
       $_SESSION['alert_message'] = 'Please log in to the system first!';
       header("Location: " . $_SERVER['PHP_SELF'] . "?id=$carId"); // Redirect back to the same page
       exit();
    

    } else {
        $fromDate = $_POST['fromDate'];
        $toDate = $_POST['toDate'];
        $message = $_POST['message'];
        $userEmail = $_SESSION['email']; // User's email from session
        $status = 0; // Default status for new bookings
        $currentDate = date('Y-m-d');
        $bookingNumber = mt_rand(100000000, 999999999); // Generate a unique booking number

        // Validate the dates
        if ($fromDate < $currentDate || $toDate < $currentDate || $fromDate > $toDate) {
            echo "<script>alert('Invalid dates. Please select valid dates.');</script>";
        } else {
            // Check if the vehicle is already booked for the requested dates
            $query = "SELECT * FROM tblbooking WHERE VehicleId = ? AND (FromDate BETWEEN ? AND ? OR ToDate BETWEEN ? AND ? OR (? BETWEEN FromDate AND ToDate))";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('isssss', $carId, $fromDate, $toDate, $fromDate, $toDate, $fromDate);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<script>alert('The car is already booked for the selected dates.');</script>";
            } else {
                // Insert the booking into the database
                $query = "INSERT INTO tblbooking (BookingNumber, userEmail, VehicleId, FromDate, ToDate, message, Status, PostingDate) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('ssisssi', $bookingNumber, $userEmail, $carId, $fromDate, $toDate, $message, $status);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    echo "<script>alert('Booking successful! Your booking number is $bookingNumber.');</script>";
                } else {
                    echo "<script>alert('An error occurred. Please try again.');</script>";
                }
            }
        }
    }
}

// Display Alert Message (if exists) and clear it
if (isset($_SESSION['alert_message'])) {
    echo "<script>alert('" . $_SESSION['alert_message'] . "');</script>";
    unset($_SESSION['alert_message']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vihanga Auto | <?php echo htmlspecialchars($car['VehiclesTitle']); ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>

<?php include('includes/header.php'); ?>

<div class="container my-5">
    <!-- Car Image Carousel -->
    <div id="carCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="admin/uploads/<?php echo htmlspecialchars($car['Vimage1']); ?>" class="d-block w-70" alt="<?php echo htmlspecialchars($car['VehiclesTitle']); ?>">
            </div>
            <div class="carousel-item">
                <img src="admin/uploads/<?php echo htmlspecialchars($car['Vimage2']); ?>" class="d-block w-70" alt="Car Image 2">
            </div>
            <div class="carousel-item">
                <img src="admin/uploads/<?php echo htmlspecialchars($car['Vimage3']); ?>" class="d-block w-70" alt="Car Image 3">
            </div>
            <div class="carousel-item">
                <img src="admin/uploads/<?php echo htmlspecialchars($car['Vimage4']); ?>" class="d-block w-70" alt="Car Image 3">
            </div>
            <!-- Add more images if needed -->
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon bg-danger text-light rounded-circle" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon bg-danger text-light rounded-circle" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- Car Details -->
    <div class="row">
        <div class="col-md-8">
            <h3><?php echo htmlspecialchars($car['VehiclesTitle']); ?></h3>
            <h5 class="text-danger">Rs. <?php echo htmlspecialchars($car['PricePerDay']); ?> Per Day</h5>
            <p><i class="fas fa-gas-pump"></i> <?php echo htmlspecialchars($car['FuelType']); ?></p>
            <p><i class="fas fa-calendar"></i> <?php echo htmlspecialchars($car['ModelYear']); ?></p>
            <p><i class="fas fa-users"></i> <?php echo htmlspecialchars($car['SeatingCapacity']); ?> Seats</p>

            <!-- Tabs for Description and Accessories -->
            <div class="mt-4">
                <ul class="nav nav-tabs" id="carDetailsTab" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab">Vehicle Description</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="accessories-tab" data-bs-toggle="tab" data-bs-target="#accessories" type="button" role="tab">Accessories</button>
                    </li>
                </ul>
                <div class="tab-content mt-3">
                    <div class="tab-pane fade show active" id="description" role="tabpanel">
                        <p><?php echo htmlspecialchars($car['VehiclesOverview']); ?></p>
                    </div>
                    <div class="tab-pane fade" id="accessories" role="tabpanel">
                    <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Accessory</th>
                                    <th>Availability</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Air Conditioner</td>
                                    <td>
                                    <i class="fas <?= $car['AirConditioner'] ? 'fa-check text-success' : 'fa-times text-danger'; ?>"></i>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Power Door Locks</td>
                                    <td>
                                    <i class="fas <?= $car['PowerDoorLocks'] ? 'fa-check text-success' : 'fa-times text-danger'; ?>"></i>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Anti Lock Braking System</td>
                                    <td>
                                    <i class="fas <?= $car['AntiLockBrakingSystem'] ? 'fa-check text-success' : 'fa-times text-danger'; ?>"></i>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Brake Assist</td>
                                    <td>
                                    <i class="fas <?= $car['BrakeAssist'] ? 'fa-check text-success' : 'fa-times text-danger'; ?>"></i>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Power Steering</td>
                                    <td>
                                    <i class="fas <?= $car['PowerSteering'] ? 'fa-check text-success' : 'fa-times text-danger'; ?>"></i>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Driver Airbag</td>
                                    <td>
                                    <i class="fas <?= $car['DriverAirbag'] ? 'fa-check text-success' : 'fa-times text-danger'; ?>"></i>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Passenger Airbag</td>
                                    <td>
                                    <i class="fas <?= $car['PassengerAirbag'] ? 'fa-check text-success' : 'fa-times text-danger'; ?>"></i>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Power Windows</td>
                                    <td>
                                    <i class="fas <?= $car['PowerWindows'] ? 'fa-check text-success' : 'fa-times text-danger'; ?>"></i>
                                    </td>
                                </tr>

                                <tr>
                                    <td>CD Player</td>
                                    <td>
                                    <i class="fas <?= $car['CDPlayer'] ? 'fa-check text-success' : 'fa-times text-danger'; ?>"></i>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Central Locking</td>
                                    <td>
                                    <i class="fas <?= $car['CentralLocking'] ? 'fa-check text-success' : 'fa-times text-danger'; ?>"></i>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Crash Sensor</td>
                                    <td>
                                    <i class="fas <?= $car['CrashSensor'] ? 'fa-check text-success' : 'fa-times text-danger'; ?>"></i>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Leather Seats</td>
                                    <td>
                                    <i class="fas <?= $car['LeatherSeats'] ? 'fa-check text-success' : 'fa-times text-danger'; ?>"></i>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Form -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="m-0">Book Now</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="fromDate" class="form-label">From Date</label>
                            <input type="date" id="fromDate" name="fromDate" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="toDate" class="form-label">To Date</label>
                            <input type="date" id="toDate" name="toDate" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea id="message" name="message" class="form-control" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger w-100">Book Now</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
