<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'doctor') {
    header("Location: ../login.php?role=doctor");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark px-3">
        <span class="navbar-brand mb-0 h1">Doctor Dashboard</span>
        <div class="d-flex">
            <span class="navbar-text text-white me-3">
                Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>
            </span>
            <a href="../logout.php" class="btn btn-outline-light">Logout</a>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row g-4">

            <div class="col-md-4">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">My Profile</h5>
                        <p class="card-text">View and update your profile information.</p>
                        <a href="profile.php" class="btn btn-light">Go</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">My Appointments</h5>
                        <p class="card-text">Check your upcoming appointments.</p>
                        <a href="appointments.php" class="btn btn-light">Go</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h5 class="card-title">Patients List</h5>
                        <p class="card-text">See your assigned patients.</p>
                        <a href="patients.php" class="btn btn-light">Go</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>
</html>
