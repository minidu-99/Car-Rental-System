<?php
include('includes/dbcon.php');
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vihanga Auto | About us</title>
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
<header class="about-header position-relative">
    <div class="overlay"></div>
    <div class="header-content text-center text-light">
        <h1 class="fw-bold">About Us</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="index.php" class="text-light text-decoration-underline">Home</a></li>
                <li> > </li>
                <li class="breadcrumb-item active text-light" aria-current="page">About Us</li>
            </ol>
        </nav>
    </div>
</header>

<!-- About Us Content -->
<section class="find-car-section py-5">
    <div class="container text-center">
    <h2 class="fw-bold mb-4">Who We Are</h2>
    <p class="text-muted">
        Welcome to Vihanga Auto, your trusted partner in car rentals for over 15 years. 
        We are proud to be a leading provider of exceptional car rental services, 
        offering tailored solutions to meet your travel needs.
    </p>
    
    <p class="text-muted">
    With a legacy of reliability and excellence, 
    Vihanga Auto boasts a diverse fleet of well-maintained vehicles, 
    competitive pricing, and a customer-first approach. 
    Whether you need a car for a business trip, a family vacation, 
    or a spontaneous road trip, we ensure your journey is safe, comfortable, and hassle-free.

    </p>
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



</script>
</body>
</html>