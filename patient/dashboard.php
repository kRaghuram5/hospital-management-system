<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'patient') {
    header("Location: ../login.php?role=patient");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patient Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark px-3">
        <span class="navbar-brand mb-0 h1">Patient Dashboard</span>
        <div class="d-flex">
            <span class="navbar-text text-white me-3">Welcome, <?= htmlspecialchars($_SESSION['username']) ?></span>
            <a href="../logout.php" class="btn btn-outline-light">Logout</a>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">My Profile</h5>
                        <p class="card-text">View personal information.</p>
                        <a href="profile.php" class="btn btn-light">Go</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Book Appointment</h5>
                        <p class="card-text">Schedule an appointment with a doctor.</p>
                        <a href="appointments.php" class="btn btn-light">Go</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">Medical Records</h5>
                        <p class="card-text">View or upload medical records.</p>
                        <a href="records.php" class="btn btn-light">Go</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
