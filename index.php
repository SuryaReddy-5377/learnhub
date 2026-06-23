<?php
$page_title = 'Home';
require_once 'config/database.php';
require_once 'includes/header.php';
?>

<!-- Main Content Wrapper -->
<div class="main-content">
    <div class="hero-banner" data-aos="fade-up">
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <h1 class="hero-title">Learn <span class="gradient-text">Anything</span> From Anywhere</h1>
                <p class="hero-text">Join thousands of students learning new skills and advancing their careers with LearnHub.</p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="courses.php" class="btn btn-primary">
                        <i class="fas fa-rocket me-2"></i>Explore Courses
                    </a>
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <a href="register.php" class="btn btn-secondary">
                            <i class="fas fa-user-plus me-2"></i>Get Started
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block" data-aos="fade-left">
                <div class="text-center">
                    <i class="fas fa-graduation-cap floating-icon" style="font-size: 8rem; color: var(--primary);"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories -->
    <div class="row mt-5" data-aos="fade-up">
        <div class="col-12 text-center">
            <h2 class="fw-bold">Explore Categories</h2>
            <p class="text-muted">Find the perfect course for your career</p>
        </div>
        <?php 
        $categories = mysqli_query($conn, "SELECT * FROM categories LIMIT 6");
        if (mysqli_num_rows($categories) > 0): 
        ?>
            <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
            <div class="col-lg-2 col-md-4 col-sm-6 mt-3">
                <a href="courses.php?category=<?php echo $cat['id']; ?>" class="text-decoration-none">
                    <div class="category-card text-center">
                        <i class="fas <?php echo $cat['icon'] ?? 'fa-book'; ?>" style="font-size: 2.5rem; color: var(--primary);"></i>
                        <h6 class="mt-2"><?php echo htmlspecialchars($cat['name']); ?></h6>
                    </div>
                </a>
            </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>

    <!-- Featured Courses -->
    <div class="row mt-5" data-aos="fade-up">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="fw-bold"><i class="fas fa-star me-2"></i>Featured Courses</h2>
                <a href="courses.php" class="btn btn-outline-primary">View All</a>
            </div>
        </div>
        <?php 
        $courses = mysqli_query($conn, "SELECT * FROM courses WHERE status = 'published' LIMIT 6");
        if (mysqli_num_rows($courses) > 0): 
        ?>
            <?php while ($course = mysqli_fetch_assoc($courses)): ?>
            <div class="col-lg-4 col-md-6 mt-4" data-aos="fade-up" data-aos-delay="100">
                <div class="course-card">
                    <div class="course-image">
                        <img src="assets/uploads/courses/<?php echo $course['image'] ?? 'default-course.jpg'; ?>" 
                             alt="<?php echo htmlspecialchars($course['title']); ?>">
                        <span class="course-level"><?php echo ucfirst($course['level']); ?></span>
                    </div>
                    <div class="course-body">
                        <h5 class="course-title">
                            <a href="course-details.php?id=<?php echo $course['id']; ?>">
                                <?php echo htmlspecialchars($course['title']); ?>
                            </a>
                        </h5>
                        <p class="course-instructor">
                            <i class="fas fa-user me-1"></i>Instructor
                        </p>
                        <div class="course-meta">
                            <span class="course-price">
                                <?php if ($course['price'] > 0): ?>
                                    ₹<?php echo number_format($course['price'], 2); ?>
                                <?php else: ?>
                                    Free
                                <?php endif; ?>
                            </span>
                            <span class="course-rating">
                                <i class="fas fa-star"></i> <?php echo number_format($course['rating'] ?? 0, 1); ?>
                            </span>
                        </div>
                        <a href="course-details.php?id=<?php echo $course['id']; ?>" class="btn btn-primary btn-sm w-100 mt-2">
                            View Course
                        </a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center mt-4">
                <p class="text-muted">No courses available yet.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>