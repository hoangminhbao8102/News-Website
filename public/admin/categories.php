<?php
include('../../config/db.php');
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['Role'] !== 'Admin') {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $slug = strtolower(str_replace(' ', '-', $name));
    sqlsrv_query($conn, "INSERT INTO Categories (Name, Slug) VALUES (?, ?)", [$name, $slug]);
}

$cats = sqlsrv_query($conn, "SELECT * FROM Categories");
?>

<?php include('../../includes/header.php'); ?>
<h2>Quản lý danh mục</h2>
<form method="POST" class="mb-3">
    <input type="text" name="name" placeholder="Tên danh mục" required>
    <button type="submit" class="btn btn-success">Thêm</button>
</form>
<table class="table table-bordered">
    <tr><th>Tên danh mục</th></tr>
    <?php while ($row = sqlsrv_fetch_array($cats, SQLSRV_FETCH_ASSOC)): ?>
    <tr><td><?php echo $row['Name']; ?></td></tr>
    <?php endwhile; ?>
</table>
<?php include('../../includes/footer.php'); ?>
