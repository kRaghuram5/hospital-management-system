<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'patient') {
    header("Location: ../login.php?role=patient");
    exit();
}

include '../includes/db_config.php';
$patient_id = $_SESSION['user_id'];

// Handle new record
if (isset($_POST['add'])) {
    $note = $_POST['note'];
    $stmt = $conn->prepare("INSERT INTO medical_records (patient_id, note, created_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("is", $patient_id, $note);
    $stmt->execute();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Medical Records</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3 class="mb-4">Medical Records</h3>
    <form method="POST" class="mb-4">
        <textarea name="note" class="form-control mb-2" placeholder="Enter medical note..." required></textarea>
        <button type="submit" name="add" class="btn btn-primary">Add Record</button>
    </form>

    <h5>Record History</h5>
    <ul class="list-group">
        <?php
        $records = $conn->query("SELECT * FROM medical_records WHERE patient_id = $patient_id ORDER BY created_at DESC");
        while ($row = $records->fetch_assoc()) {
            echo "<li class='list-group-item'><strong>{$row['created_at']}:</strong> " . htmlspecialchars($row['note']) . "</li>";
        }
        ?>
    </ul>
    <a href="dashboard.php" class="btn btn-secondary mt-4">Back to Dashboard</a>
</div>
</body>
</html>
