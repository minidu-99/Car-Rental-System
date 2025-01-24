<?php
include('includes/dbcon.php'); // Ensure dbcon.php connects to your database
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contactNumber = $_POST['phone'];
    $message = $_POST['message'];
    $postingDate = date('Y-m-d H:i:s'); // Current date and time
    $status = 0; // Default status

    // Insert the data into the table
    $query = "INSERT INTO tblcontactusquery (name, EmailId, ContactNumber, Message, PostingDate, status) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssi", $name, $email, $contactNumber, $message, $postingDate, $status);

    if ($stmt->execute()) {
        echo "<script>alert('Message sent successfully!');</script>";
    } else {
        echo "<script>alert('Error occurred while sending the message.');</script>";
    }
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vihanga Auto | Contact Us</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
<?php include('includes/header.php');?>

<!-- About Us Header -->
<header class="contact-header position-relative">
    <div class="overlay"></div>
    <div class="header-content text-center text-light">
        <h1 class="fw-bold">Contact us</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="index.php" class="text-light text-decoration-underline">Home</a></li>
                <li> > </li>
                <li class="breadcrumb-item active text-light" aria-current="page">Contact Us</li>
            </ol>
        </nav>
    </div>
</header>

<!-- Contact Us Section -->
<section class="contact-us-section py-5">
    <div class="container">
        <div class="row">
            <!-- Contact Form -->
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h2 class="fw-bold mb-4">Get in Touch</h2>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" id="name" placeholder="Enter your full name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" id="email" placeholder="Enter your email" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" name="phone" class="form-control" id="phone" placeholder="Enter your phone number" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea name="message" class="form-control" id="message" rows="4" placeholder="Enter your message" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary px-4 py-2">Send Message</button>
                </form>
            </div>
            <!-- Company Information -->
            <div class="col-lg-6">
                <h2 class="fw-bold mb-4">Contact Information</h2>
                <p class="text-muted">
                    <strong>Vihanga Auto</strong><br>
                    123 Main Street, Colombo 07, Sri Lanka<br>
                    <strong>Phone:</strong> +94 712 345 678<br>
                    <strong>Email:</strong> info@vihangaauto.com
                </p>
                <div class="mt-4">
                    <h5>Follow Us:</h5>
                    <ul class="list-unstyled d-flex">
                        <li class="me-3">
                            <a href="#" class="text-dark"><i class="fab fa-facebook-f"></i></a>
                        </li>
                        <li class="me-3">
                            <a href="#" class="text-dark"><i class="fab fa-instagram"></i></a>
                        </li>
                        <li class="me-3">
                            <a href="#" class="text-dark"><i class="fab fa-twitter"></i></a>
                        </li>
                        <li>
                            <a href="#" class="text-dark"><i class="fab fa-linkedin-in"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include('includes/footer.php');?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.getElementById("showSignupForm").addEventListener("click", function () {
    document.getElementById("loginForm").parentElement.classList.add("d-none");
    document.getElementById("signupFormBody").classList.remove("d-none");
    document.getElementById("authModalLabel").textContent = "Sign Up";
});

document.getElementById("showLoginForm").addEventListener("click", function () {
    document.getElementById("signupFormBody").classList.add("d-none");
    document.getElementById("loginForm").parentElement.classList.remove("d-none");
    document.getElementById("authModalLabel").textContent = "Login";
});

</body>
</html>
