<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'patient') {
    header("Location: ../login.php?role=patient");
    exit();
}

include '../includes/db_config.php';
$userId = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM patients WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$patient = $result->fetch_assoc();

if (!$patient) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Profile not found.</div></div>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Patient Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3 class="mb-4">My Profile</h3>
    <table class="table table-bordered">
        <tr><th>Name</th><td><?= htmlspecialchars($patient['name']) ?></td></tr>
        <tr><th>Gender</th><td><?= htmlspecialchars($patient['gender']) ?></td></tr>
        <tr><th>DOB</th><td><?= htmlspecialchars($patient['dob']) ?></td></tr>
        <tr><th>Contact</th><td><?= htmlspecialchars($patient['contact']) ?></td></tr>
        <tr><th>Address</th><td><?= htmlspecialchars($patient['address']) ?></td></tr>
    </table>
    <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>
</body>
</html>
