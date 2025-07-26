<?php
include('../../config/db.php');
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['Role'] !== 'Admin') {
    header('Location: ../login.php');
    exit();
}

$sql = "SELECT Articles.Id, Title, Name AS CategoryName
        FROM Articles
        JOIN Categories ON Articles.CategoryId = Categories.Id
        ORDER BY Articles.CreatedAt DESC";
$stmt = sqlsrv_query($conn, $sql);
?>

<?php include('../../includes/header.php'); ?>
<h2>Quản lý bài viết</h2>
<a href="add_article.php" class="btn btn-primary mb-3">Thêm bài viết</a>
<table class="table table-bordered">
    <tr>
        <th>Tiêu đề</th>
        <th>Danh mục</th>
        <th>Hành động</th>
    </tr>
    <?php while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
    <tr>
        <td><?php echo $row['Title']; ?></td>
        <td><?php echo $row['CategoryName']; ?></td>
        <td>
            <a href="edit_article.php?id=<?php echo $row['Id']; ?>" class="btn btn-warning btn-sm">Sửa</a>
            <a href="delete_article.php?id=<?php echo $row['Id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Xóa bài viết?');">Xóa</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
<?php include('../../includes/footer.php'); ?>
