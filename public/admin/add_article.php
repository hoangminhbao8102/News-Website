<?php
include('../../config/db.php');
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['Role'] !== 'Admin') {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $categoryId = $_POST['category_id'];
    $image = $_FILES['image']['name'];

    move_uploaded_file($_FILES['image']['tmp_name'], "../../assets/img/$image");

    $sql = "INSERT INTO Articles (Title, Content, Image, CategoryId, AuthorId) VALUES (?, ?, ?, ?, ?)";
    $params = [$title, $content, $image, $categoryId, $_SESSION['user']['Id']];
    sqlsrv_query($conn, $sql, $params);
    header("Location: articles.php");
    exit();
}
?>

<?php include('../../includes/header.php'); ?>
<h2>Thêm bài viết</h2>
<form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label>Tiêu đề</label>
        <input type="text" name="title" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Nội dung</label>
        <textarea name="content" class="form-control" rows="5" required></textarea>
    </div>
    <div class="mb-3">
        <label>Danh mục</label>
        <select name="category_id" class="form-control">
            <?php
            $cats = sqlsrv_query($conn, "SELECT Id, Name FROM Categories");
            while ($row = sqlsrv_fetch_array($cats, SQLSRV_FETCH_ASSOC)) {
                echo "<option value='{$row['Id']}'>{$row['Name']}</option>";
            }
            ?>
        </select>
    </div>
    <div class="mb-3">
        <label>Ảnh</label>
        <input type="file" name="image" class="form-control">
    </div>
    <button type="submit" class="btn btn-success">Thêm</button>
</form>
<?php include('../../includes/footer.php'); ?>
