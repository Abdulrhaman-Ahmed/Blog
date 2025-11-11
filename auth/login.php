<?php
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ;
    $pass = $_POST['password'];

    if ($user === 'admin' && $pass === 'admin123') {
        $_SESSION['admin'] = true;
        header('Location: ../admin/index.php');
        exit;
    } else {
        $error = 'Invalid login information';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="card-title text-center mb-4">Admin Login</h3>
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>
                        <form method="POST" autocomplete="off">
                            <div class="mb-3">
                                <input type="text" class="form-control form-control-lg" name="username" placeholder="Username" required>
                            </div>
                            <div class="mb-3">
                                <input type="password" class="form-control form-control-lg" name="password" placeholder="Password" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
                <p class="text-center text-muted mt-3">&copy; <?=  date('Y'); ?> Blog System </p>
            </div>
        </div>
    </div>
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>

</html>
