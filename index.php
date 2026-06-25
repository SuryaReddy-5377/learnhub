<?php
$page_title = 'Home';
require_once 'config/database.php';
require_once 'includes/header.php';

$total_courses  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM courses WHERE status='published'"))['c'];
$total_students = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM users WHERE role='student'"))['c'];
$total_enroll   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM enrollments WHERE status='active'"))['c'];
$categories     = mysqli_query($conn, "SELECT * FROM categories LIMIT 8");
$courses        = mysqli_query($conn, "SELECT c.*, u.first_name, u.last_name, cat.name as category_name,
    (SELECT COUNT(*) FROM enrollments WHERE course_id=c.id AND status='active') as enrolled_count
    FROM courses c JOIN users u ON c.instructor_id=u.id JOIN categories cat ON c.category_id=cat.id
    WHERE c.status='published' ORDER BY c.created_at DESC LIMIT 6");

$colors = ['#6C3CE1','#EC4899','#10B981','#F59E0B','#3B82F6','#EF4444','#8B5CF6','#34D399','#F472B6','#FBBF24'];
$icons  = ['fa-code','fa-chart-bar','fa-mobile-alt','fa-cloud','fa-paint-brush','fa-briefcase','fa-robot','fa-shield-alt','fa-gamepad','fa-tasks'];
?>

<div class="main-content">

<!-- HERO -->
<div class="hero-banner" data-aos="fade-up">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <h1 class="hero-title">Learn <span class="gradient-text">Anything</span> From Anywhere</h1>
                <p class="hero-text">Join thousands of students learning new skills and advancing their careers with LearnHub.</p>
                <div class="d-flex flex-wrap gap-3 mt-4">
                    <a href="courses.php" class="btn btn-primary btn-lg"><i class="fas fa-rocket me-2"></i>Explore Courses</a>
                    <?php if (!isLoggedIn()): ?>
                        <a href="register.php" class="btn btn-secondary btn-lg"><i class="fas fa-user-plus me-2"></i>Get Started Free</a>
                    <?php else: ?>
                        <a href="dashboard.php" class="btn btn-secondary btn-lg"><i class="fas fa-th-large me-2"></i>My Dashboard</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-6 text-center d-none d-lg-block" data-aos="fade-left">
                <i class="fas fa-graduation-cap floating-icon" style="font-size:9rem; color:var(--primary);"></i>
            </div>
        </div>
    </div>
</div>

<!-- STATS -->
<div class="container py-4">
    <div class="row g-4">
        <div class="col-md-4"><div class="stat-card text-center"><div class="stat-icon"><i class="fas fa-book-open"></i></div><h3><?php echo $total_courses; ?>+</h3><small>Courses Available</small></div></div>
        <div class="col-md-4"><div class="stat-card text-center"><div class="stat-icon"><i class="fas fa-users"></i></div><h3><?php echo $total_students; ?>+</h3><small>Students Enrolled</small></div></div>
        <div class="col-md-4"><div class="stat-card text-center"><div class="stat-icon"><i class="fas fa-certificate"></i></div><h3><?php echo $total_enroll; ?>+</h3><small>Enrollments Made</small></div></div>
    </div>
</div>

<!-- CATEGORIES -->
<div class="container py-4">
    <div class="text-center mb-4"><h2 class="fw-bold">Explore <span class="gradient-text">Categories</span></h2><p class="text-muted">Find the perfect course for your career</p></div>
    <div class="row g-3">
    <?php $ci=0; $cat_icons=['fa-code','fa-paint-brush','fa-chart-bar','fa-mobile-alt','fa-cloud','fa-briefcase','fa-robot','fa-shield-alt'];
    while ($cat = mysqli_fetch_assoc($categories)):
        $ci_color = $colors[$ci % count($colors)]; $ci_icon = $cat_icons[$ci % count($cat_icons)]; $ci++;
    ?>
        <div class="col-6 col-md-3">
            <a href="courses.php?category=<?php echo $cat['id']; ?>" class="text-decoration-none">
                <div class="category-card text-center">
                    <i class="fas <?php echo $cat['icon'] ?? $ci_icon; ?>" style="font-size:2.5rem; color:<?php echo $ci_color; ?>; display:block; margin-bottom:10px;"></i>
                    <h6><?php echo htmlspecialchars($cat['name']); ?></h6>
                </div>
            </a>
        </div>
    <?php endwhile; ?>
    </div>
</div>

<!-- FEATURED COURSES -->
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="fas fa-star me-2"></i>Featured <span class="gradient-text">Courses</span></h2>
        <a href="courses.php" class="btn btn-secondary">View All <i class="fas fa-arrow-right ms-1"></i></a>
    </div>
    <?php if ($courses && mysqli_num_rows($courses) > 0): ?>
    <div class="row g-4">
        <?php $i=0; while ($course = mysqli_fetch_assoc($courses)):
            $color = $colors[$i % count($colors)]; $icon = $icons[$i % count($icons)]; $i++;
        ?>
        <div class="col-lg-4 col-md-6">
            <div class="course-card">
                <div class="course-image" style="background:linear-gradient(135deg,<?php echo $color; ?>,<?php echo $color; ?>cc); height:200px; display:flex; align-items:center; justify-content:center; position:relative; flex-direction:column; color:white;">
                    <i class="fas <?php echo $icon; ?>" style="font-size:4rem; opacity:0.9; margin-bottom:8px;"></i>
                    <span style="font-size:0.75rem; opacity:0.8; text-transform:uppercase; letter-spacing:1px;"><?php echo htmlspecialchars($course['category_name'] ?? ''); ?></span>
                    <span class="course-level"><?php echo ucfirst($course['level']); ?></span>
                    <?php if ($course['price'] == 0): ?><span class="course-badge free">Free</span><?php endif; ?>
                </div>
                <div class="course-body">
                    <h5 class="course-title"><a href="course-details.php?id=<?php echo $course['id']; ?>"><?php echo htmlspecialchars($course['title']); ?></a></h5>
                    <p class="course-instructor"><i class="fas fa-user me-1"></i><?php echo htmlspecialchars($course['first_name'].' '.$course['last_name']); ?></p>
                    <div class="course-meta">
                        <span class="course-price"><?php echo $course['price']>0 ? '₹'.number_format($course['price'],2) : 'Free'; ?></span>
                        <span class="course-rating"><i class="fas fa-star"></i> <?php echo number_format($course['rating']??0,1); ?> <span class="text-muted">(<?php echo $course['enrolled_count']; ?>)</span></span>
                    </div>
                    <div class="d-grid mt-3"><a href="course-details.php?id=<?php echo $course['id']; ?>" class="btn btn-primary btn-sm"><i class="fas fa-eye me-1"></i>View Course</a></div>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    <?php else: ?>
    <div class="text-center py-5"><i class="fas fa-book-open" style="font-size:4rem; color:var(--text-muted);"></i><h4 class="mt-3">No courses yet</h4><p class="text-muted">Add courses from the admin panel.</p></div>
    <?php endif; ?>
</div>

<!-- CTA -->
<?php if (!isLoggedIn()): ?>
<div class="container py-5">
    <div class="form-card text-center p-5">
        <h2 class="fw-bold mb-3">Ready to Start Learning? 🎯</h2>
        <p class="text-muted mb-4">Join thousands of students already learning on LearnHub.</p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="register.php" class="btn btn-primary btn-lg"><i class="fas fa-user-plus me-2"></i>Get Started Free</a>
            <a href="courses.php" class="btn btn-secondary btn-lg"><i class="fas fa-search me-2"></i>Browse Courses</a>
        </div>
    </div>
</div>
<?php endif; ?>

</div>
<?php require_once 'includes/footer.php'; ?>