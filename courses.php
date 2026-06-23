<?php
$page_title = 'All Courses';
require_once 'config/database.php';
require_once 'includes/header.php';

// Get filters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? intval($_GET['category']) : 0;
$level = isset($_GET['level']) ? $_GET['level'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

// Build query
$where = "WHERE c.status = 'published'";
$params = [];
$types = "";

if ($search) {
    $where .= " AND (c.title LIKE ? OR c.description LIKE ?)";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= "ss";
}

if ($category > 0) {
    $where .= " AND c.category_id = ?";
    $params[] = $category;
    $types .= "i";
}

if ($level) {
    $where .= " AND c.level = ?";
    $params[] = $level;
    $types .= "s";
}

$order = "ORDER BY c.created_at DESC";
if ($sort === 'popular') {
    $order = "ORDER BY c.enrolled_students DESC";
} elseif ($sort === 'rating') {
    $order = "ORDER BY c.rating DESC";
} elseif ($sort === 'price_low') {
    $order = "ORDER BY c.price ASC";
} elseif ($sort === 'price_high') {
    $order = "ORDER BY c.price DESC";
}

// Get courses
$query = "SELECT c.*, u.first_name, u.last_name, cat.name as category_name,
          (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id AND status = 'active') as enrolled_count
          FROM courses c
          JOIN users u ON c.instructor_id = u.id
          JOIN categories cat ON c.category_id = cat.id
          $where $order";
$stmt = mysqli_prepare($conn, $query);
if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$courses = mysqli_stmt_get_result($stmt);
mysqli_stmt_close($stmt);

// Get categories for filter
$categories = mysqli_query($conn, "SELECT * FROM categories ORDER BY name");
?>

<!-- Hero Section -->
<section class="hero-banner py-4" data-aos="fade-up">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8" data-aos="fade-right">
                <h1 class="hero-title" style="font-size: 2.5rem;">Explore Our <span class="gradient-text">Courses</span></h1>
                <p class="hero-text" style="font-size: 1.1rem;">Find the perfect course to advance your skills and career.</p>
            </div>
        </div>
    </div>
</section>

<!-- Filters Section -->
<section data-aos="fade-up">
    <div class="container">
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="search-bar">
                    <form action="courses.php" method="GET" class="d-flex">
                        <input type="text" name="search" class="form-control" placeholder="Search courses..." 
                               value="<?php echo htmlspecialchars($search); ?>">
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select" onchange="window.location.href=this.value">
                    <option value="courses.php">All Categories</option>
                    <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
                        <option value="courses.php?category=<?php echo $cat['id']; ?>" 
                                <?php echo $category == $cat['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-2">
                <select name="level" class="form-select" onchange="window.location.href=this.value">
                    <option value="courses.php">All Levels</option>
                    <option value="courses.php?level=beginner" <?php echo $level === 'beginner' ? 'selected' : ''; ?>>Beginner</option>
                    <option value="courses.php?level=intermediate" <?php echo $level === 'intermediate' ? 'selected' : ''; ?>>Intermediate</option>
                    <option value="courses.php?level=advanced" <?php echo $level === 'advanced' ? 'selected' : ''; ?>>Advanced</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="sort" class="form-select" onchange="window.location.href=this.value">
                    <option value="courses.php?sort=newest" <?php echo $sort === 'newest' ? 'selected' : ''; ?>>Newest</option>
                    <option value="courses.php?sort=popular" <?php echo $sort === 'popular' ? 'selected' : ''; ?>>Most Popular</option>
                    <option value="courses.php?sort=rating" <?php echo $sort === 'rating' ? 'selected' : ''; ?>>Highest Rated</option>
                    <option value="courses.php?sort=price_low" <?php echo $sort === 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
                    <option value="courses.php?sort=price_high" <?php echo $sort === 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
                </select>
            </div>
        </div>
    </div>
</section>

<!-- Courses Grid -->
<section data-aos="fade-up">
    <div class="container">
        <?php if (mysqli_num_rows($courses) > 0): ?>
            <div class="row g-4">
                <?php while ($course = mysqli_fetch_assoc($courses)): ?>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="course-card">
                        <div class="course-image">
                            <img src="assets/uploads/courses/<?php echo $course['image'] ?? 'default-course.jpg'; ?>" 
                                 alt="<?php echo htmlspecialchars($course['title']); ?>">
                            <span class="course-level"><?php echo ucfirst($course['level']); ?></span>
                            <?php if ($course['price'] == 0): ?>
                                <span class="course-badge free">Free</span>
                            <?php endif; ?>
                        </div>
                        <div class="course-body">
                            <h5 class="course-title">
                                <a href="course-details.php?id=<?php echo $course['id']; ?>">
                                    <?php echo htmlspecialchars($course['title']); ?>
                                </a>
                            </h5>
                            <p class="course-instructor">
                                <i class="fas fa-user me-1"></i>
                                <?php echo htmlspecialchars($course['first_name'] . ' ' . $course['last_name']); ?>
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
                                    <span class="text-muted">(<?php echo $course['enrolled_count']; ?>)</span>
                                </span>
                            </div>
                            <div class="d-grid gap-2">
                                <a href="course-details.php?id=<?php echo $course['id']; ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i>View Course
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-book-open" style="font-size: 4rem; color: var(--text-muted);"></i>
                <h3 class="mt-3">No Courses Found</h3>
                <p class="text-muted">Try adjusting your search or filters.</p>
                <a href="courses.php" class="btn btn-primary">View All Courses</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>