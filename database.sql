-- database.sql

CREATE DATABASE IF NOT EXISTS internship_system;
USE internship_system;

-- Users Table (All actors)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'admin', 'employer', 'advisor') NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20),
    profile_image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- Majors Table
CREATE TABLE majors (
    id VARCHAR(10) PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    faculty VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Students Table (Extended profile)
CREATE TABLE students (
    user_id INT PRIMARY KEY,
    student_id VARCHAR(20) NOT NULL UNIQUE,
    major VARCHAR(100),
    year_level INT,
    gpa DECIMAL(3, 2),
    address TEXT,
    advisor_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (advisor_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Companies/Internship Places
CREATE TABLE companies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    address TEXT,
    province VARCHAR(100),
    contact_person VARCHAR(100),
    phone VARCHAR(20),
    email VARCHAR(100),
    description TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_by INT, -- Student who added it (if applicable)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Internship Requests
CREATE TABLE internship_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    company_id INT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    start_date DATE,
    end_date DATE,
    admin_comment TEXT,
    FOREIGN KEY (student_id) REFERENCES students(user_id) ON DELETE CASCADE,
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE
);

-- Daily Logs
CREATE TABLE daily_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    log_date DATE NOT NULL,
    work_description TEXT NOT NULL,
    problems TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(user_id) ON DELETE CASCADE
);

-- Time Sheets
CREATE TABLE time_sheets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    date DATE NOT NULL,
    time_in TIME,
    time_out TIME,
    notes TEXT,
    FOREIGN KEY (student_id) REFERENCES students(user_id) ON DELETE CASCADE
);

-- Evaluations (Employer -> Student)
CREATE TABLE evaluations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    employer_id INT NOT NULL,
    score INT, -- Out of 100 or similar
    comments TEXT,
    evaluation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(user_id) ON DELETE CASCADE,
    FOREIGN KEY (employer_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Feedbacks (Student -> Company)
CREATE TABLE feedbacks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    company_id INT NOT NULL,
    rating INT, -- 1-5
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(user_id) ON DELETE CASCADE,
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE
);

-- Advisor Messages ("เล่าสู่กันฟัง")
CREATE TABLE advisor_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    advisor_id INT, -- Can be specific or general
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(user_id) ON DELETE CASCADE,
    FOREIGN KEY (advisor_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Seed Data (Admin)
INSERT INTO users (username, password, role, full_name) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'System Administrator'); 
-- Password is 'password' (hashed)

-- Seed Data (Majors)
INSERT INTO majors (id, name, faculty) VALUES
('CS', 'วิทยาการคอมพิวเตอร์', 'คณะวิทยาศาสตร์'),
('IT', 'เทคโนโลยีสารสนเทศ', 'คณะวิทยาศาสตร์'),
('CE', 'วิศวกรรมคอมพิวเตอร์', 'คณะวิศวกรรมศาสตร์'),
('EE', 'วิศวกรรมไฟฟ้า', 'คณะวิศวกรรมศาสตร์'),
('ME', 'วิศวกรรมเครื่องกล', 'คณะวิศวกรรมศาสตร์'),
('BA', 'บริหารธุรกิจ', 'คณะบริหารธุรกิจ'),
('ACC', 'การบัญชี', 'คณะบริหารธุรกิจ'),
('MKT', 'การตลาด', 'คณะบริหารธุรกิจ'),
('ECON', 'เศรษฐศาสตร์', 'คณะเศรษฐศาสตร์'),
('COMM', 'นิเทศศาสตร์', 'คณะนิเทศศาสตร์');
