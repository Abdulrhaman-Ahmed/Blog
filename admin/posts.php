<?php
include '../config/database.php';
include 'includes/header.php';
include 'includes/sidebar.php';


// delete post
if (isset($_GET['del_id'])) {
    $del_id = $_GET['del_id'];
    $stmt = $pdo->prepare("SELECT image FROM posts WHERE id = ?");
    $stmt->execute([$del_id]);
    $post = $stmt->fetch();

    if (!empty($post['image'])) {
        $image_path = '../uploads/' . $post['image'];
        unlink($image_path);
    }
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->execute([$del_id]);

    header('Location: posts.php');
    exit;
}


$stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC");
$posts = $stmt->fetchAll();
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="py-4">
        <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2"><i class="bi bi-file-text"></i> Posts Management</h1>
            <a href="addpost.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add Post</a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">All Posts</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Category ID</th>
                            <th>Status</th>
                            <th>Views</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($posts as $post){ ?>
                            <tr>
                                <td><?= $post['id']; ?></td>
                                <td><?= $post['title']; ?></td>
                                <td><?= $post['category_id']; ?></td>
                                <td>
                                    <?=  $post['status'] === 'published'
                                        ? '<span class="badge bg-success">Published</span>'
                                        : '<span class="badge bg-secondary">Draft</span>'; ?>
                                </td>
                                <td><?=  $post['views']; ?></td>
                                <td><?=  date('M j, Y', strtotime($post['created_at'])); ?></td>
                                <td>
                                    <a href="editpost.php?id=<?= $post['id']; ?>"
                                        class="btn btn-sm btn-primary">Edit</a>
                                    <a href="posts.php?del_id=<?= $post['id']; ?>" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Delete this post?')">Delete</a>
                                </td>
                            </tr>
                        <?php }; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>