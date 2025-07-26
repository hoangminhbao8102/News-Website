<?php
include('../config/db.php');
include('../includes/header.php');

$cat_id = $_GET['id'] ?? 0;

// Lấy tên danh mục
$stmtCat = sqlsrv_query($conn, "SELECT Name FROM Categories WHERE Id = ?", [$cat_id]);
$category = sqlsrv_fetch_array($stmtCat, SQLSRV_FETCH_ASSOC);

// Lấy bài viết trong danh mục
$sql = "SELECT Id, Title, Image FROM Articles WHERE CategoryId = ? ORDER BY CreatedAt DESC";
$stmt = sqlsrv_query($conn, $sql, [$cat_id]);
?>

<h3>Danh mục: <?php echo $category['Name']; ?></h3>
<div class="row mt-3">
    <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) : ?>
        <div class="col-md-4 mb-3">
            <div class="card">
                <img src="../assets/img/<?php echo $row['Image'] ?: 'news1.jpg'; ?>" class="card-img-top">
                <div class="card-body">
                    <h5><?php echo $row['Title']; ?></h5>
                    <a href="article.php?id=<?php echo $row['Id']; ?>" class="btn btn-primary btn-sm">Đọc tiếp</a>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<?php include('../includes/footer.php'); ?>
