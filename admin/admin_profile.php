<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php?role=admin");
    exit;
}

include '../includes/db_config.php';

$user_id = $_SESSION['user_id'];

// Fetch admin details
$stmt = $conn->prepare("SELECT ap.name, ap.email, ap.contact, ap.address, ap.admin_code 
                        FROM admin_profiles ap
                        JOIN users u ON ap.user_id = u.id 
                        WHERE u.id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3 class="mb-4">My Profile</h3>
    <?php if ($admin): ?>
        <table class="table table-bordered">
            <tr>
                <th>Full Name</th>
                <td><?= htmlspecialchars($admin['name']) ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?= htmlspecialchars($admin['email']) ?></td>
            </tr>
            <tr>
                <th>Contact</th>
                <td><?= htmlspecialchars($admin['contact']) ?></td>
            </tr>
            <tr>
                <th>Address</th>
                <td><?= htmlspecialchars($admin['address']) ?></td>
            </tr>
            <tr>
                <th>Admin Code</th>
                <td><?= htmlspecialchars($admin['admin_code']) ?></td>
            </tr>
        </table>
    <?php else: ?>
        <div class="alert alert-danger">No profile details found.</div>
    <?php endif; ?>
    <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>
</body>
</html>
