<?php
include('../config/db.php');
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM Users WHERE Username = ?";
    $stmt = sqlsrv_query($conn, $sql, [$username]);
    $user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    if ($user && $user['Password'] === $password) {
        $_SESSION['user'] = $user;
        if ($user['Role'] === 'Admin') {
            header('Location: admin/dashboard.php');
        } else {
            header('Location: index.php');
        }
        exit();
    } else {
        $error = "Sai tên đăng nhập hoặc mật khẩu";
    }
}
?>

<?php include('../includes/header.php'); ?>
<h2>Đăng nhập</h2>
<?php if($error): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<form method="POST">
    <div class="mb-3">
        <label>Tên đăng nhập</label>
        <input type="text" name="username" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Mật khẩu</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Đăng nhập</button>
</form>
<?php include('../includes/footer.php'); ?>
