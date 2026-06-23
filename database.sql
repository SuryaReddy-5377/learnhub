-- ============================================
-- LEARNHUB - E-Learning Portal Database
-- ============================================

CREATE DATABASE IF NOT EXISTS learnhub_db;
USE learnhub_db;

-- Users Table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'instructor', 'admin') DEFAULT 'student',
    profile_pic VARCHAR(255) DEFAULT 'default.png',
    phone VARCHAR(20),
    bio TEXT,
    is_verified BOOLEAN DEFAULT 0,
    otp VARCHAR(6),
    otp_expires DATETIME,
    reset_token VARCHAR(255),
    reset_expires DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Categories Table
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    icon VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Courses Table
CREATE TABLE courses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    instructor_id INT NOT NULL,
    category_id INT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) DEFAULT 0.00,
    image VARCHAR(255) DEFAULT 'default-course.jpg',
    video_url VARCHAR(255),
    lessons_count INT DEFAULT 0,
    duration INT DEFAULT 0,
    level ENUM('beginner', 'intermediate', 'advanced') DEFAULT 'beginner',
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    rating DECIMAL(3,2) DEFAULT 0,
    enrolled_students INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (instructor_id) REFERENCES users(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Lessons Table
CREATE TABLE lessons (
    id INT PRIMARY KEY AUTO_INCREMENT,
    course_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    video_url VARCHAR(255),
    duration INT DEFAULT 0,
    order_number INT DEFAULT 0,
    is_preview BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

-- Enrollments Table
CREATE TABLE enrollments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    course_id INT NOT NULL,
    status ENUM('active', 'completed', 'cancelled') DEFAULT 'active',
    progress INT DEFAULT 0,
    enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    UNIQUE KEY unique_enrollment (user_id, course_id)
);

-- Quiz Results Table
CREATE TABLE quiz_results (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    course_id INT NOT NULL,
    score INT DEFAULT 0,
    total_questions INT DEFAULT 0,
    passed BOOLEAN DEFAULT 0,
    completed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

-- Insert Admin (password: Admin@123)
INSERT INTO users (first_name, last_name, email, password, role, is_verified) 
VALUES ('Admin', 'LearnHub', 'admin@learnhub.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1);

-- Insert Instructor (password: Instructor@123)
INSERT INTO users (first_name, last_name, email, password, role, is_verified) 
VALUES ('John', 'Instructor', 'instructor@learnhub.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'instructor', 1);

-- Insert Categories
INSERT INTO categories (name, description, icon) VALUES
('Web Development', 'Learn HTML, CSS, JavaScript, PHP and more', 'fa-code'),
('Data Science', 'Python, Machine Learning, AI, Data Analysis', 'fa-chart-bar'),
('Mobile Development', 'Android, iOS, React Native, Flutter', 'fa-mobile-alt'),
('Cloud Computing', 'AWS, Azure, Google Cloud, DevOps', 'fa-cloud'),
('Design', 'UI/UX, Graphic Design, Figma, Adobe XD', 'fa-paint-brush'),
('Business', 'Marketing, Finance, Entrepreneurship, Leadership', 'fa-briefcase');

-- Insert Sample Courses
INSERT INTO courses (instructor_id, category_id, title, description, price, level, status, rating, enrolled_students) VALUES
(2, 1, 'Complete Web Development Bootcamp', 'Learn full-stack web development from scratch', 49.99, 'beginner', 'published', 4.8, 150),
(2, 1, 'Advanced JavaScript Mastery', 'Deep dive into JavaScript concepts and frameworks', 39.99, 'intermediate', 'published', 4.6, 89),
(2, 2, 'Python for Data Science', 'Learn Python and data analysis libraries', 44.99, 'beginner', 'published', 4.7, 112),
(2, 3, 'Android App Development', 'Build Android apps with Java and Kotlin', 54.99, 'intermediate', 'published', 4.5, 67),
(2, 4, 'AWS Cloud Practitioner', 'Complete guide to Amazon Web Services', 59.99, 'beginner', 'published', 4.4, 45);

-- Insert Sample Lessons
INSERT INTO lessons (course_id, title, description, duration, order_number) VALUES
(1, 'Introduction to Web Development', 'Overview of web technologies', 15, 1),
(1, 'HTML Basics', 'Learn HTML structure and tags', 30, 2),
(1, 'CSS Fundamentals', 'Styling websites with CSS', 35, 3),
(1, 'JavaScript Essentials', 'JavaScript programming basics', 40, 4);

SELECT '✅ Database Setup Complete!' as 'Status';