<?php
require_once 'config/database.php';
$post_id = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ? AND status = 'published'");
$stmt->execute([$post_id]);
$post = $stmt->fetch();

if (!$post) {
    die("Post not found.");
}


$pdo->prepare("UPDATE posts SET views = views + 1 WHERE id = ?")->execute([$post_id]);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $comment = trim($_POST['comment'] ?? '');
    if ($name && $email && $comment) {
        $pdo->prepare("INSERT INTO comments (post_id, name, email, comment) VALUES (?, ?, ?, ?)")
            ->execute([$post_id, $name, $email, $comment]);
        $success = "Your comment has been submitted and is awaiting approval.";
    }
}

// fetch approved comments
$comments_stmt = $pdo->prepare("SELECT * FROM comments WHERE post_id = ? AND status = 'approved' ORDER BY created_at DESC");
$comments_stmt->execute([$post_id]);
$comments = $comments_stmt->fetchAll();
?>
<?php include("./inc/Header.php") ?>

    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">My Blog</a>
            <a href="index.php" class="btn btn-outline-light btn-sm">Home</a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <article>
                    <h1 class="mb-3"><?php echo htmlspecialchars($post['title']); ?></h1>

                    <div class="text-muted mb-4">
                        Published: <?php echo date('F j, Y', strtotime($post['created_at'])); ?> |
                        Views: <?php echo $post['views']; ?>
                    </div>

                    <?php if (!empty($post['image'])): ?>
                        <img src="uploads/<?php echo htmlspecialchars($post['image']); ?>"
                            alt="<?php echo htmlspecialchars($post['title']); ?>" class="img-fluid mb-4 rounded">
                    <?php endif; ?>

                    <div class="post-content mb-4">
                        <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                    </div>

                    <a href="index.php" class="btn btn-primary">Back to Posts</a>
                </article>

                <!-- Comments Section -->
                <div class="mt-5">
                    <h3>Comments</h3>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>

                    <!-- Comment Form -->
                    <form method="POST" class="mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" name="name" class="form-control mb-2" placeholder="Name" required>
                            </div>
                            <div class="col-md-6">
                                <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
                            </div>
                        </div>
                        <textarea name="comment" class="form-control mb-2" rows="3" placeholder="Comment"
                            required></textarea>
                        <button type="submit" class="btn btn-primary">Submit Comment</button>
                    </form>

                    <!-- Comments List -->
                    <?php foreach ($comments as $c): ?>
                        <div class="border p-3 mb-2">
                            <strong><?php echo htmlspecialchars($c['name']); ?></strong>
                            <small class="text-muted"> - <?php echo $c['created_at']; ?></small>
                            <p class="mb-0"><?php echo nl2br(htmlspecialchars($c['comment'])); ?></p>
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>
        </div>
    </div>

<?php include("./inc/Footer.php") ?>