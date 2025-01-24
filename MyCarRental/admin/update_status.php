<?php
include('includes/dbcon.php');

// Handle POST request
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id']) && isset($data['status'])) {
    $id = $data['id'];
    $status = $data['status'];

    // Update the booking status
    $query = "UPDATE tblbooking SET Status = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $status, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false]);
}

$conn->close();
?>
