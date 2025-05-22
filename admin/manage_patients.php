<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php?role=admin");
    exit;
}
include '../includes/db_config.php';

// Add Patient
if (isset($_POST['add_patient'])) {
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Insert into users table
    $stmt1 = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'patient')");
    $stmt1->bind_param("ss", $username, $password);
    $stmt1->execute();
    $user_id = $conn->insert_id;

    // Insert into patients table
    $stmt2 = $conn->prepare("INSERT INTO patients (user_id, name, contact, gender, dob, address) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt2->bind_param("isssss", $user_id, $name, $contact, $gender, $dob, $address);
    $stmt2->execute();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Patients</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <h2>Manage Patients</h2>

        <!-- Add Patient Form -->
        <form method="POST" class="mb-4">
            <div class="row g-2">
                <div class="col-md-3">
                    <input type="text" name="name" required class="form-control" placeholder="Patient Name">
                </div>
                <div class="col-md-3">
                    <input type="text" name="contact" required class="form-control" placeholder="Contact">
                </div>
                <div class="col-md-3">
                    <select name="gender" required class="form-control">
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="date" name="dob" required class="form-control" placeholder="Date of Birth">
                </div>
                <div class="col-md-3 mt-2">
                    <textarea name="address" required class="form-control" placeholder="Address"></textarea>
                </div>
                <div class="col-md-3 mt-2">
                    <input type="text" name="username" required class="form-control" placeholder="Login Username">
                </div>
                <div class="col-md-3 mt-2">
                    <input type="password" name="password" required class="form-control" placeholder="Login Password">
                </div>
                <div class="col-md-2 mt-2">
                    <button type="submit" name="add_patient" class="btn btn-success w-100">Add Patient</button>
                </div>
            </div>
        </form>

        <!-- Patient List -->
        <h5>Patient List</h5>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Gender</th>
                    <th>Date of Birth</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT p.*, u.username FROM patients p LEFT JOIN users u ON p.user_id = u.id");
                $i = 1;
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$i}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['contact']}</td>
                            <td>{$row['gender']}</td>
                            <td>{$row['dob']}</td>
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
