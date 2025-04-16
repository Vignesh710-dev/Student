-- Create database
CREATE DATABASE IF NOT EXISTS scholar_db;
USE scholar_db;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'instructor', 'admin') NOT NULL DEFAULT 'student',
    image VARCHAR(255) DEFAULT 'assets/images/default-user.jpg',
    specialty VARCHAR(100) NULL,
    bio TEXT NULL,
    facebook VARCHAR(255) NULL,
    twitter VARCHAR(255) NULL,
    linkedin VARCHAR(255) NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Courses table
CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    category_id INT NOT NULL,
    instructor_id INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255) DEFAULT 'assets/images/default-course.jpg',
    duration VARCHAR(50) NOT NULL,
    level ENUM('beginner', 'intermediate', 'advanced') NOT NULL,
    status ENUM('active', 'inactive', 'draft') DEFAULT 'active',
    enrollment_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id),
    FOREIGN KEY (instructor_id) REFERENCES users(id)
);

-- Course ratings table
CREATE TABLE IF NOT EXISTS course_ratings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    user_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    review TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    UNIQUE KEY (course_id, user_id)
);

-- Enrollments table
CREATE TABLE IF NOT EXISTS enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    user_id INT NOT NULL,
    enrollment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completion_date TIMESTAMP NULL,
    progress INT DEFAULT 0,
    status ENUM('active', 'completed', 'dropped') DEFAULT 'active',
    FOREIGN KEY (course_id) REFERENCES courses(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    UNIQUE KEY (course_id, user_id)
);

-- Events table
CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    event_date DATE NOT NULL,
    time VARCHAR(50) NOT NULL,
    location VARCHAR(255) NOT NULL,
    image VARCHAR(255) DEFAULT 'assets/images/default-event.jpg',
    organizer_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (organizer_id) REFERENCES users(id)
);

-- Contact messages table
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('new', 'read', 'replied') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- FAQ questions table
CREATE TABLE IF NOT EXISTS faq_questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    question TEXT NOT NULL,
    answer TEXT NULL,
    status ENUM('pending', 'answered', 'published') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    answered_at TIMESTAMP NULL
);

-- Activity logs table
CREATE TABLE IF NOT EXISTS activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    activity_type VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    ip_address VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Active users tracking table
CREATE TABLE IF NOT EXISTS active_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(255) NOT NULL UNIQUE,
    user_id INT NOT NULL DEFAULT 0,
    ip_address VARCHAR(50) NOT NULL,
    last_activity INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- User course views for recommendation system
CREATE TABLE IF NOT EXISTS user_course_views (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    course_id INT NOT NULL,
    view_count INT NOT NULL DEFAULT 1,
    last_viewed TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (course_id) REFERENCES courses(id),
    UNIQUE KEY (user_id, course_id)
);

-- AI questions log
CREATE TABLE IF NOT EXISTS ai_questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    question TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Course lessons table
CREATE TABLE IF NOT EXISTS lessons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    content TEXT NOT NULL,
    duration INT NOT NULL COMMENT 'Duration in minutes',
    order_number INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id)
);

-- User lesson progress
CREATE TABLE IF NOT EXISTS lesson_progress (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    lesson_id INT NOT NULL,
    status ENUM('not_started', 'in_progress', 'completed') DEFAULT 'not_started',
    progress_percentage INT DEFAULT 0,
    last_accessed TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (lesson_id) REFERENCES lessons(id),
    UNIQUE KEY (user_id, lesson_id)
);

-- Course quizzes
CREATE TABLE IF NOT EXISTS quizzes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    lesson_id INT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    time_limit INT NULL COMMENT 'Time limit in minutes, NULL for no limit',
    passing_score INT NOT NULL DEFAULT 70 COMMENT 'Passing score percentage',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id),
    FOREIGN KEY (lesson_id) REFERENCES lessons(id)
);

-- Quiz questions
CREATE TABLE IF NOT EXISTS quiz_questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT NOT NULL,
    question TEXT NOT NULL,
    question_type ENUM('multiple_choice', 'true_false', 'short_answer') NOT NULL,
    points INT NOT NULL DEFAULT 1,
    order_number INT NOT NULL,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id)
);

-- Quiz question options (for multiple choice)
CREATE TABLE IF NOT EXISTS quiz_question_options (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_id INT NOT NULL,
    option_text TEXT NOT NULL,
    is_correct BOOLEAN NOT NULL DEFAULT FALSE,
    order_number INT NOT NULL,
    FOREIGN KEY (question_id) REFERENCES quiz_questions(id)
);

-- User quiz attempts
CREATE TABLE IF NOT EXISTS quiz_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    quiz_id INT NOT NULL,
    score INT NOT NULL,
    passed BOOLEAN NOT NULL,
    time_spent INT NOT NULL COMMENT 'Time spent in seconds',
    started_at TIMESTAMP NOT NULL,
    completed_at TIMESTAMP NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id)
);

-- User quiz answers
CREATE TABLE IF NOT EXISTS quiz_answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    attempt_id INT NOT NULL,
    question_id INT NOT NULL,
    answer TEXT NOT NULL,
    is_correct BOOLEAN NOT NULL,
    points_earned INT NOT NULL,
    FOREIGN KEY (attempt_id) REFERENCES quiz_attempts(id),
    FOREIGN KEY (question_id) REFERENCES quiz_questions(id)
);

-- Course certificates
CREATE TABLE IF NOT EXISTS certificates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    course_id INT NOT NULL,
    certificate_number VARCHAR(50) NOT NULL UNIQUE,
    issue_date TIMESTAMP NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (course_id) REFERENCES courses(id),
    UNIQUE KEY (user_id, course_id)
);

-- Course materials
CREATE TABLE IF NOT EXISTS course_materials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    lesson_id INT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_type VARCHAR(50) NOT NULL,
    file_size INT NOT NULL COMMENT 'File size in KB',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id),
    FOREIGN KEY (lesson_id) REFERENCES lessons(id)
);

-- Course discussions
CREATE TABLE IF NOT EXISTS discussions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Discussion replies
CREATE TABLE IF NOT EXISTS discussion_replies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    discussion_id INT NOT NULL,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (discussion_id) REFERENCES discussions(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Insert sample data for testing
-- Categories
INSERT INTO categories (name, description) VALUES
('Web Development', 'Learn to build and maintain websites and web applications'),
('Data Science', 'Learn to analyze and interpret complex data'),
('Business', 'Develop business skills and knowledge'),
('Design', 'Learn graphic design, UI/UX, and more'),
('Marketing', 'Learn digital marketing strategies and techniques');

-- Sample admin user
INSERT INTO users (name, email, password, role, specialty, bio) VALUES
('Admin User', 'admin@scholar.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NULL, 'System administrator');

-- Sample instructors
INSERT INTO users (name, email, password, role, specialty, bio, facebook, twitter, linkedin) VALUES
('John Smith', 'john@scholar.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'instructor', 'Web Development', 'Experienced web developer with 10+ years in the industry', 'https://facebook.com/johnsmith', 'https://twitter.com/johnsmith', 'https://linkedin.com/in/johnsmith'),
('Sarah Johnson', 'sarah@scholar.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'instructor', 'Data Science', 'Data scientist with expertise in machine learning and AI', 'https://facebook.com/sarahjohnson', 'https://twitter.com/sarahjohnson', 'https://linkedin.com/in/sarahjohnson'),
('Michael Brown', 'michael@scholar.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'instructor', 'Business', 'Business consultant and entrepreneur', 'https://facebook.com/michaelbrown', 'https://twitter.com/michaelbrown', 'https://linkedin.com/in/michaelbrown'),
('Emily Davis', 'emily@scholar.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'instructor', 'Design', 'UI/UX designer with a passion for creating beautiful interfaces', 'https://facebook.com/emilydavis', 'https://twitter.com/emilydavis', 'https://linkedin.com/in/emilydavis');

-- Sample courses
INSERT INTO courses (title, description, category_id, instructor_id, price, duration, level, enrollment_count) VALUES
('Complete Web Development Bootcamp', 'Learn HTML, CSS, JavaScript, React, Node.js, and more to become a full-stack web developer', 1, 2, 99.99, '12 weeks', 'beginner', 245),
('Python for Data Science', 'Master Python programming for data analysis and visualization', 2, 3, 79.99, '8 weeks', 'intermediate', 189),
('Business Strategy Fundamentals', 'Learn the core principles of business strategy and management', 3, 4, 59.99, '6 weeks', 'beginner', 156),
('UI/UX Design Principles', 'Create beautiful and functional user interfaces', 4, 5, 89.99, '10 weeks', 'intermediate', 132),
('Digital Marketing Essentials', 'Learn SEO, social media marketing, and content strategy', 5, 4, 69.99, '8 weeks', 'beginner', 178),
('Advanced JavaScript', 'Take your JavaScript skills to the next level', 1, 2, 109.99, '10 weeks', 'advanced', 98);

-- Sample events
INSERT INTO events (title, description, event_date, time, location, organizer_id) VALUES
('Web Development Workshop', 'Hands-on workshop to build your first website', DATE_ADD(CURDATE(), INTERVAL 7 DAY), '10:00 AM - 2:00 PM', 'Online (Zoom)', 2),
('Data Science Seminar', 'Learn about the latest trends in data science', DATE_ADD(CURDATE(), INTERVAL 14 DAY), '3:00 PM - 5:00 PM', 'Scholar Campus, Room 101', 3),
('Business Networking Event', 'Connect with professionals in your industry', DATE_ADD(CURDATE(), INTERVAL 21 DAY), '6:00 PM - 8:00 PM', 'Downtown Conference Center', 4);