<?php
$page_title = 'Dashboard';
require_once 'config/database.php';
require_once 'includes/header.php';
if (!isLoggedIn()) { redirect('login.php'); }

$user = getUser($_SESSION['user_id']);
$my_enrollments = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM enrollments WHERE user_id={$_SESSION['user_id']} AND status='active'"))['c'];
$completed = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM enrollments WHERE user_id={$_SESSION['user_id']} AND status='completed'"))['c'];
$total_courses = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM courses WHERE status='published'"))['c'];

// Recent enrolled courses
$recent = mysqli_query($conn, "SELECT c.*, cat.name as category_name, u.first_name, u.last_name
    FROM enrollments e JOIN courses c ON e.course_id=c.id
    JOIN categories cat ON c.category_id=cat.id
    JOIN users u ON c.instructor_id=u.id
    WHERE e.user_id={$_SESSION['user_id']} AND e.status='active'
    ORDER BY e.enrolled_at DESC LIMIT 3");

$colors = ['#6C3CE1','#EC4899','#10B981','#F59E0B','#3B82F6','#EF4444'];
$icons  = ['fa-code','fa-chart-bar','fa-mobile-alt','fa-cloud','fa-paint-brush','fa-briefcase'];
?>

<div class="main-content">
<div class="container py-4">

<!-- Welcome Banner -->
<div class="form-card mb-4 p-4" style="background: linear-gradient(135deg,#6C3CE1,#EC4899) !important; border:none !important;">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h2 class="fw-bold text-white mb-1">Welcome back, <?php echo htmlspecialchars($user['first_name']); ?>! 👋</h2>
            <p class="text-white mb-3" style="opacity:0.9;">Role: <strong><?php echo ucfirst($_SESSION['role']); ?></strong> | Keep learning and growing!</p>
            <div class="d-flex gap-2 flex-wrap">
                <a href="courses.php" class="btn btn-light btn-sm"><i class="fas fa-rocket me-1"></i>Browse Courses</a>
                <a href="my-courses.php" class="btn btn-outline-light btn-sm"><i class="fas fa-book me-1"></i>My Courses</a>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a href="admin/index.php" class="btn btn-warning btn-sm"><i class="fas fa-cog me-1"></i>Admin Panel</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-lg-4 text-center d-none d-lg-block">
            <i class="fas fa-graduation-cap" style="font-size:6rem; color:rgba(255,255,255,0.3);"></i>
        </div>
    </div>
</div>

<!-- Stats -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stat-card text-center">
            <div class="stat-icon"><i class="fas fa-book-open"></i></div>
            <h3><?php echo $my_enrollments; ?></h3>
            <small>Enrolled Courses</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card text-center">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <h3><?php echo $completed; ?></h3>
            <small>Completed</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card text-center">
            <div class="stat-icon"><i class="fas fa-globe"></i></div>
            <h3><?php echo $total_courses; ?></h3>
            <small>Available Courses</small>
        </div>
    </div>
</div>

<!-- Recent Courses -->
<div class="form-card p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0"><i class="fas fa-play-circle me-2" style="color:var(--primary);"></i>My Recent Courses</h5>
        <a href="my-courses.php" class="btn btn-secondary btn-sm">View All</a>
    </div>
    <?php if ($recent && mysqli_num_rows($recent) > 0): ?>
    <div class="row g-3">
        <?php $i=0; while($course = mysqli_fetch_assoc($recent)):
            $color=$colors[$i%count($colors)]; $icon=$icons[$i%count($icons)]; $i++; ?>
        <div class="col-md-4">
            <div class="course-card">
                <div class="course-image" style="background:linear-gradient(135deg,<?php echo $color;?>,<?php echo $color;?>cc);height:120px;display:flex;align-items:center;justify-content:center;color:white;flex-direction:column;">
                    <i class="fas <?php echo $icon;?>" style="font-size:2.5rem;opacity:0.9;"></i>
                </div>
                <div class="course-body" style="padding:14px;">
                    <h6 class="course-title mb-1"><a href="course-details.php?id=<?php echo $course['id'];?>"><?php echo htmlspecialchars($course['title']);?></a></h6>
                    <small class="course-instructor"><i class="fas fa-user me-1"></i><?php echo htmlspecialchars($course['first_name'].' '.$course['last_name']);?></small>
                    <div class="d-grid mt-2"><a href="lesson.php?course_id=<?php echo $course['id'];?>" class="btn btn-primary btn-sm"><i class="fas fa-play me-1"></i>Continue</a></div>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    <?php else: ?>
    <div class="text-center py-4">
        <i class="fas fa-book-open" style="font-size:3rem;color:var(--text-muted);"></i>
        <p class="mt-2 text-muted">No courses enrolled yet.</p>
        <a href="courses.php" class="btn btn-primary btn-sm mt-1"><i class="fas fa-search me-1"></i>Browse Courses</a>
    </div>
    <?php endif; ?>
</div>

<!-- Quick Links -->
<div class="row g-3">
    <div class="col-md-3 col-6"><a href="profile.php" class="text-decoration-none"><div class="stat-card text-center p-3"><i class="fas fa-user" style="font-size:2rem;color:var(--primary);"></i><p class="mb-0 mt-2 fw-bold small">My Profile</p></div></a></div>
    <div class="col-md-3 col-6"><a href="my-courses.php" class="text-decoration-none"><div class="stat-card text-center p-3"><i class="fas fa-book" style="font-size:2rem;color:#10B981;"></i><p class="mb-0 mt-2 fw-bold small">My Courses</p></div></a></div>
    <div class="col-md-3 col-6"><a href="courses.php" class="text-decoration-none"><div class="stat-card text-center p-3"><i class="fas fa-search" style="font-size:2rem;color:#F59E0B;"></i><p class="mb-0 mt-2 fw-bold small">Browse</p></div></a></div>
    <div class="col-md-3 col-6"><a href="logout.php" class="text-decoration-none"><div class="stat-card text-center p-3"><i class="fas fa-sign-out-alt" style="font-size:2rem;color:#EF4444;"></i><p class="mb-0 mt-2 fw-bold small">Logout</p></div></a></div>
</div>

</div>
</div>
<?php require_once 'includes/footer.php'; ?>