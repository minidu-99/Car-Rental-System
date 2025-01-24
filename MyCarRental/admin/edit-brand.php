<?php
// Start session
session_start();

// Include database connection
include('includes/dbcon.php');

// Check if the admin is logged in
if (!isset($_SESSION['admin_username'])) {
    header("Location: index.php");
    exit();
}

// Get the logged-in admin's username
$adminUsername = $_SESSION['admin_username'];

// Check if ID is passed
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage-brand.php"); // Redirect to Manage Brands page if no ID is passed
    exit();
}

$brandId = $_GET['id'];
$brandName = "";

// Fetch brand details
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $query = "SELECT BrandName FROM tblbrands WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $brandId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $brand = $result->fetch_assoc();
        $brandName = $brand['BrandName'];
    } else {
        header("Location: manage-brand.php");
        exit();
    }
    $stmt->close();
}

// Update brand details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newBrandName = $_POST['brandName'];

    // Check if the brand name already exists
    $checkQuery = "SELECT COUNT(*) FROM tblbrands WHERE BrandName = ? AND id != ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("si", $newBrandName, $brandId);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        $error = "The brand name already exists. Please choose a different name.";
    } else {
        // Proceed with the update if no duplicate
        $query = "UPDATE tblbrands SET BrandName = ?, UpdationDate = NOW() WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $newBrandName, $brandId);
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Brand updated successfully.";
            header("Location: manage-brand.php");
            exit();
        } else {
            $error = "Failed to update the brand. Please try again.";
        }
        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Brand</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <!-- Header -->
    <?php include('includes/header.php'); ?>

    <!-- Main Layout -->
    <div class="container-fluid">
        <div class="row" style="height:100vh">
            <!-- Sidebar -->
            <?php include('includes/sidebar.php'); ?>

            <!-- Content Section -->
            <div class="col-md-9 col-sm-12">
                <div class="container p-5">
                    <h2>Edit Brand</h2>
                    <hr>
                    <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
                    <form method="POST" id="editBrandForm">
                        <div class="mb-3">
                            <label for="brandName" class="form-label">Brand Name</label>
                            <input type="text" class="form-control" id="brandName" name="brandName" value="<?= htmlspecialchars($brandName) ?>" required>
                        </div>
                        <button type="submit" class="btn btn-success" onclick="return confirmUpdate()">Update</button>
                        <a href="manage-brands.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- JavaScript for confirmation alert -->
    <script>
        function confirmUpdate() {
            return confirm("Are you sure you want to update this brand?");
        }
    </script>
</body>
</html>
