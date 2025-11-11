<?php
require_once '../config/database.php';
include 'includes/Header.php';
include 'includes/sidebar.php';

$message = '';
$message_type = '';

if (isset($_GET['action']) && isset($_GET['id'])) {
    $comment_id = (int) $_GET['id'];
    $action = $_GET['action'];

    if ($action === 'approve') {
        $pdo->prepare("UPDATE comments SET status = 'approved' WHERE id = ?")->execute([$comment_id]);
        header('Location: comments.php');
        exit;
    }
    if ($action === 'reject') {
        $pdo->prepare("UPDATE comments SET status = 'rejected' WHERE id = ?")->execute([$comment_id]);
        header('Location: comments.php');
        exit;
    }
    if ($action === 'delete') {
        $pdo->prepare("DELETE FROM comments WHERE id = ?")->execute([$comment_id]);
        header('Location: comments.php');
        exit;
    }
}

$stmt = $pdo->query("SELECT * FROM comments ORDER BY created_at DESC");
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_comments = count($comments);
$pending_comments = $pdo->query("SELECT COUNT(*) FROM comments WHERE status = 'pending'")->fetchColumn();
$approved_comments = $pdo->query("SELECT COUNT(*) FROM comments WHERE status = 'approved'")->fetchColumn();
$rejected_comments = $pdo->query("SELECT COUNT(*) FROM comments WHERE status = 'rejected'")->fetchColumn();
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="py-4">
        <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2"><i class="bi bi-chat-dots"></i> Comments Management</h1>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">All Comments</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Comment</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($comments as $comment): ?>
                                <tr>
                                    <td><?php echo $comment['id']; ?></td>
                                    <td><?php echo $comment['name']; ?></td>
                                    <td><?php echo$comment['comment']; ?></td>
                                    <td>
                                        <?php if ($comment['status'] == 'approved'): ?>
                                            <span class="badge bg-success">Approved</span>
                                        <?php elseif ($comment['status'] == 'pending'): ?>
                                            <span class="badge bg-warning">Pending</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Rejected</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo date('M j, Y', strtotime($comment['created_at'])); ?></td>
                                    <td>
                                        <?php if ($comment['status'] != 'approved'): ?>
                                            <a href="comments.php?action=approve&id=<?php echo $comment['id']; ?>"
                                                class="btn btn-sm btn-success">Approve</a>
                                        <?php endif; ?>
                                        <?php if ($comment['status'] != 'rejected'): ?>
                                            <a href="comments.php?action=reject&id=<?php echo $comment['id']; ?>"
                                                class="btn btn-sm btn-warning">Reject</a>
                                        <?php endif; ?>
                                        <a href="comments.php?action=delete&id=<?php echo $comment['id']; ?>"
                                            class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4><?php echo $total_comments; ?></h4>
                                <p class="mb-0">Total Comments</p>
                            </div>
                            <div class="align-self-center"><i class="bi bi-chat-dots fs-1"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4><?php echo $pending_comments; ?></h4>
                                <p class="mb-0">Pending</p>
                            </div>
                            <div class="align-self-center"><i class="bi bi-clock fs-1"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4><?php echo $approved_comments; ?></h4>
                                <p class="mb-0">Approved</p>
                            </div>
                            <div class="align-self-center"><i class="bi bi-check-circle fs-1"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-white bg-danger">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4><?php echo $rejected_comments; ?></h4>
                                <p class="mb-0">Rejected</p>
                            </div>
                            <div class="align-self-center"><i class="bi bi-x-circle fs-1"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>

<?php include 'includes/footer.php'; ?>