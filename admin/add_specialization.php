<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php?role=admin");
    exit;
}
include '../includes/db_config.php';

// Add specialization
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $stmt = $conn->prepare("INSERT INTO specializations (name) VALUES (?)");
    $stmt->bind_param("s", $name);
    $stmt->execute();
}

// Delete specialization
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM specializations WHERE id = $id");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Specializations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2>Add / Edit Specializations</h2>
    <form method="POST" class="mb-3">
        <div class="input-group">
            <input type="text" name="name" required placeholder="Specialization Name" class="form-control">
            <button type="submit" name="add" class="btn btn-primary">Add</button>
        </div>
    </form>
    <table class="table table-bordered">
        <thead><tr><th>#</th><th>Name</th><th>Action</th></tr></thead>
        <tbody>
            <?php
            $specs = $conn->query("SELECT * FROM specializations");
            $i = 1;
            while ($row = $specs->fetch_assoc()) {
                echo "<tr>
                        <td>{$i}</td>
                        <td>{$row['name']}</td>
                        <td><a href='?delete={$row['id']}' class='btn btn-danger btn-sm'>Delete</a></td>
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
