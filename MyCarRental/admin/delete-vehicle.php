<?php
// Start session
session_start();
include('includes/dbcon.php'); // Ensure this file contains the correct database connection setup

// Check if admin is logged in
if (!isset($_SESSION['admin_username'])) {
    header("Location: index.php");
    exit();
}

// Check if the vehicle ID is provided
if (isset($_GET['id'])) {
    $vehicleId = $_GET['id'];

    // Prepare and execute the delete query
    $query = "DELETE FROM tblvehicles WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $vehicleId);

    if ($stmt->execute()) {
        echo "<script>
            alert('Vehicle deleted successfully!');
            window.location.href = 'manage-vehicle.php';
        </script>";
    } else {
        echo "<script>
            alert('Error deleting vehicle. Please try again.');
            window.location.href = 'manage-vehicle.php';
        </script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<script>
        alert('Invalid vehicle ID!');
        window.location.href = 'manage-vehicle.php';
    </script>";
}
?>
