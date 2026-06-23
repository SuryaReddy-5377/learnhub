<?php
$page_title = 'Analytics';
require_once '../config/database.php';
require_once 'admin-header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// Get monthly data
$monthly_data = mysqli_query($conn, "
    SELECT DATE_FORMAT(created_at, '%b') as month, COUNT(*) as count 
    FROM users 
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
    GROUP BY MONTH(created_at)
    ORDER BY created_at ASC
");
$months = [];
$user_counts = [];
while ($row = mysqli_fetch_assoc($monthly_data)) {
    $months[] = $row['month'];
    $user_counts[] = $row['count'];
}

// Get course enrollments
$course_enrollments = mysqli_query($conn, "
    SELECT c.title, COUNT(e.id) as count 
    FROM courses c 
    LEFT JOIN enrollments e ON c.id = e.course_id 
    GROUP BY c.id 
    ORDER BY count DESC 
    LIMIT 5
");
?>

<div class="row">
    <div class="col-12">
        <h2 class="fw-bold"><i class="fas fa-chart-line me-2"></i>Analytics</h2>
        <p class="text-muted">User growth and course performance</p>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-6">
        <div class="card form-card">
            <div class="card-body p-4">
                <h5 class="fw-bold">User Growth (Last 6 Months)</h5>
                <canvas id="userChart" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card form-card">
            <div class="card-body p-4">
                <h5 class="fw-bold">Top 5 Courses</h5>
                <canvas id="courseChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // User Growth Chart
    const ctx1 = document.getElementById('userChart').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($months); ?>,
            datasets: [{
                label: 'New Users',
                data: <?php echo json_encode($user_counts); ?>,
                backgroundColor: 'rgba(108, 60, 225, 0.1)',
                borderColor: '#6C3CE1',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });

    // Course Chart
    const ctx2 = document.getElementById('courseChart').getContext('2d');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: <?php 
                $labels = [];
                $data = [];
                while ($row = mysqli_fetch_assoc($course_enrollments)) {
                    $labels[] = $row['title'];
                    $data[] = $row['count'];
                }
                echo json_encode($labels);
            ?>,
            datasets: [{
                label: 'Enrollments',
                data: <?php echo json_encode($data); ?>,
                backgroundColor: 'rgba(108, 60, 225, 0.7)',
                borderColor: '#6C3CE1',
                borderWidth: 2,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });
});
</script>

<?php require_once 'admin-footer.php'; ?>