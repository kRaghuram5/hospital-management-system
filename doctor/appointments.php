<?php
session_start();
include '../includes/db_config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'doctor') {
    header("Location: ../login.php?role=doctor");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get doctor ID from doctors table
$stmt = $conn->prepare("SELECT id FROM doctors WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$doctor = $stmt->get_result()->fetch_assoc();
$doctor_id = $doctor['id'] ?? 0;

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appointment_id'], $_POST['new_status'])) {
    $appointment_id = (int)$_POST['appointment_id'];
    $new_status = $_POST['new_status'];

    if (in_array($new_status, ['confirmed', 'rejected'])) {
        $update = $conn->prepare("UPDATE appointments SET status = ? WHERE id = ? AND doctor_id = ?");
        $update->bind_param("sii", $new_status, $appointment_id, $doctor_id);
        $update->execute();
    }
}

// Fetch appointments
$query = "SELECT a.id, p.name AS patient_name, a.appointment_date, a.appointment_time, a.status 
          FROM appointments a 
          JOIN patients p ON a.patient_id = p.id 
          WHERE a.doctor_id = ? 
          ORDER BY a.appointment_date, a.appointment_time";

$stmt2 = $conn->prepare($query);
$stmt2->bind_param("i", $doctor_id);
$stmt2->execute();
$appointments = $stmt2->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Appointments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>My Appointments</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Patient</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $appointments->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['patient_name']) ?></td>
                <td><?= htmlspecialchars($row['appointment_date']) ?></td>
                <td><?= htmlspecialchars($row['appointment_time']) ?></td>
                <td>
                    <?php if ($row['status'] === 'pending'): ?>
                        <form method="POST" style="margin: 0;">
                            <input type="hidden" name="appointment_id" value="<?= $row['id'] ?>">
                            <select name="new_status" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option disabled selected>Pending</option>
                                <option value="confirmed">Confirm</option>
                                <option value="rejected">Reject</option>
                            </select>
                        </form>
                    <?php else: ?>
                        <?= ucfirst($row['status']) ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <a href="dashboard.php" class="btn btn-secondary">Back</a>
</div>
</body>
</html>
