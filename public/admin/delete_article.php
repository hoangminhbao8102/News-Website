<?php
include('../../config/db.php');
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['Role'] !== 'Admin') {
    header('Location: ../login.php');
    exit();
}

$id = $_GET['id'];
sqlsrv_query($conn, "DELETE FROM Articles WHERE Id = ?", [$id]);
header("Location: articles.php");
