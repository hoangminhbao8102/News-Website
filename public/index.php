<?php
include('../config/db.php');
include('../includes/header.php');

// Lấy danh mục
$categories = sqlsrv_query($conn, "SELECT * FROM Categories");

// Lấy 6 bài viết mới nhất
$sql = "SELECT TOP 6 Articles.Id, Title, Image, Name AS CategoryName
        FROM Articles
        JOIN Categories ON Articles.CategoryId = Categories.Id
        ORDER BY Articles.CreatedAt DESC";
$articles = sqlsrv_query($conn, $sql);
?>

<div class="row">
    <div class="col-md-3">
        <h4>Danh mục</h4>
        <ul class="list-group">
            <?php while ($cat = sqlsrv_fetch_array($categories, SQLSRV_FETCH_ASSOC)) : ?>
                <li class="list-group-item">
                    <a href="category.php?id=<?php echo $cat['Id']; ?>"><?php echo $cat['Name']; ?></a>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>

    <div class="col-md-9">
        <h4>Bài viết mới nhất</h4>
        <div class="row">
            <?php while ($row = sqlsrv_fetch_array($articles, SQLSRV_FETCH_ASSOC)) : ?>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <img src="../assets/img/<?php echo $row['Image'] ?: 'news1.jpg'; ?>" class="card-img-top">
                        <div class="card-body">
                            <h5><?php echo $row['Title']; ?></h5>
                            <p><small><?php echo $row['CategoryName']; ?></small></p>
                            <a href="article.php?id=<?php echo $row['Id']; ?>" class="btn btn-primary btn-sm">Đọc tiếp</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
