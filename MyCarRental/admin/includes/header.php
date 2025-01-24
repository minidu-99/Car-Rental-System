<!-- Header -->
<!-- Header -->
<div class="header d-flex justify-content-between align-items-center">
    <h1>Vihanga Auto | Admin Panel</h1>
    <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?php echo htmlspecialchars($adminUsername); ?>
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="change_password.php">Change Password</a></li>
            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
        </ul>
    </div>

    
</div>
