<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header("Location: ../login.php?role=patient");
    exit;
}
include '../includes/db_config.php';

// Fetch actual patient ID using the logged-in user_id
$user_id = $_SESSION['user_id'];
$patientStmt = $conn->prepare("SELECT id FROM patients WHERE user_id = ?");
$patientStmt->bind_param("i", $user_id);
$patientStmt->execute();
$patientResult = $patientStmt->get_result();
$patientData = $patientResult->fetch_assoc();

if (!$patientData) {
    echo "<div class='alert alert-danger'>Patient record not found.</div>";
    exit;
}
$patient_id = $patientData['id'];

// Booking logic
if (isset($_POST['book'])) {
    $doctor_id = $_POST['doctor_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];

    $now = date('Y-m-d H:i:s');
    $selectedDateTime = date('Y-m-d H:i:s', strtotime("$appointment_date $appointment_time"));

    // Validate: must be future date/time
    if ($selectedDateTime <= $now) {
        $error = "Appointment must be booked for a future date and time.";
    } else {
        // Check for overlapping appointment for same doctor within the same hour
        $checkStmt = $conn->prepare("
            SELECT * FROM appointments 
            WHERE doctor_id = ? AND appointment_date = ? 
              AND HOUR(appointment_time) = HOUR(?) 
        ");
        $checkStmt->bind_param("iss", $doctor_id, $appointment_date, $appointment_time);
        $checkStmt->execute();
        $conflict = $checkStmt->get_result()->num_rows > 0;

        if ($conflict) {
            $error = "This doctor already has an appointment in the selected hour.";
        } else {
            // All good, insert appointment
            $insertStmt = $conn->prepare("INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, status) VALUES (?, ?, ?, ?, 'pending')");
            $insertStmt->bind_param("iiss", $patient_id, $doctor_id, $appointment_date, $appointment_time);
            $insertStmt->execute();
            $success = "Appointment booked successfully!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Appointment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3>Book Appointment</h3>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php elseif (isset($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <!-- Appointment Booking Form -->
    <form method="POST" class="row g-3 mb-4">
        <div class="col-md-4">
            <label>Select Doctor</label>
            <select name="doctor_id" required class="form-control">
                <option value="">Choose</option>
                <?php
                $doctors = $conn->query("SELECT d.id, d.name, s.name AS specialization FROM doctors d LEFT JOIN specializations s ON d.specialization_id = s.id");
                while ($doc = $doctors->fetch_assoc()) {
                    echo "<option value='{$doc['id']}'>{$doc['name']} - {$doc['specialization']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-3">
            <label>Date</label>
            <input type="date" name="appointment_date" required class="form-control" min="<?= date('Y-m-d') ?>">
        </div>
        <div class="col-md-3">
            <label>Time</label>
            <input type="time" name="appointment_time" required class="form-control">
        </div>
        <div class="col-md-2 align-self-end">
            <button type="submit" name="book" class="btn btn-primary w-100">Book</button>
        </div>
    </form>

    <h5>My Appointments</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Doctor</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $apptQuery = $conn->prepare("
                SELECT a.*, d.name AS doctor_name 
                FROM appointments a 
                JOIN doctors d ON a.doctor_id = d.id 
                WHERE a.patient_id = ? 
                ORDER BY a.appointment_date DESC, a.appointment_time DESC
            ");
            $apptQuery->bind_param("i", $patient_id);
            $apptQuery->execute();
            $result = $apptQuery->get_result();

            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['doctor_name']}</td>
                        <td>{$row['appointment_date']}</td>
                        <td>{$row['appointment_time']}</td>
                        <td>{$row['status']}</td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>

    <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>
</body>
</html>
