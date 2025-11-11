<?php include 'config/database.php';
$posts = $pdo->query("SELECT * FROM posts WHERE status='published' ORDER BY created_at DESC")->fetchAll();
?>
<base href="http://localhost/php_blog_project/">

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/bootstrap-icons.css">
    <title>My Blog</title>

    <!-- Bootstrap -->

</head>

<body class="d-flex flex-column min-vh-100">
