<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'doctor') {
    header("Location: ../login.php?role=doctor");
    exit();
}

include '../includes/db_config.php';

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Get doctor ID from the doctors table
$stmt = $conn->prepare("SELECT id FROM doctors WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$docResult = $stmt->get_result();
$doc = $docResult->fetch_assoc();

$patients = [];

if ($doc) {
    $doctorId = $doc['id'];

    // Fetch confirmed patients from appointments
    $stmt = $conn->prepare("SELECT DISTINCT p.id, p.name, p.dob, p.contact
                            FROM appointments a
                            JOIN patients p ON a.patient_id = p.id
                            WHERE a.doctor_id = ? AND a.status = 'confirmed'");
    $stmt->bind_param("i", $doctorId);
    $stmt->execute();
    $patients = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Patients Assigned to You</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3 class="mb-4">Patients Assigned to You (Confirmed)</h3>
    <?php if ($patients && $patients->num_rows > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Patient ID</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Contact</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($p = $patients->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($p['id']) ?></td>
                    <td><?= htmlspecialchars($p['name']) ?></td>
                    <td>
                        <?php
                        $dob = new DateTime($p['dob']);
                        $today = new DateTime();
                        echo $today->diff($dob)->y;
                        ?>
                    </td>
                    <td><?= htmlspecialchars($p['contact']) ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">No confirmed patients assigned.</div>
    <?php endif; ?>
    <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>
</body>
</html>
