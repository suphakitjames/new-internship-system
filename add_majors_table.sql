-- Add majors table to existing database
USE internship_system;

-- Create majors table if not exists
CREATE TABLE IF NOT EXISTS majors (
    id VARCHAR(10) PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    faculty VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample majors data
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
('COMM', 'นิเทศศาสตร์', 'คณะนิเทศศาสตร์')
ON DUPLICATE KEY UPDATE name=VALUES(name), faculty=VALUES(faculty);

