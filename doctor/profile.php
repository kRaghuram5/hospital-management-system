<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'doctor') {
    header("Location: ../login.php?role=doctor");
    exit();
}

include '../includes/db_config.php';

$username = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM doctors WHERE user_id = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$doctor = $result->fetch_assoc();

if (!$doctor) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Profile not found for the logged-in doctor.</div></div>";
    exit();
}

// 🩺 Fetch specialization name using specialization_id
$specializationName = 'Not Assigned';
if (!empty($doctor['specialization_id'])) {
    $specStmt = $conn->prepare("SELECT name FROM specializations WHERE id = ?");
    $specStmt->bind_param("i", $doctor['specialization_id']);
    $specStmt->execute();
    $specResult = $specStmt->get_result();
    if ($specRow = $specResult->fetch_assoc()) {
        $specializationName = $specRow['name'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h3 class="mb-4">My Profile</h3>
        <table class="table table-bordered">
            <tr><th>Name</th><td><?= htmlspecialchars($doctor['name']) ?></td></tr>
            <tr><th>Contact</th><td><?= htmlspecialchars($doctor['contact']) ?></td></tr>
            <tr><th>Specialization</th><td><?= htmlspecialchars($specializationName) ?></td></tr>
            <tr><th>Experience</th><td><?= htmlspecialchars($doctor['experience']) ?> years</td></tr>
        </table>
        <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</body>
</html>
