<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in.']);
    exit();
}

// Database connection
include('includes/dbcon.php');

// Get POST data
$newPassword = $_POST['newPassword'];
$confirmPassword = $_POST['confirmPassword'];
$userName = $_SESSION['user_name'];

// Validate data (check if both passwords match)
if (empty($newPassword) || empty($confirmPassword)) {
    echo json_encode(['success' => false, 'error' => 'Both fields are required.']);
    exit();
}

if ($newPassword !== $confirmPassword) {
    echo json_encode(['success' => false, 'error' => 'Passwords do not match.']);
    exit();
}

// Hash the new password
$hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

// Update password in the database
$query = "UPDATE tblusers SET Password = ? WHERE FullName = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $hashedPassword, $userName);

// Execute the query
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Password updated successfully.']);
} else {
    echo json_encode(['success' => false, 'error' => 'Database update failed.']);
}
?>
