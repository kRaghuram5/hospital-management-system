<?php
session_start();
include '../includes/db_config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
    header("Location: ../login.php?role=doctor");
    exit;
}

$doctor_id = $conn->query("SELECT id FROM doctors WHERE user_id = {$_SESSION['user_id']}")->fetch_assoc()['id'];

if (isset($_POST['submit'])) {
    $patient_id = $_POST['patient_id'];
    $appointment_id = $_POST['appointment_id'];
    $diagnosis = $_POST['diagnosis'];
    $prescription = $_POST['prescription'];
    $date = date('Y-m-d');

    $stmt = $conn->prepare("INSERT INTO medical_records (patient_id, doctor_id, appointment_id, diagnosis, prescription, date) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiisss", $patient_id, $doctor_id, $appointment_id, $diagnosis, $prescription, $date);
    $stmt->execute();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Medical Record</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3>Add Medical Record</h3>
    <form method="POST">
        <div class="mb-3">
            <input type="number" name="patient_id" placeholder="Patient ID" required class="form-control">
        </div>
        <div class="mb-3">
            <input type="number" name="appointment_id" placeholder="Appointment ID" required class="form-control">
        </div>
        <div class="mb-3">
            <textarea name="diagnosis" class="form-control" placeholder="Diagnosis" required></textarea>
        </div>
        <div class="mb-3">
            <textarea name="prescription" class="form-control" placeholder="Prescription" required></textarea>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Add Record</button>
        <a href="dashboard.php" class="btn btn-secondary">Back</a>
    </form>
</div>
</body>
</html>
