<?php
$page_title = 'Add Course';
require_once '../config/database.php';
require_once 'admin-header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

$error = '';
$success = '';

// Get instructors
$instructors = mysqli_query($conn, "SELECT id, first_name, last_name FROM users WHERE role = 'instructor'");
$categories = mysqli_query($conn, "SELECT * FROM categories");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $level = $_POST['level'];
    $category_id = intval($_POST['category_id']);
    $instructor_id = intval($_POST['instructor_id']);
    $status = $_POST['status'];
    
    if (empty($title) || empty($description)) {
        $error = 'Title and description are required!';
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO courses (instructor_id, category_id, title, description, price, level, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "iissdss", $instructor_id, $category_id, $title, $description, $price, $level, $status);
        
        if (mysqli_stmt_execute($stmt)) {
            $success = 'Course added successfully!';
        } else {
            $error = 'Failed to add course!';
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <h2 class="fw-bold"><i class="fas fa-plus me-2"></i>Add New Course</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <div class="card form-card mt-3">
            <div class="card-body p-4">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label">Course Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Price (₹)</label>
                            <input type="number" name="price" class="form-control" step="0.01" value="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Level</label>
                            <select name="level" class="form-control">
                                <option value="beginner">Beginner</option>
                                <option value="intermediate">Intermediate</option>
                                <option value="advanced">Advanced</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Category</label>
                            <select name="category_id" class="form-control">
                                <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
                                    <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Instructor</label>
                            <select name="instructor_id" class="form-control">
                                <?php while ($inst = mysqli_fetch_assoc($instructors)): ?>
                                    <option value="<?php echo $inst['id']; ?>">
                                        <?php echo htmlspecialchars($inst['first_name'] . ' ' . $inst['last_name']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="published">Published</option>
                            <option value="draft">Draft</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-2"></i>Add Course
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'admin-footer.php'; ?>