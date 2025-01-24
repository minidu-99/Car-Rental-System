<?php
// Include database connection
include('includes/dbcon.php');

// Check if ID is set in POST request
if (isset($_POST['id'])) {
    $inquiryId = intval($_POST['id']);

    // Update the status in the database
    $query = "UPDATE tblcontactusquery SET status = 1 WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $inquiryId);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }

    $stmt->close();
} else {
    echo 'error';
}
?>
