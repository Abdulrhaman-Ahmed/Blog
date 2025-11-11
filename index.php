<?php include("./inc/Header.php") ?>
<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">My Blog</a>
    </div>
</nav>

<div class="container mt-4 mb-4">
    <h1 class="mb-4 text-center fw-bold">Latest Posts</h1>

    <div class="row g-4">
        <?php foreach ($posts as $post): ?>
        <div class="col-md-4">
            <div class="card shadow-sm h-100">

                <?php if (!empty($post['image'])): ?>
                    <img src="uploads/<?php echo htmlspecialchars($post['image']); ?>"
                         class="card-img-top"
                         style="height:200px; object-fit:cover;">
                <?php else: ?>
                    <div style="height:200px; background:#ddd;"></div>
                <?php endif; ?>

                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-bold"><?php echo htmlspecialchars($post['title']); ?></h5>
                    <p class="text-muted mb-1">
                        <?php echo date('F j, Y', strtotime($post['created_at'])); ?>
                    </p>

                    <p class="card-text">
                        <?php echo mb_substr(strip_tags($post['content']), 0, 120) . "..."; ?>
                    </p>

                    <a href="post.php?id=<?php echo $post['id']; ?>" class="btn btn-primary mt-auto w-100">
                        Read More
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

        <?php if (empty($posts)): ?>
        <p class="text-center text-muted">No posts available.</p>
        <?php endif; ?>
    </div>
</div>
<?php include("./inc/Footer.php") ?>