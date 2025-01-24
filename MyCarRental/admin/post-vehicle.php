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

// Get the logged-in admin's username
$adminUsername = $_SESSION['admin_username'];

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $vehicleTitle = $_POST['vehicleTitle'];
    $vehicleOverview = $_POST['vehicleOverview'];
    $pricePerDay = $_POST['pricePerDay'];
    $modelYear = $_POST['modelYear'];
    $selectBrand = $_POST['selectBrand'];
    $selectFuelType = $_POST['selectFuelType'];
    $seatingCapacity = $_POST['seatingCapacity'];


    // Handle accessories (check if checked, otherwise set as 0)
    $airConditioner = isset($_POST['accessories']) && in_array("Air Conditioner", $_POST['accessories']) ? 1 : 0;
    $powerDoorLocks = isset($_POST['accessories']) && in_array("Power Door Locks", $_POST['accessories']) ? 1 : 0;
    $antiLockBrakingSystem = isset($_POST['accessories']) && in_array("AntiLock Braking System", $_POST['accessories']) ? 1 : 0;
    $brakeAssist = isset($_POST['accessories']) && in_array("Brake Assist", $_POST['accessories']) ? 1 : 0;
    $powerSteering = isset($_POST['accessories']) && in_array("Power Steering", $_POST['accessories']) ? 1 : 0;
    $driverAirbag = isset($_POST['accessories']) && in_array("Driver Airbag", $_POST['accessories']) ? 1 : 0;
    $passengerAirbag = isset($_POST['accessories']) && in_array("Passenger Airbag", $_POST['accessories']) ? 1 : 0;
    $powerWindows = isset($_POST['accessories']) && in_array("Power Windows", $_POST['accessories']) ? 1 : 0;
    $cdPlayer = isset($_POST['accessories']) && in_array("CD Player", $_POST['accessories']) ? 1 : 0;
    $centralLocking = isset($_POST['accessories']) && in_array("Central Locking", $_POST['accessories']) ? 1 : 0;
    $crashSensor = isset($_POST['accessories']) && in_array("Crash Sensor", $_POST['accessories']) ? 1 : 0;
    $leatherSeats = isset($_POST['accessories']) && in_array("Leather Seats", $_POST['accessories']) ? 1 : 0;

    // Handle image uploads
    $images = $_FILES['images'];
    $imageNames = [];
    for ($i = 0; $i < count($images['name']); $i++) {
        $imageNames[] = $images['name'][$i];
    }

    // Insert data into tblvehicles
    $query = "INSERT INTO tblvehicles (VehiclesTitle, VehiclesBrand, VehiclesOverview, PricePerDay, FuelType, ModelYear, SeatingCapacity, Vimage1, Vimage2, Vimage3, Vimage4, AirConditioner, PowerDoorLocks, AntiLockBrakingSystem, BrakeAssist, PowerSteering, DriverAirbag, PassengerAirbag, PowerWindows, CDPlayer, CentralLocking, CrashSensor, LeatherSeats, RegDate)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    $stmt = $conn->prepare($query);
    $stmt->bind_param(
        "sssisssssssssssssssssss", 
        $vehicleTitle, 
        $selectBrand, 
        $vehicleOverview, 
        $pricePerDay, 
        $selectFuelType, 
        $modelYear, 
        $seatingCapacity, 
        $imageNames[0], 
        $imageNames[1], 
        $imageNames[2], 
        $imageNames[3], 
        $airConditioner, 
        $powerDoorLocks, 
        $antiLockBrakingSystem, 
        $brakeAssist, 
        $powerSteering, 
        $driverAirbag, 
        $passengerAirbag, 
        $powerWindows, 
        $cdPlayer, 
        $centralLocking, 
        $crashSensor, 
        $leatherSeats
    );
    
    if ($stmt->execute()) {
        // Upload images
        for ($i = 0; $i < count($images['name']); $i++) {
            move_uploaded_file($images['tmp_name'][$i], 'uploads/' . $images['name'][$i]);
        }
        
        $_SESSION['success_message'] = "Vehicle added successfully.";
        header("Location: manage-vehicle.php");

        exit();
    } else {
        $error = "Failed to add vehicle. Please try again.";
    }
    $stmt->close();
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post a Car | Admin Panel</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
     <!-- CSS -->
     <link rel="stylesheet" href="css/styles.css">
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
                <div class="container p-5">
                    <h2>Post a Car</h2>
                    <hr>

                    <!-- Post a Car Form Panel -->
                    <div class="card">
                        <div class="card-header">
                            <h5>Add New Vehicle</h5>
                        </div>
                        <div class="card-body">
                            <form  method="POST" enctype="multipart/form-data">
                                <div class="row">
                                    <!-- Left Column -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="vehicleTitle" class="form-label">Vehicle Title</label>
                                            <input type="text" class="form-control" id="vehicleTitle" name="vehicleTitle" placeholder="Enter Vehicle Title" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="vehicleOverview" class="form-label">Vehicle Overview</label>
                                            <textarea class="form-control" id="vehicleOverview" name="vehicleOverview" rows="3" placeholder="Enter Vehicle Overview" required></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="pricePerDay" class="form-label">Price per Day</label>
                                            <input type="number" class="form-control" id="pricePerDay" name="pricePerDay" placeholder="Enter Price per Day" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="modelYear" class="form-label">Model Year</label>
                                            <input type="number" class="form-control" id="modelYear" name="modelYear" placeholder="Enter Model Year" required>
                                        </div>
                                        <!-- Select Brand -->
                                        <div class="mb-3">
                                            <label for="selectBrand" class="form-label">Select Brand</label>
                                            <select class="form-select" id="selectBrand" name="selectBrand" required>
                                                <option value="">Select Brand</option>
                                                <?php
                                                // Fetch brands from the database
                                                $brandQuery = "SELECT id, BrandName FROM tblbrands";
                                                $result = $conn->query($brandQuery);
                                                while ($brand = $result->fetch_assoc()) {
                                                    echo "<option value='" . $brand['id'] . "'>" . $brand['BrandName'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="selectFuelType" class="form-label">Select Fuel Type</label>
                                            <select class="form-select" id="selectFuelType" name="selectFuelType" required>
                                                <option value="">Select Fuel Type</option>
                                                <option value="Petrol">Petrol</option>
                                                <option value="Diesel">Diesel</option>
                                                <option value="Electric">Electric</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Right Column -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="seatingCapacity" class="form-label">Seating Capacity</label>
                                            <input type="number" class="form-control" id="seatingCapacity" name="seatingCapacity" placeholder="Enter Seating Capacity" required>
                                        </div>

                                        <!-- Accessories Section -->
<h5>Accessories</h5>
<div class="mb-3 row">
    <div class="form-check col-md-6">
        <input type="checkbox" class="form-check-input" id="airConditioner" name="accessories[]" value="Air Conditioner">
        <label class="form-check-label" for="airConditioner">Air Conditioner</label>
    </div>
    <div class="form-check col-md-6">
        <input type="checkbox" class="form-check-input" id="powerDoorLocks" name="accessories[]" value="Power Door Locks">
        <label class="form-check-label" for="powerDoorLocks">Power Door Locks</label>
    </div>
    <div class="form-check col-md-6">
        <input type="checkbox" class="form-check-input" id="antiLockBrakingSystem" name="accessories[]" value="AntiLock Braking System">
        <label class="form-check-label" for="antiLockBrakingSystem">AntiLock Braking System</label>
    </div>
    <div class="form-check col-md-6">
        <input type="checkbox" class="form-check-input" id="brakeAssist" name="accessories[]" value="Brake Assist">
        <label class="form-check-label" for="brakeAssist">Brake Assist</label>
    </div>
    <div class="form-check col-md-6">
        <input type="checkbox" class="form-check-input" id="powerSteering" name="accessories[]" value="Power Steering">
        <label class="form-check-label" for="powerSteering">Power Steering</label>
    </div>
    <div class="form-check col-md-6">
        <input type="checkbox" class="form-check-input" id="driverAirbag" name="accessories[]" value="Driver Airbag">
        <label class="form-check-label" for="driverAirbag">Driver Airbag</label>
    </div>
    <div class="form-check col-md-6">
        <input type="checkbox" class="form-check-input" id="passengerAirbag" name="accessories[]" value="Passenger Airbag">
        <label class="form-check-label" for="passengerAirbag">Passenger Airbag</label>
    </div>
    <div class="form-check col-md-6">
        <input type="checkbox" class="form-check-input" id="powerWindows" name="accessories[]" value="Power Windows">
        <label class="form-check-label" for="powerWindows">Power Windows</label>
    </div>
    <div class="form-check col-md-6">
        <input type="checkbox" class="form-check-input" id="cdPlayer" name="accessories[]" value="CD Player">
        <label class="form-check-label" for="cdPlayer">CD Player</label>
    </div>
    <div class="form-check col-md-6">
        <input type="checkbox" class="form-check-input" id="centralLocking" name="accessories[]" value="Central Locking">
        <label class="form-check-label" for="centralLocking">Central Locking</label>
    </div>
    <div class="form-check col-md-6">
        <input type="checkbox" class="form-check-input" id="crashSensor" name="accessories[]" value="Crash Sensor">
        <label class="form-check-label" for="crashSensor">Crash Sensor</label>
    </div>
    <div class="form-check col-md-6">
        <input type="checkbox" class="form-check-input" id="leatherSeats" name="accessories[]" value="Leather Seats">
        <label class="form-check-label" for="leatherSeats">Leather Seats</label>
    </div>
</div>


                                        <!-- Image Upload Section -->
                                        <h5>Upload Images</h5>
                                        <div class="row">
                                        <div class="mb-3 col-md-6">
                                            <label for="image1" class="form-label">Image 1</label>
                                            <input type="file" class="form-control" id="image1" name="images[]" accept="image/*" required>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label for="image2" class="form-label">Image 2</label>
                                            <input type="file" class="form-control" id="image2" name="images[]" accept="image/*" required>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label for="image3" class="form-label">Image 3</label>
                                            <input type="file" class="form-control" id="image3" name="images[]" accept="image/*" required>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label for="image4" class="form-label">Image 4</label>
                                            <input type="file" class="form-control" id="image4" name="images[]" accept="image/*" required>
                                        </div>

                                        <!-- Submit Button -->
                                        <button type="submit" class="btn btn-success">Submit</button>
                                    </div>
                                </div>
                            </form>
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
