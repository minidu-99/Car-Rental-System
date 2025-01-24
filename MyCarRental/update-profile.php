<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in.']);
    exit();
}

// Database connection
include('includes/dbcon.php');

// Get POST data
$fullName = $_POST['fullName'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$dob = $_POST['dob'];
$address = $_POST['address'];
$city = $_POST['city'];
$country = $_POST['country'];
$userName = $_SESSION['user_name'];

// Validate data (basic example)
if (empty($fullName) || empty($email) || empty($phone)) {
    echo json_encode(['success' => false, 'error' => 'All fields are required.']);
    exit();
}

// Update query
$query = "UPDATE tblusers SET FullName = ?, EmailId = ?, ContactNo = ?, dob = ?, Address = ?, City = ?, Country = ? WHERE FullName = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssssssss", $fullName, $email, $phone, $dob, $address, $city, $country, $userName);

if ($stmt->execute()) {
    // If FullName was updated successfully, update the session user_name
    $_SESSION['user_name'] = $fullName;

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Database update failed.']);
}
?>
