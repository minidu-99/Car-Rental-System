<?php
include('includes/dbcon.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and fetch form inputs
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);

    // Check for user in the database
    $query = "SELECT * FROM tblusers WHERE EmailId='$email'";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $user['Password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['FullName'];
            $_SESSION['email'] = $user['EmailId'];
            echo "<script>
                window.location.href = 'index.php';
                alert('Login successful!');
                
            </script>";
        } else {
            echo "<script>
                alert('Invalid password. Please try again.');
                window.history.back();
            </script>";
        }
    } else {
        echo "<script>
            alert('No user found with this email.');
            window.history.back();
        </script>";
    }
}

$conn->close();
?>
