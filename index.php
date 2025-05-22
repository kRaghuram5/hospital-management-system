<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hospital Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">

<div class="container text-center mt-5">

    <!-- Logo and Title Side by Side -->
    <div class="d-flex justify-content-center align-items-center mb-4 gap-3 flex-wrap">
        <img src="assets/hms-logo.jpg" alt="HMS Logo" width="80">
        <h1 class="main-title m-0">Hospital Management System</h1>
    </div>

    <!-- Doctor Illustration (larger) -->
    <div class="mb-5">
        <img src="assets/doctor-icon.png" alt="Doctor Icon" width="220" class="main-icon">
    </div>

    <!-- Role Buttons -->
    <div class="d-flex justify-content-center gap-4 flex-wrap">
        <a href="login.php?role=admin" class="btn btn-admin btn-lg">Admin Login</a>
        <a href="login.php?role=doctor" class="btn btn-doctor btn-lg">Doctor Login</a>
        <a href="login.php?role=patient" class="btn btn-patient btn-lg">Patient Login</a>
    </div>

</div>

</body>
</html>
