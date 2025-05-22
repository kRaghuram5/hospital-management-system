<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php?role=admin");
    exit;
}
include '../includes/db_config.php';

// Add Doctor
if (isset($_POST['add_doctor'])) {
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $specialization_id = $_POST['specialization_id'];
    $experience = $_POST['experience'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Insert into users table
    $stmt1 = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'doctor')");
    $stmt1->bind_param("ss", $username, $password);
    $stmt1->execute();
    $user_id = $conn->insert_id;

    // Insert into doctors table
    $stmt2 = $conn->prepare("INSERT INTO doctors (user_id, name, contact, specialization_id, experience) VALUES (?, ?, ?, ?, ?)");
    $stmt2->bind_param("issii", $user_id, $name, $contact, $specialization_id, $experience);
    $stmt2->execute();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Doctors</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2>Manage Doctors</h2>

    <!-- Add Doctor Form -->
    <form method="POST" class="mb-4">
        <div class="row g-2">
            <div class="col-md-3">
                <input type="text" name="name" required class="form-control" placeholder="Doctor Name">
            </div>
            <div class="col-md-3">
                <input type="text" name="contact" required class="form-control" placeholder="Contact">
            </div>
            <div class="col-md-3">
                <select name="specialization_id" required class="form-control">
                    <option value="">Select Specialization</option>
                    <?php
                    $specs = $conn->query("SELECT * FROM specializations");
                    while ($spec = $specs->fetch_assoc()) {
                        echo "<option value='{$spec['id']}'>{$spec['name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-3">
                <input type="number" name="experience" required class="form-control" placeholder="Experience (Years)">
            </div>
            <div class="col-md-3 mt-2">
                <input type="text" name="username" required class="form-control" placeholder="Login Username">
            </div>
            <div class="col-md-3 mt-2">
                <input type="password" name="password" required class="form-control" placeholder="Login Password">
            </div>
            <div class="col-md-2 mt-2">
                <button type="submit" name="add_doctor" class="btn btn-success w-100">Add Doctor</button>
            </div>
        </div>
    </form>

    <!-- Doctor List -->
    <h5>Doctor List</h5>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Contact</th>
                <th>Specialization</th>
                <th>Experience</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT d.*, s.name AS spec_name FROM doctors d LEFT JOIN specializations s ON d.specialization_id = s.id");
            $i = 1;
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$i}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['contact']}</td>
                        <td>{$row['spec_name']}</td>
                        <td>{$row['experience']} yrs</td>
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
