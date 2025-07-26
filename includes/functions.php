<?php
// ==============================
//  Hàm tiện ích dùng chung
// ==============================

// Kiểm tra đăng nhập Admin
function checkAdminLogin()
{
    if (!isset($_SESSION['user']) || $_SESSION['user']['Role'] !== 'Admin') {
        header('Location: ../login.php');
        exit();
    }
}

// Lấy tổng số bản ghi từ bảng
function getCount($conn, $table)
{
    $sql = "SELECT COUNT(*) AS Total FROM $table";
    $stmt = sqlsrv_query($conn, $sql);
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    return $row['Total'];
}

// Lấy 5 bài viết mới nhất
function getLatestArticles($conn, $limit = 5)
{
    $sql = "SELECT TOP $limit Title, CreatedAt FROM Articles ORDER BY CreatedAt DESC";
    return sqlsrv_query($conn, $sql);
}

// Chuyển đổi datetime SQL Server sang format đẹp
function formatDateTime($datetime)
{
    if ($datetime instanceof DateTime) {
        return $datetime->format('d/m/Y H:i');
    }
    return '';
}

// Rút gọn nội dung
function truncateText($text, $limit = 100)
{
    return mb_strlen($text) > $limit ? mb_substr($text, 0, $limit) . '...' : $text;
}
