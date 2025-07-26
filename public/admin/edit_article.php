<?php
include('../../config/db.php');
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['Role'] !== 'Admin') {
    header('Location: ../login.php');
    exit();
}

$id = $_GET['id'];
$stmt = sqlsrv_query($conn, "SELECT * FROM Articles WHERE Id = ?", [$id]);
$article = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $categoryId = $_POST['category_id'];
    $image = $article['Image'];

    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../../assets/img/$image");
    }

    $sql = "UPDATE Articles SET Title = ?, Content = ?, Image = ?, CategoryId = ? WHERE Id = ?";
    $params = [$title, $content, $image, $categoryId, $id];
    sqlsrv_query($conn, $sql, $params);
    header("Location: articles.php");
    exit();
}
?>

<?php include('../../includes/header.php'); ?>
<h2>Sửa bài viết</h2>
<form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label>Tiêu đề</label>
        <input type="text" name="title" class="form-control" value="<?php echo $article['Title']; ?>" required>
    </div>
    <div class="mb-3">
        <label>Nội dung</label>
        <textarea name="content" class="form-control" rows="5" required><?php echo $article['Content']; ?></textarea>
    </div>
    <div class="mb-3">
        <label>Danh mục</label>
        <select name="category_id" class="form-control">
            <?php
            $cats = sqlsrv_query($conn, "SELECT Id, Name FROM Categories");
            while ($row = sqlsrv_fetch_array($cats, SQLSRV_FETCH_ASSOC)) {
                $selected = $row['Id'] == $article['CategoryId'] ? 'selected' : '';
                echo "<option value='{$row['Id']}' $selected>{$row['Name']}</option>";
            }
            ?>
        </select>
    </div>
    <div class="mb-3">
        <label>Ảnh</label>
        <input type="file" name="image" class="form-control">
        <p>Ảnh hiện tại: <?php echo $article['Image']; ?></p>
    </div>
    <button type="submit" class="btn btn-warning">Cập nhật</button>
</form>
<?php include('../../includes/footer.php'); ?>
