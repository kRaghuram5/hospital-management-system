<?php
session_start();
include 'includes/db_config.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin_code = strtolower(trim($_POST['admin_code']));
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $contact = trim($_POST['contact']);
    $address = trim($_POST['address']);
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Validate secret code format
    if (!preg_match('/^adm\d{3}$/', $admin_code)) {
        $error = "Invalid secret key format. Use format: adm followed by 3 digits (e.g., adm001)";
    } else {
        // Check if admin code already exists
        $stmt = $conn->prepare("SELECT id FROM admin_profiles WHERE admin_code = ?");
        $stmt->bind_param("s", $admin_code);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Admin secret key already used.";
        } else {
            // Check if username already exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $error = "Username already taken.";
            } else {
                // Insert into users table
                $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'admin')");
                $stmt->bind_param("ss", $username, $password);
                if ($stmt->execute()) {
                    $user_id = $conn->insert_id;

                    // Insert into admin_profiles table
                    $stmt2 = $conn->prepare("INSERT INTO admin_profiles (user_id, admin_code, name, email, contact, address) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt2->bind_param("isssss", $user_id, $admin_code, $name, $email, $contact, $address);
                    if ($stmt2->execute()) {
                        // ✅ Redirect to login after success
                        header("Location: login.php?role=admin");
                        exit;
                    } else {
                        $error = "Failed to save admin profile.";
                    }
                } else {
                    $error = "Error creating admin user.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Registration</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container col-md-6 mt-5">
    <h3 class="text-center mb-4">Admin Registration</h3>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" class="card p-4">
        <div class="mb-3">
            <label for="admin_code" class="form-label">Secret Admin Key (e.g., adm001)</label>
            <input type="text" name="admin_code" id="admin_code" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="contact" class="form-label">Phone Number</label>
            <input type="text" name="contact" id="contact" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <textarea name="address" id="address" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">Login Username</label>
            <input type="text" name="username" id="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Login Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Register</button>
        <a href="login.php?role=admin" class="btn btn-secondary w-100 mt-2">Back to Login</a>
    </form>
</div>
</body>
</html>
