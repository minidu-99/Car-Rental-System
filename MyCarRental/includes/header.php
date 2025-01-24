<!-- Header -->


<!-- Header Top -->
<div class="header-top d-flex justify-content-between align-items-center px-4 flex-wrap">
    <div class="company-logo mx-auto mx-lg-0 text-center">
        <img src="images/logo.jpg" alt="company-logo" class="img-fluid">
    </div>
    <div class="contact-info d-none d-lg-block">
        <span>Email: info@vihangaauto.com | Phone: +94 712 345 678</span>
    </div>

    <?php if (!isset($_SESSION['user_name'])): ?>
    <?php endif; ?>
</div>


<!-- Modal -->
<div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="authModalLabel">Login</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body: Login Form -->
            <div class="modal-body" id="loginFormBody">
                <form id="loginForm" action="login.php" method="POST">
                    <div class="mb-3">
                        <label for="loginEmail" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" id="loginEmail" placeholder="Enter your email" required>
                    </div>
                    <div class="mb-3">
                        <label for="loginPassword" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" id="loginPassword" placeholder="Enter your password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
                <p class="text-center mt-3">
                    Don't have an account? 
                    <a href="#" id="showSignupForm">Sign Up</a>
                </p>
            </div>

            <!-- Modal Body: Sign Up Form -->
            <div class="modal-body d-none" id="signupFormBody">
                <form id="signupForm" action="register.php" method="POST">
                    <div class="mb-3">
                        <label for="signupFullName" class="form-label">User Name</label>
                        <input type="text" name="fullName" class="form-control" id="signupFullName" placeholder="Enter your full name" required>
                    </div>
                    <div class="mb-3">
                        <label for="signupEmail" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" id="signupEmail" placeholder="Enter your email" required>
                    </div>
                    <div class="mb-3">
                        <label for="signupPhone" class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="form-control" id="signupPhone" placeholder="Enter your phone number" required>
                    </div>
                    <div class="mb-3">
                        <label for="signupPassword" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" id="signupPassword" placeholder="Enter your password" required>
                    </div>
                    <div class="mb-3">
                        <label for="signupConfirmPassword" class="form-label">Confirm Password</label>
                        <input type="password" name="password" class="form-control" id="signupConfirmPassword" placeholder="Confirm your password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Sign Up</button>
                </form>
                <p class="text-center mt-3">
                    Already have an account? 
                    <a href="#" id="showLoginForm">Login</a>
                </p>
            </div>
        </div>
    </div>
</div>



<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon" style="background-color: #d9534f;"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="aboutus.php">About Us</a></li>
                <li class="nav-item"><a class="nav-link" href="carlist.php">Car Listings</a></li>
                <li class="nav-item"><a class="nav-link" href="contactUs.php">Contact Us</a></li>
            </ul>
        </div>
       <!-- Search Bar Form -->
                <form class="d-flex search-bar me-3" method="GET" action="searchcar.php">
                    <input class="form-control me-2" type="search" name="search_query" placeholder="Search Cars" aria-label="Search" value="<?php echo isset($_GET['search_query']) ? htmlspecialchars($_GET['search_query']) : ''; ?>">
                    <button class="btn" type="submit">Search</button>
                </form>


        <?php if (isset($_SESSION['user_name'])): ?>
        <!-- Dropdown for Logged-in Users -->
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                <?php echo htmlspecialchars($_SESSION['user_name']); ?>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                <li><a class="dropdown-item" href="profile.php">Profile Settings</a></li>
                <li><a class="dropdown-item" href="logout.php">Sign Out</a></li>
            </ul>
        </div>
        <?php else: ?>
        <!-- Login/Register Button for Guests -->
        <button class="btn btn-secondary login-register" type="button" id="userMenu" data-bs-toggle="modal" data-bs-target="#authModal" aria-expanded="false">
                Login | Register
            </button>
        <?php endif; ?>
    </div>
</nav>


<!-- <button class="btn btn-secondary " type="button" data-bs-toggle="modal"  data-bs-target="#authModal">Login/Register</button> -->
