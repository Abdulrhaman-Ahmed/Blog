<?php
require_once '../config/database.php';
include 'includes/header.php';
include 'includes/sidebar.php';

if (!isset($_GET['id'])) {
  header("Location: posts.php");
  exit;
}

$post_id = (int) $_GET['id'];

// Fetch post data
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$post_id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
  echo "<div class='alert alert-danger m-4'>Post not found!</div>";
  exit;
}

// Update post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = $_POST['title'] ?? '';
  $content = $_POST['content'] ?? '';
  $category_id = (int) ($_POST['category_id'] ?? 0);
  $status = $_POST['status'] ?? 'draft';

  $image = $post['image'];

  if (!empty($_FILES['image']['name'])) {
    $fileName = time() . '_' . basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/' . $fileName);
    $image = $fileName;
  }

  $stmt = $pdo->prepare("UPDATE posts SET title=?, content=?, image=?, category_id=?, status=? WHERE id=?");
  $stmt->execute([$title, $content, $image, $category_id, $status, $post_id]);

  header("Location: posts.php");
  exit;
}

// Get categories
$cats = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
  <div class="py-4">
    <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom">
      <h1 class="h2"><i class="bi bi-pencil-square"></i> Edit Post</h1>
      <a href="posts.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back</a>
    </div>

    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">Edit Post</h5>
      </div>
      <div class="card-body">
        <form method="POST" enctype="multipart/form-data">

          <div class="mb-3">
            <label class="form-label">Title *</label>
            <input type="text" class="form-control" name="title" value="<?php echo $post['title']; ?>"
              required>
          </div>

          <div class="mb-3">
            <label class="form-label">Content *</label>
            <textarea class="form-control" rows="6" name="content"
              required><?php echo $post['content']; ?></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Category *</label>
            <select class="form-select" name="category_id" required>
              <?php foreach ($cats as $c): ?>
                <option value="<?php echo $c['id']; ?>" <?php if ($post['category_id'] == $c['id'])
                     echo 'selected'; ?>>
                  <?php echo $c['name']; ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Status</label>
            <select class="form-select" name="status">
              <option value="draft" <?php if ($post['status'] == 'draft')
                echo 'selected'; ?>>Draft</option>
              <option value="published" <?php if ($post['status'] == 'published')
                echo 'selected'; ?>>Published</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Current Image</label><br>
            <?php if (!empty($post['image'])): ?>
              <img src="../uploads/<?php echo $post['image']; ?>" width="150" class="rounded mb-2">
            <?php else: ?>
              <p class="text-muted">No image uploaded</p>
            <?php endif; ?>
            <input type="file" class="form-control" name="image">
          </div>

          <button type="submit" class="btn btn-warning w-100">
            <i class="bi bi-save"></i> Update
          </button>

        </form>
      </div>
    </div>
  </div>
</main>

<?php include 'includes/footer.php'; ?>