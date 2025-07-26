<?php
include('../config/db.php');
include('../includes/header.php');

$id = $_GET['id'] ?? 0;

// Lấy bài viết
$sql = "SELECT Articles.Title, Articles.Content, Articles.Image, Categories.Name AS CategoryName
        FROM Articles
        JOIN Categories ON Articles.CategoryId = Categories.Id
        WHERE Articles.Id = ?";
$stmt = sqlsrv_query($conn, $sql, [$id]);
$article = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

// Xử lý bình luận
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user'])) {
    $content = $_POST['comment'];
    $userId = $_SESSION['user']['Id'];
    sqlsrv_query($conn, "INSERT INTO Comments (ArticleId, UserId, Content) VALUES (?, ?, ?)", [$id, $userId, $content]);
}

// Lấy bình luận
$comments = sqlsrv_query($conn,
    "SELECT Comments.Content, Users.FullName, Comments.CreatedAt
     FROM Comments
     JOIN Users ON Comments.UserId = Users.Id
     WHERE ArticleId = ?
     ORDER BY Comments.CreatedAt DESC", [$id]);
?>

<div class="row">
    <div class="col-md-8">
        <h2><?php echo $article['Title']; ?></h2>
        <p><small>Danh mục: <?php echo $article['CategoryName']; ?></small></p>
        <img src="../assets/img/<?php echo $row['Image'] ?: 'news1.jpg'; ?>" class="card-img-top">
        <p><?php echo nl2br($article['Content']); ?></p>
    </div>

    <div class="col-md-4">
        <h4>Bình luận</h4>
        <?php if(isset($_SESSION['user'])): ?>
        <form method="POST" class="mb-3">
            <textarea name="comment" class="form-control" required></textarea>
            <button type="submit" class="btn btn-primary mt-2">Gửi</button>
        </form>
        <?php else: ?>
        <p>Vui lòng <a href="login.php">đăng nhập</a> để bình luận.</p>
        <?php endif; ?>

        <?php while ($c = sqlsrv_fetch_array($comments, SQLSRV_FETCH_ASSOC)) : ?>
            <div class="border p-2 mb-2">
                <strong><?php echo $c['FullName']; ?></strong><br>
                <small><?php echo $c['CreatedAt']->format('d/m/Y H:i'); ?></small>
                <p><?php echo $c['Content']; ?></p>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
