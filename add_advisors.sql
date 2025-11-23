-- SQL for adding Majors and Advisors (10 per major)
USE internship_system;

-- 1. Ensure Majors Table Exists and has Data
CREATE TABLE IF NOT EXISTS majors (
    id VARCHAR(10) PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    faculty VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

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

-- 2. Create Advisors Table
CREATE TABLE IF NOT EXISTS advisors (
    user_id INT PRIMARY KEY,
    major_id VARCHAR(10),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (major_id) REFERENCES majors(id) ON DELETE SET NULL
);

-- 3. Insert Advisors (10 per major)
-- Password is 'password' ($2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi)

-- CS Advisors
INSERT INTO users (username, password, role, full_name, email) VALUES
('advisor_cs_1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สมชาย ใจดี (CS)', 'advisor.cs.1@uni.ac.th'),
('advisor_cs_2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วิไล รักเรียน (CS)', 'advisor.cs.2@uni.ac.th'),
('advisor_cs_3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สุชาติ มีสุข (CS)', 'advisor.cs.3@uni.ac.th'),
('advisor_cs_4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.กานดา สุขใจ (CS)', 'advisor.cs.4@uni.ac.th'),
('advisor_cs_5', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ณัฐพล มั่นคง (CS)', 'advisor.cs.5@uni.ac.th'),
('advisor_cs_6', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ปวีณา เจริญ (CS)', 'advisor.cs.6@uni.ac.th'),
('advisor_cs_7', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ธนพล รุ่งเรือง (CS)', 'advisor.cs.7@uni.ac.th'),
('advisor_cs_8', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.นภา สมบูรณ์ (CS)', 'advisor.cs.8@uni.ac.th'),
('advisor_cs_9', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ชัย ยอดเยี่ยม (CS)', 'advisor.cs.9@uni.ac.th'),
('advisor_cs_10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ชลธิชา ดีเลิศ (CS)', 'advisor.cs.10@uni.ac.th');

INSERT INTO advisors (user_id, major_id) 
SELECT id, 'CS' FROM users WHERE username LIKE 'advisor_cs_%' AND id NOT IN (SELECT user_id FROM advisors);

-- IT Advisors
INSERT INTO users (username, password, role, full_name, email) VALUES
('advisor_it_1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.กิตติ เก่งกล้า (IT)', 'advisor.it.1@uni.ac.th'),
('advisor_it_2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.มณี สามารถ (IT)', 'advisor.it.2@uni.ac.th'),
('advisor_it_3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วิชัย วิเศษ (IT)', 'advisor.it.3@uni.ac.th'),
('advisor_it_4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.รัตนา เลิศล้ำ (IT)', 'advisor.it.4@uni.ac.th'),
('advisor_it_5', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สมศักดิ์ ประเสริฐ (IT)', 'advisor.it.5@uni.ac.th'),
('advisor_it_6', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.อารี มั่งคั่ง (IT)', 'advisor.it.6@uni.ac.th'),
('advisor_it_7', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.บุญมี ร่ำรวย (IT)', 'advisor.it.7@uni.ac.th'),
('advisor_it_8', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.จินตนา ศรีสุข (IT)', 'advisor.it.8@uni.ac.th'),
('advisor_it_9', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ประดิษฐ์ มีชัย (IT)', 'advisor.it.9@uni.ac.th'),
('advisor_it_10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วรรณา ชนะ (IT)', 'advisor.it.10@uni.ac.th');

INSERT INTO advisors (user_id, major_id) 
SELECT id, 'IT' FROM users WHERE username LIKE 'advisor_it_%' AND id NOT IN (SELECT user_id FROM advisors);

-- CE Advisors
INSERT INTO users (username, password, role, full_name, email) VALUES
('advisor_ce_1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สมปอง (CE)', 'advisor.ce.1@uni.ac.th'),
('advisor_ce_2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สมศรี (CE)', 'advisor.ce.2@uni.ac.th'),
('advisor_ce_3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สมบัติ (CE)', 'advisor.ce.3@uni.ac.th'),
('advisor_ce_4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สมใจ (CE)', 'advisor.ce.4@uni.ac.th'),
('advisor_ce_5', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สมหมาย (CE)', 'advisor.ce.5@uni.ac.th'),
('advisor_ce_6', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สมหวัง (CE)', 'advisor.ce.6@uni.ac.th'),
('advisor_ce_7', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สมนึก (CE)', 'advisor.ce.7@uni.ac.th'),
('advisor_ce_8', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สมพร (CE)', 'advisor.ce.8@uni.ac.th'),
('advisor_ce_9', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สมยศ (CE)', 'advisor.ce.9@uni.ac.th'),
('advisor_ce_10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สมเกียรติ (CE)', 'advisor.ce.10@uni.ac.th');

INSERT INTO advisors (user_id, major_id) 
SELECT id, 'CE' FROM users WHERE username LIKE 'advisor_ce_%' AND id NOT IN (SELECT user_id FROM advisors);

-- EE Advisors
INSERT INTO users (username, password, role, full_name, email) VALUES
('advisor_ee_1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วิชัย (EE)', 'advisor.ee.1@uni.ac.th'),
('advisor_ee_2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วิยะดา (EE)', 'advisor.ee.2@uni.ac.th'),
('advisor_ee_3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วิโรจน์ (EE)', 'advisor.ee.3@uni.ac.th'),
('advisor_ee_4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วิภา (EE)', 'advisor.ee.4@uni.ac.th'),
('advisor_ee_5', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วิทวัส (EE)', 'advisor.ee.5@uni.ac.th'),
('advisor_ee_6', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วิมล (EE)', 'advisor.ee.6@uni.ac.th'),
('advisor_ee_7', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วิศิษฐ์ (EE)', 'advisor.ee.7@uni.ac.th'),
('advisor_ee_8', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วิไลวรรณ (EE)', 'advisor.ee.8@uni.ac.th'),
('advisor_ee_9', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วิชาญ (EE)', 'advisor.ee.9@uni.ac.th'),
('advisor_ee_10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วิชิต (EE)', 'advisor.ee.10@uni.ac.th');

INSERT INTO advisors (user_id, major_id) 
SELECT id, 'EE' FROM users WHERE username LIKE 'advisor_ee_%' AND id NOT IN (SELECT user_id FROM advisors);

-- ME Advisors
INSERT INTO users (username, password, role, full_name, email) VALUES
('advisor_me_1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ประเสริฐ (ME)', 'advisor.me.1@uni.ac.th'),
('advisor_me_2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ปราณี (ME)', 'advisor.me.2@uni.ac.th'),
('advisor_me_3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ประวิทย์ (ME)', 'advisor.me.3@uni.ac.th'),
('advisor_me_4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ประไพ (ME)', 'advisor.me.4@uni.ac.th'),
('advisor_me_5', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ประยุทธ์ (ME)', 'advisor.me.5@uni.ac.th'),
('advisor_me_6', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ประนอม (ME)', 'advisor.me.6@uni.ac.th'),
('advisor_me_7', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ประสิทธิ์ (ME)', 'advisor.me.7@uni.ac.th'),
('advisor_me_8', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ประภา (ME)', 'advisor.me.8@uni.ac.th'),
('advisor_me_9', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ประจักษ์ (ME)', 'advisor.me.9@uni.ac.th'),
('advisor_me_10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ประทีป (ME)', 'advisor.me.10@uni.ac.th');

INSERT INTO advisors (user_id, major_id) 
SELECT id, 'ME' FROM users WHERE username LIKE 'advisor_me_%' AND id NOT IN (SELECT user_id FROM advisors);

-- BA Advisors
INSERT INTO users (username, password, role, full_name, email) VALUES
('advisor_ba_1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สุรพล (BA)', 'advisor.ba.1@uni.ac.th'),
('advisor_ba_2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สุรีย์ (BA)', 'advisor.ba.2@uni.ac.th'),
('advisor_ba_3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สุรชัย (BA)', 'advisor.ba.3@uni.ac.th'),
('advisor_ba_4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สุภา (BA)', 'advisor.ba.4@uni.ac.th'),
('advisor_ba_5', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สุรินทร์ (BA)', 'advisor.ba.5@uni.ac.th'),
('advisor_ba_6', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สุนิสา (BA)', 'advisor.ba.6@uni.ac.th'),
('advisor_ba_7', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สุริยา (BA)', 'advisor.ba.7@uni.ac.th'),
('advisor_ba_8', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สุวรรณา (BA)', 'advisor.ba.8@uni.ac.th'),
('advisor_ba_9', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สุรศักดิ์ (BA)', 'advisor.ba.9@uni.ac.th'),
('advisor_ba_10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สุจินดา (BA)', 'advisor.ba.10@uni.ac.th');

INSERT INTO advisors (user_id, major_id) 
SELECT id, 'BA' FROM users WHERE username LIKE 'advisor_ba_%' AND id NOT IN (SELECT user_id FROM advisors);

-- ACC Advisors
INSERT INTO users (username, password, role, full_name, email) VALUES
('advisor_acc_1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.อำนาจ (ACC)', 'advisor.acc.1@uni.ac.th'),
('advisor_acc_2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.อำไพ (ACC)', 'advisor.acc.2@uni.ac.th'),
('advisor_acc_3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.อำนวย (ACC)', 'advisor.acc.3@uni.ac.th'),
('advisor_acc_4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.อำภา (ACC)', 'advisor.acc.4@uni.ac.th'),
('advisor_acc_5', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.อำพล (ACC)', 'advisor.acc.5@uni.ac.th'),
('advisor_acc_6', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.อุไร (ACC)', 'advisor.acc.6@uni.ac.th'),
('advisor_acc_7', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.อุดม (ACC)', 'advisor.acc.7@uni.ac.th'),
('advisor_acc_8', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.อุษา (ACC)', 'advisor.acc.8@uni.ac.th'),
('advisor_acc_9', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.อุทัย (ACC)', 'advisor.acc.9@uni.ac.th'),
('advisor_acc_10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.อุบล (ACC)', 'advisor.acc.10@uni.ac.th');

INSERT INTO advisors (user_id, major_id) 
SELECT id, 'ACC' FROM users WHERE username LIKE 'advisor_acc_%' AND id NOT IN (SELECT user_id FROM advisors);

-- MKT Advisors
INSERT INTO users (username, password, role, full_name, email) VALUES
('advisor_mkt_1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ชัยวัฒน์ (MKT)', 'advisor.mkt.1@uni.ac.th'),
('advisor_mkt_2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ชไมพร (MKT)', 'advisor.mkt.2@uni.ac.th'),
('advisor_mkt_3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ชัยณรงค์ (MKT)', 'advisor.mkt.3@uni.ac.th'),
('advisor_mkt_4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ชมพู่ (MKT)', 'advisor.mkt.4@uni.ac.th'),
('advisor_mkt_5', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ชัยสิทธิ์ (MKT)', 'advisor.mkt.5@uni.ac.th'),
('advisor_mkt_6', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ชลดา (MKT)', 'advisor.mkt.6@uni.ac.th'),
('advisor_mkt_7', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ชัยยศ (MKT)', 'advisor.mkt.7@uni.ac.th'),
('advisor_mkt_8', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ช่อทิพย์ (MKT)', 'advisor.mkt.8@uni.ac.th'),
('advisor_mkt_9', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ชัยพร (MKT)', 'advisor.mkt.9@uni.ac.th'),
('advisor_mkt_10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ชุติมา (MKT)', 'advisor.mkt.10@uni.ac.th');

INSERT INTO advisors (user_id, major_id) 
SELECT id, 'MKT' FROM users WHERE username LIKE 'advisor_mkt_%' AND id NOT IN (SELECT user_id FROM advisors);

-- ECON Advisors
INSERT INTO users (username, password, role, full_name, email) VALUES
('advisor_econ_1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ธนากร (ECON)', 'advisor.econ.1@uni.ac.th'),
('advisor_econ_2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ธนัชชา (ECON)', 'advisor.econ.2@uni.ac.th'),
('advisor_econ_3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ธนินทร์ (ECON)', 'advisor.econ.3@uni.ac.th'),
('advisor_econ_4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ธนภรณ์ (ECON)', 'advisor.econ.4@uni.ac.th'),
('advisor_econ_5', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ธนวัฒน์ (ECON)', 'advisor.econ.5@uni.ac.th'),
('advisor_econ_6', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ธนวรรณ (ECON)', 'advisor.econ.6@uni.ac.th'),
('advisor_econ_7', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ธนดล (ECON)', 'advisor.econ.7@uni.ac.th'),
('advisor_econ_8', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ธนารีย์ (ECON)', 'advisor.econ.8@uni.ac.th'),
('advisor_econ_9', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ธนกฤต (ECON)', 'advisor.econ.9@uni.ac.th'),
('advisor_econ_10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ธนิกา (ECON)', 'advisor.econ.10@uni.ac.th');

INSERT INTO advisors (user_id, major_id) 
SELECT id, 'ECON' FROM users WHERE username LIKE 'advisor_econ_%' AND id NOT IN (SELECT user_id FROM advisors);

-- COMM Advisors
INSERT INTO users (username, password, role, full_name, email) VALUES
('advisor_comm_1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.นพดล (COMM)', 'advisor.comm.1@uni.ac.th'),
('advisor_comm_2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.นพวรรณ (COMM)', 'advisor.comm.2@uni.ac.th'),
('advisor_comm_3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.นพรัตน์ (COMM)', 'advisor.comm.3@uni.ac.th'),
('advisor_comm_4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.นพมาศ (COMM)', 'advisor.comm.4@uni.ac.th'),
('advisor_comm_5', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.นพสิทธิ์ (COMM)', 'advisor.comm.5@uni.ac.th'),
('advisor_comm_6', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.นพเก้า (COMM)', 'advisor.comm.6@uni.ac.th'),
('advisor_comm_7', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.นพพล (COMM)', 'advisor.comm.7@uni.ac.th'),
('advisor_comm_8', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.นพดา (COMM)', 'advisor.comm.8@uni.ac.th'),
('advisor_comm_9', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.นพคุณ (COMM)', 'advisor.comm.9@uni.ac.th'),
('advisor_comm_10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.นพสร (COMM)', 'advisor.comm.10@uni.ac.th');

INSERT INTO advisors (user_id, major_id) 
SELECT id, 'COMM' FROM users WHERE username LIKE 'advisor_comm_%' AND id NOT IN (SELECT user_id FROM advisors);
