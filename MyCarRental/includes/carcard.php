<div class="car-card">
                <img src="admin/uploads/<?php echo htmlspecialchars($row['Vimage1']); ?>" alt="Car Image" class="car-img">
                <div class="car-details">
                    <h5><?php echo htmlspecialchars($row['VehiclesTitle']); ?></h5>
                    <p><strong>Price per Day:</strong>Rs.<?php echo htmlspecialchars($row['PricePerDay']); ?></p>
                    <div class="horizontal-details">
                        <span><i class="fas fa-users"></i><?php echo htmlspecialchars($row['SeatingCapacity']); ?></span>
                        <span><i class="fas fa-calendar"></i></i> <?php echo htmlspecialchars($row['ModelYear']); ?></span>
                        <span><i class="fas fa-gas-pump"></i><?php echo htmlspecialchars($row['FuelType']); ?></span>
                    </div>
                    <a href="cardetails.php?id=<?php echo $row['id']; ?>" class="btn btn-danger">See Details</a>
                </div>
            </div>



            <div class="col-lg-9 col-md-8 order-md-2 order-1">
            <div class="row">
                <?php
                // Fetch all cars from the tblvehicles table
                $query = "SELECT * FROM tblvehicles";
                $result = $conn->query($query);

                // Check if any cars are available
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        ?>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="car-card">
                                <img src="admin/uploads/<?php echo htmlspecialchars($row['Vimage1']); ?>" alt="Car Image" class="car-img">
                                <div class="car-details">
                                    <h5><?php echo htmlspecialchars($row['VehiclesTitle']); ?></h5>
                                    <p><strong>Price per Day:</strong> Rs.<?php echo htmlspecialchars($row['PricePerDay']); ?></p>
                                    <div class="horizontal-details">
                                        <span><i class="fas fa-users"></i> <?php echo htmlspecialchars($row['SeatingCapacity']); ?> Seats</span>
                                        <span><i class="fas fa-calendar"></i> <?php echo htmlspecialchars($row['ModelYear']); ?></span>
                                        <span><i class="fas fa-gas-pump"></i> <?php echo htmlspecialchars($row['FuelType']); ?></span>
                                    </div>
                                    <a href="cardetails.php?id=<?php echo $row['id']; ?>" class="btn btn-danger">See Details</a>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p class='text-center'>No cars available at the moment.</p>";
                }
                ?>
            </div>