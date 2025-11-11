<?php
// auth/login.php - very basic (for demo only)
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';

    // hardcoded demo credentials: admin / admin123
    if ($user === 'admin' && $pass === 'admin123') {
        $_SESSION['admin'] = true;
        header('Location: ../admin/index.php');
        exit;
    } else {
        $error = 'Invalid login credentials';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">

                <h3 class="mb-3">Login</h3>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST">
                    <input class="form-control mb-2" name="username" placeholder="Username">
                    <input class="form-control mb-2" name="password" type="password" placeholder="Password">
                    <button class="btn btn-primary w-100">Login</button>
                </form>

            </div>
        </div>
    </div>
</body>

</html>