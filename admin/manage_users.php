<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php?role=admin");
    exit;
}
include '../includes/db_config.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2>All Users</h2>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Username</th>
                <th>Role</th>
                <th>Created</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $users = $conn->query("SELECT * FROM users");
        $i = 1;
        while ($user = $users->fetch_assoc()) {
            echo "<tr>
                    <td>{$i}</td>
                    <td>{$user['username']}</td>
                    <td>{$user['role']}</td>
                    <td>{$user['created_at']}</td>
                </tr>";
            $i++;
        }
        ?>
        </tbody>
    </table>
    <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>
</body>
</html>
