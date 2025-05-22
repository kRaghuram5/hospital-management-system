<?php
$role = $_GET['role'] ?? '';
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo ucfirst($role); ?> Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js" defer></script>
</head>
<body class="bg-light">
    <div class="container col-md-4 mt-5">
        <h3 class="text-center mb-4"><?php echo ucfirst($role); ?> Login</h3>

        <form action="login.php?role=<?php echo $role; ?>" method="POST" class="mb-3">
            <input type="hidden" name="role" value="<?php echo $role; ?>">
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" required class="form-control">
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" required class="form-control">
            </div>
            <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
        </form>

        <?php if ($role === 'patient'): ?>
            <p class="text-center">
                Don't have an account?
                <a href="register.php?role=patient">Sign up</a>
            </p>
        <?php elseif ($role === 'admin'): ?>
            <p class="text-center">
                Don't have an account?
                <a href="admin_register.php">Register as Admin</a>
            </p>
        <?php endif; ?>
    </div>

<?php
if (isset($_POST['login'])) {
    include 'includes/db_config.php';

    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND role=?");
    $stmt->bind_param("ss", $username, $role);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $role;

        if ($role == 'admin') {
            header("Location: admin/dashboard.php");
        } else if ($role == 'doctor') {
            header("Location: doctor/dashboard.php");
        } else if ($role == 'patient') {
            header("Location: patient/dashboard.php");
        }
        exit;
    } else {
        echo "<div class='text-danger text-center mt-3'>Invalid credentials</div>";
    }
}
?>
</body>
</html>
