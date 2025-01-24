<?php
session_start();

// Database connection
include('includes/dbcon.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get username and password from the form
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    // Input validation
    if (empty($username) || empty($password)) {
        echo "<script>alert('Both fields are required!');</script>";
        exit();
    }

    // Check credentials in the database
    $query = "SELECT * FROM admin WHERE UserName = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Verify password
        if($password == $row['Password']) {
            // Store user details in session
            $_SESSION['admin_username'] = $row['UserName'];

            // Redirect to admin dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<script>alert('Invalid password!');</script>";
        }
    } else {
        echo "<script>alert('Invalid username!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <!-- Link Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>

    body {
        background-color: #f8d7da; /* Light red shade for background */
        color: #212529; /* Dark text for contrast */
    }

    .login-container {
        max-width: 400px;
        margin: 100px auto;
        background-color: #ffffff; /* White background for the login box */
        border: 1px solid #ced4da; /* Light black border */
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Soft shadow */
        padding: 20px;
    }

    .login-container h3 {
        color: #721c24; /* Dark red for the title */
        text-align: center;
    }

    .btn-login {
        background-color: #721c24; /* Dark red button */
        color: white;
        border: none;
    }

    .btn-login:hover {
        background-color: #5a121b; /* Slightly darker red on hover */
    }
    </style>
        
</head>
<body>
    <div class="login-container">
        <div class="text-center mb-4">
            <img src="images/logo.jpg" alt="Company Logo" style="width: 150px; height: auto;">
        </div>
        <h3>Admin Login</h3>
        <form method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" id="username" class="form-control" placeholder="Enter your username">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password">
            </div>
            <button type="submit" name="login" class="btn btn-login w-100">Login</button>
        </form>
    </div>

    <!-- Link Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
