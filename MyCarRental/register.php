<?php
include('includes/dbcon.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and fetch form inputs
    $fullName = $conn->real_escape_string($_POST['fullName']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($conn->real_escape_string($_POST['password']), PASSWORD_BCRYPT); // Hash password
    $contactNo = $conn->real_escape_string($_POST['phone']);

    // Check if email already exists
    $checkEmailQuery = "SELECT EmailId FROM tblusers WHERE EmailId='$email'";
    $result = $conn->query($checkEmailQuery);

    if ($result->num_rows > 0) {
        echo "<script>
            alert('Email already exists. Please use a different email.');
            window.history.back();
        </script>";
    } else {
        // Insert the new user
        $query = "INSERT INTO tblusers (FullName, EmailId, Password, ContactNo) 
                  VALUES ('$fullName', '$email', '$password', '$contactNo')";
        if ($conn->query($query)) {
            echo "<script>
                alert('Registration successful. You can now login.');
                window.location.href = 'index.php';
            </script>";
        } else {
            echo "<script>
                alert('Error: " . $conn->error . "');
                window.history.back();
            </script>";
        }
    }
}

$conn->close();
?>
