<?php
include('../config/db.php');
session_start();

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Kiểm tra username/email đã tồn tại
    $check = "SELECT * FROM Users WHERE Username = ? OR Email = ?";
    $stmt = sqlsrv_query($conn, $check, [$username, $email]);
    if (sqlsrv_fetch_array($stmt)) {
        $message = "Tên đăng nhập hoặc email đã tồn tại!";
    } else {
        $sql = "INSERT INTO Users (FullName, Email, Username, Password, Role) VALUES (?, ?, ?, ?, 'User')";
        $params = [$fullname, $email, $username, $password];
        if (sqlsrv_query($conn, $sql, $params)) {
            $message = "Đăng ký thành công! Vui lòng đăng nhập.";
        } else {
            $message = "Lỗi khi đăng ký!";
        }
    }
}
?>

<?php include('../includes/header.php'); ?>
<h2>Đăng ký</h2>
<?php if($message): ?>
<div class="alert alert-info"><?php echo $message; ?></div>
<?php endif; ?>
<form method="POST">
    <div class="mb-3">
        <label>Họ tên</label>
        <input type="text" name="fullname" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Tên đăng nhập</label>
        <input type="text" name="username" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Mật khẩu</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-success">Đăng ký</button>
</form>
<?php include('../includes/footer.php'); ?>
