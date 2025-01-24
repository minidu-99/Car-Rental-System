<?php
session_start();

// Database connection
include('includes/dbcon.php');

// Handling the search query
$search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';

// Prepare the SQL statement to prevent SQL injection
$stmt = $conn->prepare("SELECT v.id AS vehicle_id, v.VehiclesTitle, v.PricePerDay, v.SeatingCapacity, v.ModelYear, v.FuelType, v.Vimage1, 
                        b.BrandName 
                        FROM tblvehicles v
                        JOIN tblbrands b ON v.VehiclesBrand = b.id
                        WHERE v.VehiclesTitle LIKE ? 
                        OR b.BrandName LIKE ? 
                        OR v.FuelType LIKE ?");

$search_term = "%" . $search_query . "%";  // Add the wildcards for LIKE
$stmt->bind_param("sss", $search_term, $search_term, $search_term);

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Fetch fuel types
$fuelQuery = "SELECT DISTINCT FuelType FROM tblvehicles";
$fuelResult = $conn->query($fuelQuery);

// Fetch car brands
$brandQuery = "SELECT id, BrandName FROM tblbrands";
$brandResult = $conn->query($brandQuery);

// Fetch seat options (distinct values from vehicles table)
$seatsQuery = "SELECT DISTINCT SeatingCapacity FROM tblvehicles";
$seatsResult = $conn->query($seatsQuery);

// Get the number of records
$total_records = $result->num_rows;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vihanga Auto | Car Listing</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>

<?php include('includes/header.php');?>

<header class="carlist-header position-relative">
    <div class="overlay"></div>
    <div class="header-content text-center text-light">
        <h1 class="fw-bold">Search Results for "<?php echo htmlspecialchars($search_query); ?>"</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="index.php" class="text-light text-decoration-underline">Search Results</a></li>
                <li> > </li>
                <li class="breadcrumb-item active text-light" aria-current="page">Search Results</li>
            </ol>
        </nav>
    </div>
</header>

<!-- LISTING CONTENT -->
<div class="container my-4">
    <div class="row">
        <!-- Filter Widget -->
        <div class="col-lg-3 col-md-4 mb-4 order-md-1 order-2">
        <div class="filter-widget bg-light">
            <h5>Filter Cars</h5>
            <form method="GET" action="filtercar.php"> <!-- Use GET to send values to the PHP page -->
                <div class="mb-3">
                    <label for="fuelType" class="form-label">Fuel Type</label>
                    <select name="fuelType" id="fuelType" class="form-select">
                        <option value="" selected>Choose Fuel Type</option>
                        <?php while ($row = $fuelResult->fetch_assoc()): ?>
                            <option value="<?= $row['FuelType'] ?>"><?= ucfirst($row['FuelType']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="carBrand" class="form-label">Brand</label>
                    <select name="carBrand" id="carBrand" class="form-select">
                        <option value="" selected>Choose Brand</option>
                        <?php while ($row = $brandResult->fetch_assoc()): ?>
                            <option value="<?= $row['id'] ?>"><?= ucfirst($row['BrandName']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="seats" class="form-label">Seats</label>
                    <select name="seats" id="seats" class="form-select">
                        <option value="" selected>Choose Seats</option>
                        <?php while ($row = $seatsResult->fetch_assoc()): ?>
                            <option value="<?= $row['SeatingCapacity'] ?>"><?= $row['SeatingCapacity'] ?> Seats</option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button class="btn btn-primary" type="submit">Apply Filters</button>
            </form>
        </div>
</div>

        <!-- Cars Section -->
        <div class="col-lg-9 col-md-8 order-md-2 order-1">
            <!-- Result Info -->
            <div class="result-info">
                <p>Showing <?php echo $total_records; ?> result(s) for "<?php echo htmlspecialchars($search_query); ?>"</p>
            </div>

            <!-- Car Cards Section -->
            <?php
            if ($total_records > 0) {
                // Loop through each car record and display in a card
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <div class="car-card">
                        <img src="admin/uploads/<?php echo htmlspecialchars($row['Vimage1']); ?>" alt="Car Image" class="car-img">
                        <div class="car-details">
                            <h5><?php echo htmlspecialchars($row['VehiclesTitle']); ?></h5>
                            <p><strong>Price per Day:</strong> $<?php echo htmlspecialchars($row['PricePerDay']); ?></p>
                            <div class="horizontal-details">
                                <span><i class="fas fa-users"></i> <?php echo htmlspecialchars($row['SeatingCapacity']); ?> Seats</span>
                                <span><i class="fas fa-calendar"></i> <?php echo htmlspecialchars($row['ModelYear']); ?></span>
                                <span><i class="fas fa-gas-pump"></i> <?php echo htmlspecialchars($row['FuelType']); ?></span>
                            </div>
                            <a href="cardetails.php?id=<?php echo $row['vehicle_id']; ?>" class="btn btn-danger">See Details</a>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p class='text-center'>No cars available matching your search criteria.</p>";
            }
            ?>
        </div>
    </div>
</div>

<?php include('includes/footer.php');?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>


