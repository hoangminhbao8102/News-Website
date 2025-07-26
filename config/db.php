<?php
$serverName = "LAPTOP-N4TOHTRH\SQLEXPRESS"; // Tên server SQL
$connectionOptions = [
    "Database" => "News_Website",
    "Uid" => "sa",          // user SQL Server
    "PWD" => "minhbao8102",      // password
    "CharacterSet" => "UTF-8",
    "TrustServerCertificate" => true, // Bỏ qua xác thực chứng chỉ SSL
];

// Kết nối
$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>
