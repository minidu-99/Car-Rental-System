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


// Fetch vehicle details based on ID
if (isset($_GET['id'])) {
    $vehicleId = $_GET['id'];
    $query = "SELECT * FROM tblvehicles WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $vehicleId);
    $stmt->execute();
    $result = $stmt->get_result();
    $vehicle = $result->fetch_assoc();
} else {
    echo "<script>alert('Invalid vehicle ID!'); window.location.href = 'manage-vehicles.php';</script>";
    exit();
}

if (isset($_POST['updateVehicle'])) {
    // Get the posted data
    $vehicleId = $_POST['vehicleId'];
    $vehicleTitle = $_POST['vehicleTitle'];
    $vehicleOverview = $_POST['vehicleOverview'];
    $pricePerDay = $_POST['pricePerDay'];
    $modelYear = $_POST['modelYear'];
    $brandId = $_POST['selectBrand'];
    $fuelType = $_POST['selectFuelType'];

    // Fetch existing images from the database
    $query = "SELECT Vimage1, Vimage2, Vimage3, Vimage4 FROM tblvehicles WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $vehicleId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $existingImages = [
        $row['Vimage1'],
        $row['Vimage2'],
        $row['Vimage3'],
        $row['Vimage4']
    ];
    $stmt->close();

    // Handle new image uploads
    $images = $_FILES['images'];
    $uploadDirectory = "uploads/";
    $newImages = [];

    for ($i = 0; $i < 4; $i++) {
        if (!empty($images['name'][$i])) {
            $fileExtension = pathinfo($images['name'][$i], PATHINFO_EXTENSION);
            $uniqueName = uniqid("vehicle_", true) . "." . $fileExtension;
            $targetFile = $uploadDirectory . $uniqueName;

            // Validate file size (max 5MB per image)
            if ($images['size'][$i] > 5 * 1024 * 1024) {
                echo "Error: File size for {$images['name'][$i]} exceeds the limit of 5MB.<br>";
                continue;
            }

            // Validate file type (only allow jpg, jpeg, png, gif)
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array(strtolower($fileExtension), $allowedTypes)) {
                echo "Error: File {$images['name'][$i]} is not a valid image format.<br>";
                continue;
            }

            // Move the uploaded file
            if (move_uploaded_file($images['tmp_name'][$i], $targetFile)) {
                $newImages[$i] = $uniqueName; // Save the new image name
            } else {
                echo "Error: Failed to upload {$images['name'][$i]}.<br>";
            }
        } else {
            $newImages[$i] = $existingImages[$i]; // Keep the existing image if no new image is uploaded
        }
    }

    // Update the database with the new data
    $updateQuery = "UPDATE tblvehicles SET 
                        VehiclesTitle = ?, 
                        VehiclesOverview = ?, 
                        PricePerDay = ?, 
                        ModelYear = ?, 
                        VehiclesBrand = ?, 
                        FuelType = ?, 
                        Vimage1 = ?, 
                        Vimage2 = ?, 
                        Vimage3 = ?, 
                        Vimage4 = ?
 
                    WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param(
        "ssiiisssssi",
        $vehicleTitle,
        $vehicleOverview,
        $pricePerDay,
        $modelYear,
        $brandId,
        $fuelType,
        $newImages[0],
        $newImages[1],
        $newImages[2],
        $newImages[3],
        $vehicleId
         
    );

    if ($stmt->execute()) {
        echo "<script>
            alert('Vehicle updated successfully!');
            window.location.href = 'edit-vehicle.php?id=$vehicleId';
        </script>";
    } else {
        echo "<script>
            alert('Error updating vehicle. Please try again.');
            window.location.href = 'edit-vehicle.php?id=$vehicleId';
        </script>";
    }
    
    

    $stmt->close();
}



// Get the logged-in admin's username
$adminUsername = $_SESSION['admin_username'];
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
                            <!-- Display the form with pre-filled data -->
                        <form id="editVehicleForm" method="POST" enctype="multipart/form-data">
                                <!-- Hidden field for vehicle ID -->
                                <input type="hidden" name="vehicleId" value="<?php echo htmlspecialchars($vehicle['id']); ?>">

                                <!-- Vehicle Title -->
                                <div class="mb-3">
                                    <label for="vehicleTitle" class="form-label">Vehicle Title</label>
                                    <input type="text" class="form-control" id="vehicleTitle" name="vehicleTitle" 
                                        value="<?php echo htmlspecialchars($vehicle['VehiclesTitle']); ?>" required>
                                </div>

                                <!-- Vehicle Overview -->
                                <div class="mb-3">
                                    <label for="vehicleOverview" class="form-label">Vehicle Overview</label>
                                    <textarea class="form-control" id="vehicleOverview" name="vehicleOverview" rows="3" required>
                                        <?php echo htmlspecialchars($vehicle['VehiclesOverview']); ?>
                                    </textarea>
                                </div>

                                <!-- Price per Day -->
                                <div class="mb-3">
                                    <label for="pricePerDay" class="form-label">Price per Day</label>
                                    <input type="number" class="form-control" id="pricePerDay" name="pricePerDay" 
                                        value="<?php echo htmlspecialchars($vehicle['PricePerDay']); ?>" required>
                                </div>

                                <!-- Model Year -->
                                <div class="mb-3">
                                    <label for="modelYear" class="form-label">Model Year</label>
                                    <input type="number" class="form-control" id="modelYear" name="modelYear" 
                                        value="<?php echo htmlspecialchars($vehicle['ModelYear']); ?>" required>
                                </div>

                                <!-- Select Brand -->
                                <div class="mb-3">
                                    <label for="selectBrand" class="form-label">Select Brand</label>
                                    <select class="form-select" id="selectBrand" name="selectBrand" required>
                                        <option value="">Select Brand</option>
                                        <?php
                                        $brandQuery = "SELECT id, BrandName FROM tblbrands";
                                        $brands = $conn->query($brandQuery);
                                        while ($brand = $brands->fetch_assoc()) {
                                            $selected = $brand['id'] == $vehicle['BrandId'] ? "selected" : "";
                                            echo "<option value='" . htmlspecialchars($brand['id']) . "' $selected>" . htmlspecialchars($brand['BrandName']) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <!-- Select Fuel Type -->
                                <div class="mb-3">
                                    <label for="selectFuelType" class="form-label">Select Fuel Type</label>
                                    <select class="form-select" id="selectFuelType" name="selectFuelType" required>
                                        <option value="">Select Fuel Type</option>
                                        <option value="Petrol" <?php echo $vehicle['FuelType'] == "Petrol" ? "selected" : ""; ?>>Petrol</option>
                                        <option value="Diesel" <?php echo $vehicle['FuelType'] == "Diesel" ? "selected" : ""; ?>>Diesel</option>
                                        <option value="Electric" <?php echo $vehicle['FuelType'] == "Electric" ? "selected" : ""; ?>>Electric</option>
                                    </select>
                                </div>

                                <!-- Current Images -->
                                <div class="mb-3">
                                    <label class="form-label">Current Images</label>
                                    <div>
                                        <img src="uploads/<?php echo htmlspecialchars($vehicle['Vimage1']); ?>" alt="Image1" style="width: 100px; height: auto;">
                                        <img src="uploads/<?php echo htmlspecialchars($vehicle['Vimage2']); ?>" alt="Image2" style="width: 100px; height: auto;">
                                        <img src="uploads/<?php echo htmlspecialchars($vehicle['Vimage3']); ?>" alt="Image3" style="width: 100px; height: auto;">
                                        <img src="uploads/<?php echo htmlspecialchars($vehicle['Vimage4']); ?>" alt="Image4" style="width: 100px; height: auto;">
                                    </div>
                                </div>

                                <!-- New Image Uploads -->
                                <div class="mb-3">
                                    <label for="image1" class="form-label">Image 1</label>
                                    <input type="file" class="form-control" id="image1" name="images[]" accept="image/*">
                                </div>
                                <div class="mb-3">
                                    <label for="image2" class="form-label">Image 2</label>
                                    <input type="file" class="form-control" id="image2" name="images[]" accept="image/*">
                                </div>
                                <div class="mb-3">
                                    <label for="image3" class="form-label">Image 3</label>
                                    <input type="file" class="form-control" id="image3" name="images[]" accept="image/*">
                                </div>
                                <div class="mb-3">
                                    <label for="image4" class="form-label">Image 4</label>
                                    <input type="file" class="form-control" id="image4" name="images[]" accept="image/*">
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" name="updateVehicle" class="btn btn-success">Update</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('editVehicleForm').addEventListener('submit', function (e) {
            if (!confirm('Are you sure you want to update this vehicle?')) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>