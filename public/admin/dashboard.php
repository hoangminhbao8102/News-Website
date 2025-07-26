<?php
include('../../config/db.php');
include('../../includes/functions.php');
session_start();

// Kiểm tra đăng nhập Admin
checkAdminLogin();

// Thống kê tổng số
$totalUsers = getCount($conn, 'Users');
$totalCategories = getCount($conn, 'Categories');
$totalArticles = getCount($conn, 'Articles');
$totalComments = getCount($conn, 'Comments');

// Lấy bài viết mới nhất
$latestArticles = getLatestArticles($conn, 5);
?>

<?php include('../../includes/header.php'); ?>

<h2>Bảng điều khiển</h2>
<div class="row text-center my-4">
    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h4><?php echo $totalUsers; ?></h4>
                <p>Người dùng</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h4><?php echo $totalCategories; ?></h4>
                <p>Danh mục</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h4><?php echo $totalArticles; ?></h4>
                <p>Bài viết</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <h4><?php echo $totalComments; ?></h4>
                <p>Bình luận</p>
            </div>
        </div>
    </div>
</div>

<h4>Bài viết mới nhất</h4>
<table class="table table-bordered">
    <tr>
        <th>Tiêu đề</th>
        <th>Ngày đăng</th>
    </tr>
    <?php while ($row = sqlsrv_fetch_array($latestArticles, SQLSRV_FETCH_ASSOC)): ?>
        <tr>
            <td><?php echo truncateText($row['Title'], 50); ?></td>
            <td><?php echo formatDateTime($row['CreatedAt']); ?></td>
        </tr>
    <?php endwhile; ?>
</table>

<!-- Quick Actions -->
<div class="d-flex gap-2 my-4">
    <a href="add_article.php" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Thêm bài viết
    </a>
    <a href="categories.php" class="btn btn-success">
        <i class="bi bi-tags"></i> Quản lý danh mục
    </a>
    <a href="articles.php" class="btn btn-warning">
        <i class="bi bi-card-list"></i> Quản lý bài viết
    </a>
</div>

<!-- Biểu đồ bài viết theo danh mục -->
<h4>Thống kê bài viết theo danh mục</h4>
<canvas id="articlesByCategory" height="120"></canvas>

<?php
// Lấy dữ liệu bài viết theo danh mục
$sqlChart = "SELECT Categories.Name, COUNT(Articles.Id) AS Total
             FROM Categories
             LEFT JOIN Articles ON Categories.Id = Articles.CategoryId
             GROUP BY Categories.Name";
$stmtChart = sqlsrv_query($conn, $sqlChart);

$labels = [];
$data = [];
while ($row = sqlsrv_fetch_array($stmtChart, SQLSRV_FETCH_ASSOC)) {
    $labels[] = $row['Name'];
    $data[] = $row['Total'];
}
?>

<!-- Biểu đồ tỷ lệ User/Admin -->
<h4 class="mt-5">Tỷ lệ người dùng (Admin/User)</h4>
<canvas id="usersByRole" height="120"></canvas>

<?php
// Lấy dữ liệu số lượng User và Admin
$sqlRole = "SELECT Role, COUNT(*) AS Total FROM Users GROUP BY Role";
$stmtRole = sqlsrv_query($conn, $sqlRole);

$roleLabels = [];
$roleData = [];
while ($row = sqlsrv_fetch_array($stmtRole, SQLSRV_FETCH_ASSOC)) {
    $roleLabels[] = $row['Role'];
    $roleData[] = $row['Total'];
}
?>

<!-- Bình luận mới nhất -->
<h4 class="mt-5">Bình luận mới nhất</h4>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Bài viết</th>
            <th>Nội dung</th>
            <th>Người gửi</th>
            <th>Thời gian</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sqlComments = "SELECT TOP 5 Comments.Content, Users.FullName, Articles.Title, Comments.CreatedAt
                        FROM Comments
                        JOIN Users ON Comments.UserId = Users.Id
                        JOIN Articles ON Comments.ArticleId = Articles.Id
                        ORDER BY Comments.CreatedAt DESC";
        $stmtComments = sqlsrv_query($conn, $sqlComments);

        while ($row = sqlsrv_fetch_array($stmtComments, SQLSRV_FETCH_ASSOC)) {
            echo "<tr>
                    <td>" . truncateText($row['Title'], 30) . "</td>
                    <td>" . truncateText($row['Content'], 50) . "</td>
                    <td>{$row['FullName']}</td>
                    <td>" . formatDateTime($row['CreatedAt']) . "</td>
                  </tr>";
        }
        ?>
    </tbody>
</table>

<!-- Thêm Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Biểu đồ cột: Bài viết theo danh mục
    const ctx = document.getElementById('articlesByCategory');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($labels); ?>,
            datasets: [{
                label: 'Số bài viết',
                data: <?php echo json_encode($data); ?>,
                backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#0dcaf0']
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Biểu đồ tròn: Tỷ lệ User/Admin
    const ctxRole = document.getElementById('usersByRole');
    new Chart(ctxRole, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($roleLabels); ?>,
            datasets: [{
                label: 'Người dùng',
                data: <?php echo json_encode($roleData); ?>,
                backgroundColor: ['#0d6efd', '#ffc107']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>

<?php include('../../includes/footer.php'); ?>
