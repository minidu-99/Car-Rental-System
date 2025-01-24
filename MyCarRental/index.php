<?php
session_start();

include('includes/dbcon.php');
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

<?php include('includes/header.php'); ?>

<!-- Hero Section -->
<section class="hero-section" style="background-image: url('assets/images/heroBanner.jpg'); background-size: cover; background-position: center; height: 400px;">
    <div class="hero-content text-center text-white d-flex justify-content-center align-items-center h-100">
        <div>
            <h1 class="display-4">Welcome to Vihanga Car Rental Service</h1>
            <p class="lead">Explore the best cars at affordable prices. Your adventure begins here!</p>
            <a href="carlist.php" class="btn btn-danger btn-lg">Get Started</a>
        </div>
    </div>
</section>

<!-- Find the Best CarForYou Section -->
<section class="find-car-section py-5">
    <div class="container text-center">
        <h2 class="mb-4">Find the Best CarForYou</h2>
        <p>
            There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form,
            by injected humour, or randomised words which don't look even slightly believable.
        </p>
    </div>
</section>

<!-- Product Row -->
<section class="product-row py-5">
    <div class="container">
        <div class="row">
            <?php
            // Fetch data from tblvehicles
            $sql = "SELECT id, VehiclesTitle, VehiclesBrand, PricePerDay, FuelType, ModelYear, SeatingCapacity, Vimage1, VehiclesOverview FROM tblvehicles limit 9";
            $result = $conn->query($sql);

            if ($result === false) {
                echo "<p>Error fetching data: " . $conn->error . "</p>";
            } elseif ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                        <a href="cardetails.php?id=<?php echo $row['id']; ?>" class="card-link">
                            <div class="card">
                                <div class="card-image position-relative">
                                    <img src="admin/uploads/<?php echo htmlspecialchars($row['Vimage1']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['VehiclesTitle']); ?>">
                                    <div class="overlay">
                                        <div class="detail">
                                            <div><i class="fas fa-gas-pump"></i> <?php echo htmlspecialchars($row['FuelType']); ?></div>
                                            <div><i class="fas fa-calendar"></i> <?php echo htmlspecialchars($row['ModelYear']); ?></div>
                                            <div><i class="fas fa-users"></i> <?php echo htmlspecialchars($row['SeatingCapacity']); ?> Seats</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($row['VehiclesTitle']); ?></h5>
                                    <p class="card-text">
                                        <?php 
                                        $overview = htmlspecialchars($row['VehiclesOverview']);
                                        $truncatedOverview = substr($overview, 0, 100); // Truncate to 100 characters
                                        echo $truncatedOverview . (strlen($overview) > 100 ? '...' : ''); // Add ellipsis if text is longer
                                        ?>
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php
                }
            } else {
                echo "<p class='text-center'>No vehicles available at the moment.</p>";
            }

            $conn->close();
            ?>
        </div>
    </div>
</section>

<?php include('includes/footer.php'); ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.getElementById('showSignupForm').addEventListener('click', function (e) {
    e.preventDefault();
    document.getElementById('loginFormBody').classList.add('d-none');
    document.getElementById('signupFormBody').classList.remove('d-none');
    document.getElementById('authModalLabel').textContent = 'Sign Up';
});

document.getElementById('showLoginForm').addEventListener('click', function (e) {
    e.preventDefault();
    document.getElementById('signupFormBody').classList.add('d-none');
    document.getElementById('loginFormBody').classList.remove('d-none');
    document.getElementById('authModalLabel').textContent = 'Login';
});

</script>



</body>
</html>
