<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php?role=admin");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/style.css">

    <!-- Custom JS -->
    <script src="../js/script.js" defer></script>
</head>
<body>
<!-- Navbar with Logout -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="navbar-brand">Admin Dashboard</span>
        <div class="d-flex">
            <a href="../logout.php" class="btn btn-outline-light">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="mb-4 text-center">Welcome, Admin</h2>
    <div class="row row-cols-1 row-cols-md-2 g-4">

        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Admin Profile</h5>
                    <p class="card-text">View your admin profile and details.</p>
                    <a href="admin_profile.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Manage Users</h5>
                    <p class="card-text">View and control all user accounts.</p>
                    <a href="manage_users.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Manage Doctors</h5>
                    <p class="card-text">Add, edit, or remove doctors and their details.</p>
                    <a href="manage_doctors.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Manage Patients</h5>
                    <p class="card-text">Add, view, or edit patient records.</p>
                    <a href="manage_patients.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Manage Appointments</h5>
                    <p class="card-text">View and track all scheduled appointments.</p>
                    <a href="appointments.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Specializations</h5>
                    <p class="card-text">Add or edit doctor specializations.</p>
                    <a href="add_specialization.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>

    </div>
</div>
</body>
</html>
