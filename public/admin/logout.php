<?php
session_start();

// Hủy toàn bộ session
session_unset();
session_destroy();

// Điều hướng về trang đăng nhập admin
header("Location: ../login.php");
exit();
