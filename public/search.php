<?php
include('../config/db.php');
include('../includes/header.php');

$keyword = $_GET['q'] ?? '';

$sql = "SELECT Id, Title, Image FROM Articles WHERE Title LIKE ? ORDER BY CreatedAt DESC";
$params = ['%' . $keyword . '%'];
$stmt = sqlsrv_query($conn, $sql, $params);
?>

<h3>Kết quả tìm kiếm cho: "<?php echo htmlspecialchars($keyword); ?>"</h3>
<div class="row mt-3">
    <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) : ?>
        <div class="col-md-4 mb-3">
            <div class="card">
                <img src="../assets/img/<?php echo $row['Image']; ?>" class="card-img-top" style="height:180px;object-fit:cover;">
                <div class="card-body">
                    <h5><?php echo $row['Title']; ?></h5>
                    <a href="article.php?id=<?php echo $row['Id']; ?>" class="btn btn-primary btn-sm">Đọc tiếp</a>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<?php include('../includes/footer.php'); ?>
