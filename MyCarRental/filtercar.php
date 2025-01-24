<?php
session_start();

// Database connection
include('includes/dbcon.php');

// Handling filters
$fuelType = isset($_GET['fuelType']) ? $_GET['fuelType'] : '';
$carBrand = isset($_GET['carBrand']) ? $_GET['carBrand'] : '';
$seats = isset($_GET['seats']) ? $_GET['seats'] : '';
$search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';

// Base SQL query
$sql = "SELECT v.id AS vehicle_id, v.VehiclesTitle, v.PricePerDay, v.SeatingCapacity, 
               v.ModelYear, v.FuelType, v.Vimage1, b.BrandName 
        FROM tblvehicles v
        JOIN tblbrands b ON v.VehiclesBrand = b.id
        WHERE 1=1";


// Add filters
$filters = [];
if ($fuelType !== '') {
    $sql .= " AND v.FuelType = ?";
    $filters[] = $fuelType;
}
if ($carBrand !== '') {
    $sql .= " AND b.id = ?";
    $filters[] = $carBrand;
}
if ($seats !== '') {
    $sql .= " AND v.SeatingCapacity = ?";
    $filters[] = $seats;
}
if ($search_query !== '') {
    $sql .= " AND (v.VehiclesTitle LIKE ? OR b.BrandName LIKE ? OR v.FuelType LIKE ?)";
    $search_term = '%' . $search_query . '%';
    $filters[] = $search_term;
    $filters[] = $search_term;
    $filters[] = $search_term;
}

// Prepare and execute the SQL query
$stmt = $conn->prepare($sql);
if ($filters) {
    $bindTypes = str_repeat('s', count($filters));
    $stmt->bind_param($bindTypes, ...$filters);
}
$stmt->execute();
$result = $stmt->get_result();
$total_records = $result->num_rows;

// Fetch distinct filter values
$fuelResult = $conn->query("SELECT DISTINCT FuelType FROM tblvehicles");
$brandResult = $conn->query("SELECT id, BrandName FROM tblbrands");
$seatsResult = $conn->query("SELECT DISTINCT SeatingCapacity FROM tblvehicles");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vihanga Auto | Car Listing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>

<?php include('includes/header.php'); ?>

<header class="carlist-header position-relative">
    <div class="overlay"></div>
    <div class="header-content text-center text-light">
        <h1 class="fw-bold">Car Listing</h1>
        <p>Showing <?php echo $total_records; ?> result(s) for "<?php echo htmlspecialchars($search_query); ?>"</p>
    </div>
</header>

<div class="container my-4">
    <div class="row">
        <!-- Filter Widget -->
        <div class="col-lg-3 col-md-4 mb-4">
            <div class="filter-widget bg-light p-3">
                <h5>Filter Cars</h5>
                <form method="GET" action="">
                    <div class="mb-3">
                        <label for="fuelType" class="form-label">Fuel Type</label>
                        <select name="fuelType" id="fuelType" class="form-select">
                            <option value="">All Fuel Types</option>
                            <?php while ($row = $fuelResult->fetch_assoc()): ?>
                                <option value="<?= $row['FuelType'] ?>" <?= $row['FuelType'] == $fuelType ? 'selected' : '' ?>>
                                    <?= ucfirst($row['FuelType']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="carBrand" class="form-label">Brand</label>
                        <select name="carBrand" id="carBrand" class="form-select">
                            <option value="">All Brands</option>
                            <?php while ($row = $brandResult->fetch_assoc()): ?>
                                <option value="<?= $row['id'] ?>" <?= $row['id'] == $carBrand ? 'selected' : '' ?>>
                                    <?= ucfirst($row['BrandName']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="seats" class="form-label">Seats</label>
                        <select name="seats" id="seats" class="form-select">
                            <option value="">All Seat Capacities</option>
                            <?php while ($row = $seatsResult->fetch_assoc()): ?>
                                <option value="<?= $row['SeatingCapacity'] ?>" <?= $row['SeatingCapacity'] == $seats ? 'selected' : '' ?>>
                                    <?= $row['SeatingCapacity'] ?> Seats
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </form>
            </div>
        </div>

        <!-- Cars Section -->
        <div class="col-lg-9 col-md-8">
            <div class="result-info mb-3">
                <p>Showing <?php echo $total_records; ?> result(s) for "<?php echo htmlspecialchars($search_query); ?>"</p>
            </div>

            <!-- Display Cars -->
            <?php
            if ($total_records > 0) {
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <div class="car-card mb-4 d-flex">
                        <img src="admin/uploads/<?php echo htmlspecialchars($row['Vimage1']); ?>" alt="Car Image" class="car-img me-3">
                        <div class="car-details">
                            <h5><?php echo htmlspecialchars($row['VehiclesTitle']); ?></h5>
                            <p><strong>Price per Day:</strong> $<?php echo htmlspecialchars($row['PricePerDay']); ?></p>
                            <div class="horizontal-details">
                                <span><i class="fas fa-users"></i> <?php echo htmlspecialchars($row['SeatingCapacity']); ?> Seats</span>
                                <span><i class="fas fa-calendar"></i> <?php echo htmlspecialchars($row['ModelYear']); ?></span>
                                <span><i class="fas fa-gas-pump"></i> <?php echo htmlspecialchars($row['FuelType']); ?></span>
                            </div>
                            <a href="cardetails.php?id=<?php echo $row['vehicle_id']; ?>" class="btn btn-danger mt-2">See Details</a>
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

<?php include('includes/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
