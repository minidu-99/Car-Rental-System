<?php
// Start session
session_start();
include('includes/dbcon.php'); // Ensure this file contains the correct database connection setup

// Check if the admin is logged in
if (!isset($_SESSION['admin_username'])) {
    header("Location: index.php");
    exit();
}

// Check if the brand ID is provided
if (isset($_GET['id'])) {
    $brandId = intval($_GET['id']);

    // Delete the brand from the database
    $query = "DELETE FROM tblbrands WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $brandId);

    if ($stmt->execute()) {
        // Redirect back to the manage page with success message
        $_SESSION['success'] = "Brand deleted successfully!";
        header("Location: manage-brand.php");
        exit();
    } else {
        // Redirect back with error message
        $_SESSION['error'] = "Failed to delete the brand.";
        header("Location: manage-brand.php");
        exit();
    }
} else {
    // Redirect back if no ID is provided
    $_SESSION['error'] = "Invalid request.";
    header("Location: manage-brand.php");
    exit();
}
?>
