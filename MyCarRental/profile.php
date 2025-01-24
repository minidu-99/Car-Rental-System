<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    // Redirect to login if not logged in
    header('Location: login.php');
    exit();
}

// Database connection
include('includes/dbcon.php'); 

// Fetch user details
$userName = $_SESSION['user_name'];
$query = "SELECT FullName, EmailId, ContactNo, dob, Address, City, Country FROM tblusers WHERE FullName = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $userName);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();

if (!$userData) {
    echo "Error fetching user data.";
    exit();
}

// Fetch bookings for the logged-in user
$userEmail = $_SESSION['email']; // Assuming email is stored in the session
$query = "
    SELECT 
        b.BookingNumber, b.FromDate, b.ToDate, b.Status, 
        v.VehiclesTitle, v.PricePerDay, v.Vimage1,
        DATEDIFF(b.ToDate, b.FromDate) AS TotalDays,
        (DATEDIFF(b.ToDate, b.FromDate) * v.PricePerDay) AS GrandTotal
    FROM 
        tblbooking b
    JOIN 
        tblvehicles v ON b.VehicleId = v.id
    WHERE 
        b.userEmail = ?
    ORDER BY 
        b.PostingDate DESC
";

$stmt = $conn->prepare($query);
// Bind the parameter
$stmt->bind_param("s", $userEmail); // 's' means the parameter is a string

// Execute the statement
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Fetch data as an associative array
$bookings = $result->fetch_all(MYSQLI_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vihanga Auto | Homepage</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>

<?php include('includes/header.php');?>

<header class="profile-header position-relative">
    <div class="overlay"></div>
    <div class="header-content text-center text-light">
        <h1 class="fw-bold">Profile</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="index.php" class="text-light text-decoration-underline">Home</a></li>
                <li> > </li>
                <li class="breadcrumb-item active text-light" aria-current="page">Profile</li>
            </ol>
        </nav>
    </div>
</header>


<div class="container mt-4">
    <div class="row">
        <!-- Sidebar Section -->
        <div class="col-lg-3 col-md-4 col-sm-12">
            <div class="sidebar">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="profile.php" class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" role="tab">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a href="updatepassword.php" class="nav-link" id="update-password-tab" data-bs-toggle="tab" data-bs-target="#update-password" role="tab">Update Password</a>
                    </li>
                    <li class="nav-item">
                        <a href="mybookings.php" class="nav-link" id="my-bookings-tab" data-bs-toggle="tab" data-bs-target="#my-bookings" role="tab">My Bookings</a>
                    </li>
                    <li class="nav-item">
                        <a href="logout.php" class="nav-link" >Sign Out</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Tab Content Section -->
        <div class="col-lg-9 col-md-8 col-sm-12">
            <div class="tab-content">
                <!-- Profile Tab -->
                <div class="tab-pane fade show active" id="profile" role="tabpanel">
                    <h4>Profile</h4>
                    <form id="profileForm">
                        <div class="mb-3">
                            <label for="fullName" class="form-label">Full Name</label>
                            <input type="text" name="fullName" class="form-control" id="fullName" value="<?php echo htmlspecialchars($userData['FullName']); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" id="email" value="<?php echo htmlspecialchars($userData['EmailId']); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control" id="phone" value="<?php echo htmlspecialchars($userData['ContactNo']); ?>">
                        </div>
                        
                        <!-- New Date of Birth Field -->
                        <div class="mb-3">
                            <label for="dob" class="form-label">Date of Birth</label>
                            <input type="date" name="dob" class="form-control" id="dob" value="<?php echo htmlspecialchars($userData['dob']); ?>">
                        </div>
                        
                        <!-- New Address Field -->
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea name="address" class="form-control" id="address" rows="3"><?php echo htmlspecialchars($userData['Address']); ?></textarea>
                        </div>
                        
                        <!-- New City Field -->
                        <div class="mb-3">
                            <label for="city" class="form-label">City</label>
                            <input type="text" name="city" class="form-control" id="city" value="<?php echo htmlspecialchars($userData['City']); ?>">
                        </div>
                        
                        <!-- New Country Field -->
                        <div class="mb-3">
                            <label for="country" class="form-label">Country</label>
                            <input type="text" name="country" class="form-control" id="country" value="<?php echo htmlspecialchars($userData['Country']); ?>">
                        </div>
                        
                        <button type="button" class="btn btn-primary" id="updateButton">Update</button>
                    </form>
                </div>


                <!-- Update Password Tab -->
                <div class="tab-pane fade" id="update-password" role="tabpanel">
                    <h4>Update Password</h4>
                    <form id="updatePasswordForm" action="update-password.php" method="POST">
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">New Password</label>
                            <input type="password" name="newPassword"class="form-control" id="newPassword" placeholder="Enter new password">
                        </div>
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Confirm Password</label>
                            <input type="password" name="confirmPassword" class="form-control" id="confirmPassword" placeholder="Confirm new password">
                        </div>
                        <button type="submit" class="btn btn-primary">Confirm</button>
                    </form>
                </div>

                <!-- My Bookings Tab -->
                <div class="tab-pane fade" id="my-bookings" role="tabpanel">
                <h4 class="mb-4">My Bookings</h4>

                <?php if (!empty($bookings)): ?>
                    <?php foreach ($bookings as $booking): ?>
                        <div class="card mb-4">
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <img src="admin/uploads/<?php echo htmlspecialchars($booking['Vimage1']); ?>" 
                                        class="img-fluid rounded-start" alt="<?php echo htmlspecialchars($booking['VehiclesTitle']); ?>">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($booking['VehiclesTitle']); ?></h5>
                                        <p class="card-text"><strong>Booking Number:</strong> <?php echo htmlspecialchars($booking['BookingNumber']); ?></p>
                                        <p class="card-text"><strong>From:</strong> <?php echo htmlspecialchars($booking['FromDate']); ?> 
                                        <strong>To:</strong> <?php echo htmlspecialchars($booking['ToDate']); ?></p>
                                        <p class="card-text">
                                            <strong>Status:</strong>
                                            <span class="badge 
                                                <?php echo ($booking['Status'] == 0) ? 'bg-warning text-dark' : 
                                                            (($booking['Status'] == 1) ? 'bg-success' : 'bg-danger'); ?>">
                                                <?php echo ($booking['Status'] == 0) ? 'Not Confirmed' : 
                                                            (($booking['Status'] == 1) ? 'Confirmed' : 'Cancelled'); ?>
                                            </span>
                                        </p>
                                        <p class="card-text"><strong>Price per Day:</strong> Rs.<?php echo htmlspecialchars($booking['PricePerDay']); ?></p>
                                        <p class="card-text"><strong>Total Days:</strong> <?php echo htmlspecialchars($booking['TotalDays']); ?></p>
                                        <p class="card-text"><strong>Grand Total:</strong> Rs.<?php echo htmlspecialchars($booking['GrandTotal']); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center">You have no bookings yet.</p>
                <?php endif; ?>
            </div>

            </div>
        </div>
    </div>
</div>





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

document.getElementById('updateButton').addEventListener('click', function () {
    if (confirm('Are you sure you want to update your details?')) {
        const formData = new FormData(document.getElementById('profileForm'));

        fetch('update-profile.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Profile updated successfully!');
                location.reload();
            } else {
                alert('Error updating profile: ' + data.error);
            }
        })
        .catch(error => console.error('Error:', error));
    }
});

// JavaScript to confirm password change
document.getElementById("updatePasswordForm").addEventListener("submit", function(event) {
        event.preventDefault(); // Prevent form submission until confirmed

        // Get values from input fields
        const newPassword = document.getElementById("newPassword").value;
        const confirmPassword = document.getElementById("confirmPassword").value;

        // Check if passwords match
        if (newPassword !== confirmPassword) {
            alert("Passwords do not match!");
            return; // Stop the process if passwords don't match
        }

        // Ask the user to confirm the password change
        const confirmChange = confirm("Are you sure you want to change your password?");

        // If confirmed, submit the form using AJAX
        if (confirmChange) {
            const formData = new FormData(this); // Form data to send via AJAX

            fetch('update-password.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message); // Show success message
                    location.reload(); // Reload the page
                } else {
                    alert(data.error); // Show error message
                }
            })
            .catch(error => {
                alert('An error occurred. Please try again later.');
            });
        }
    }); 



</script>
</body>
</html>
