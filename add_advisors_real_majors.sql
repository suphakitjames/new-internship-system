-- SQL for adding Advisors (10 per major) based on your actual majors
USE internship_system;

-- 1. Ensure Advisors Table Exists
CREATE TABLE IF NOT EXISTS advisors (
    user_id INT PRIMARY KEY,
    major_id VARCHAR(10),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (major_id) REFERENCES majors(id) ON DELETE SET NULL
);

-- 2. Insert Advisors (10 per major)
-- Password is 'password' ($2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi)

-- GM: การจัดการ
INSERT INTO users (username, password, role, full_name, email) VALUES
('advisor_gm_1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สมชาย (GM)', 'advisor.gm.1@uni.ac.th'),
('advisor_gm_2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วิไล (GM)', 'advisor.gm.2@uni.ac.th'),
('advisor_gm_3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สุชาติ (GM)', 'advisor.gm.3@uni.ac.th'),
('advisor_gm_4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.กานดา (GM)', 'advisor.gm.4@uni.ac.th'),
('advisor_gm_5', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ณัฐพล (GM)', 'advisor.gm.5@uni.ac.th'),
('advisor_gm_6', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ปวีณา (GM)', 'advisor.gm.6@uni.ac.th'),
('advisor_gm_7', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ธนพล (GM)', 'advisor.gm.7@uni.ac.th'),
('advisor_gm_8', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.นภา (GM)', 'advisor.gm.8@uni.ac.th'),
('advisor_gm_9', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ชัย (GM)', 'advisor.gm.9@uni.ac.th'),
('advisor_gm_10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ชลธิชา (GM)', 'advisor.gm.10@uni.ac.th');

INSERT INTO advisors (user_id, major_id) 
SELECT id, 'GM' FROM users WHERE username LIKE 'advisor_gm_%' AND id NOT IN (SELECT user_id FROM advisors);

-- ENT: การจัดการการประกอบการ
INSERT INTO users (username, password, role, full_name, email) VALUES
('advisor_ent_1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.กิตติ (ENT)', 'advisor.ent.1@uni.ac.th'),
('advisor_ent_2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.มณี (ENT)', 'advisor.ent.2@uni.ac.th'),
('advisor_ent_3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วิชัย (ENT)', 'advisor.ent.3@uni.ac.th'),
('advisor_ent_4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.รัตนา (ENT)', 'advisor.ent.4@uni.ac.th'),
('advisor_ent_5', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สมศักดิ์ (ENT)', 'advisor.ent.5@uni.ac.th'),
('advisor_ent_6', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.อารี (ENT)', 'advisor.ent.6@uni.ac.th'),
('advisor_ent_7', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.บุญมี (ENT)', 'advisor.ent.7@uni.ac.th'),
('advisor_ent_8', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.จินตนา (ENT)', 'advisor.ent.8@uni.ac.th'),
('advisor_ent_9', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ประดิษฐ์ (ENT)', 'advisor.ent.9@uni.ac.th'),
('advisor_ent_10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วรรณา (ENT)', 'advisor.ent.10@uni.ac.th');

INSERT INTO advisors (user_id, major_id) 
SELECT id, 'ENT' FROM users WHERE username LIKE 'advisor_ent_%' AND id NOT IN (SELECT user_id FROM advisors);

-- HRM: การจัดการทรัพยากรมนุษย์
INSERT INTO users (username, password, role, full_name, email) VALUES
('advisor_hrm_1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สมปอง (HRM)', 'advisor.hrm.1@uni.ac.th'),
('advisor_hrm_2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สมศรี (HRM)', 'advisor.hrm.2@uni.ac.th'),
('advisor_hrm_3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สมบัติ (HRM)', 'advisor.hrm.3@uni.ac.th'),
('advisor_hrm_4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สมใจ (HRM)', 'advisor.hrm.4@uni.ac.th'),
('advisor_hrm_5', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สมหมาย (HRM)', 'advisor.hrm.5@uni.ac.th'),
('advisor_hrm_6', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สมหวัง (HRM)', 'advisor.hrm.6@uni.ac.th'),
('advisor_hrm_7', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สมนึก (HRM)', 'advisor.hrm.7@uni.ac.th'),
('advisor_hrm_8', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สมพร (HRM)', 'advisor.hrm.8@uni.ac.th'),
('advisor_hrm_9', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สมยศ (HRM)', 'advisor.hrm.9@uni.ac.th'),
('advisor_hrm_10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สมเกียรติ (HRM)', 'advisor.hrm.10@uni.ac.th');

INSERT INTO advisors (user_id, major_id) 
SELECT id, 'HRM' FROM users WHERE username LIKE 'advisor_hrm_%' AND id NOT IN (SELECT user_id FROM advisors);

-- ECOM: การจัดการพาณิชย์อิเล็กทรอนิกส์
INSERT INTO users (username, password, role, full_name, email) VALUES
('advisor_ecom_1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วิชัย (ECOM)', 'advisor.ecom.1@uni.ac.th'),
('advisor_ecom_2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วิยะดา (ECOM)', 'advisor.ecom.2@uni.ac.th'),
('advisor_ecom_3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วิโรจน์ (ECOM)', 'advisor.ecom.3@uni.ac.th'),
('advisor_ecom_4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วิภา (ECOM)', 'advisor.ecom.4@uni.ac.th'),
('advisor_ecom_5', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วิทวัส (ECOM)', 'advisor.ecom.5@uni.ac.th'),
('advisor_ecom_6', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วิมล (ECOM)', 'advisor.ecom.6@uni.ac.th'),
('advisor_ecom_7', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วิศิษฐ์ (ECOM)', 'advisor.ecom.7@uni.ac.th'),
('advisor_ecom_8', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วิไลวรรณ (ECOM)', 'advisor.ecom.8@uni.ac.th'),
('advisor_ecom_9', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วิชาญ (ECOM)', 'advisor.ecom.9@uni.ac.th'),
('advisor_ecom_10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วิชิต (ECOM)', 'advisor.ecom.10@uni.ac.th');

INSERT INTO advisors (user_id, major_id) 
SELECT id, 'ECOM' FROM users WHERE username LIKE 'advisor_ecom_%' AND id NOT IN (SELECT user_id FROM advisors);

-- MK: การตลาด
INSERT INTO users (username, password, role, full_name, email) VALUES
('advisor_mk_1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ประเสริฐ (MK)', 'advisor.mk.1@uni.ac.th'),
('advisor_mk_2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ปราณี (MK)', 'advisor.mk.2@uni.ac.th'),
('advisor_mk_3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ประวิทย์ (MK)', 'advisor.mk.3@uni.ac.th'),
('advisor_mk_4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ประไพ (MK)', 'advisor.mk.4@uni.ac.th'),
('advisor_mk_5', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ประยุทธ์ (MK)', 'advisor.mk.5@uni.ac.th'),
('advisor_mk_6', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ประนอม (MK)', 'advisor.mk.6@uni.ac.th'),
('advisor_mk_7', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ประสิทธิ์ (MK)', 'advisor.mk.7@uni.ac.th'),
('advisor_mk_8', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ประภา (MK)', 'advisor.mk.8@uni.ac.th'),
('advisor_mk_9', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ประจักษ์ (MK)', 'advisor.mk.9@uni.ac.th'),
('advisor_mk_10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ประทีป (MK)', 'advisor.mk.10@uni.ac.th');

INSERT INTO advisors (user_id, major_id) 
SELECT id, 'MK' FROM users WHERE username LIKE 'advisor_mk_%' AND id NOT IN (SELECT user_id FROM advisors);

-- FM: การบริหารการเงิน
INSERT INTO users (username, password, role, full_name, email) VALUES
('advisor_fm_1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สุรพล (FM)', 'advisor.fm.1@uni.ac.th'),
('advisor_fm_2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สุรีย์ (FM)', 'advisor.fm.2@uni.ac.th'),
('advisor_fm_3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สุรชัย (FM)', 'advisor.fm.3@uni.ac.th'),
('advisor_fm_4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สุภา (FM)', 'advisor.fm.4@uni.ac.th'),
('advisor_fm_5', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สุรินทร์ (FM)', 'advisor.fm.5@uni.ac.th'),
('advisor_fm_6', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สุนิสา (FM)', 'advisor.fm.6@uni.ac.th'),
('advisor_fm_7', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สุริยา (FM)', 'advisor.fm.7@uni.ac.th'),
('advisor_fm_8', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สุวรรณา (FM)', 'advisor.fm.8@uni.ac.th'),
('advisor_fm_9', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สุรศักดิ์ (FM)', 'advisor.fm.9@uni.ac.th'),
('advisor_fm_10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สุจินดา (FM)', 'advisor.fm.10@uni.ac.th');

INSERT INTO advisors (user_id, major_id) 
SELECT id, 'FM' FROM users WHERE username LIKE 'advisor_fm_%' AND id NOT IN (SELECT user_id FROM advisors);

-- AC: การบัญชี
INSERT INTO users (username, password, role, full_name, email) VALUES
('advisor_ac_1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.อำนาจ (AC)', 'advisor.ac.1@uni.ac.th'),
('advisor_ac_2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.อำไพ (AC)', 'advisor.ac.2@uni.ac.th'),
('advisor_ac_3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.อำนวย (AC)', 'advisor.ac.3@uni.ac.th'),
('advisor_ac_4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.อำภา (AC)', 'advisor.ac.4@uni.ac.th'),
('advisor_ac_5', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.อำพล (AC)', 'advisor.ac.5@uni.ac.th'),
('advisor_ac_6', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.อุไร (AC)', 'advisor.ac.6@uni.ac.th'),
('advisor_ac_7', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.อุดม (AC)', 'advisor.ac.7@uni.ac.th'),
('advisor_ac_8', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.อุษา (AC)', 'advisor.ac.8@uni.ac.th'),
('advisor_ac_9', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.อุทัย (AC)', 'advisor.ac.9@uni.ac.th'),
('advisor_ac_10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.อุบล (AC)', 'advisor.ac.10@uni.ac.th');

INSERT INTO advisors (user_id, major_id) 
SELECT id, 'AC' FROM users WHERE username LIKE 'advisor_ac_%' AND id NOT IN (SELECT user_id FROM advisors);

-- AC (EP): การบัญชี (หลักสูตรภาษาอังกฤษ)
INSERT INTO users (username, password, role, full_name, email) VALUES
('advisor_acep_1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'Aj. John (AC-EP)', 'advisor.acep.1@uni.ac.th'),
('advisor_acep_2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'Aj. Mary (AC-EP)', 'advisor.acep.2@uni.ac.th'),
('advisor_acep_3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'Aj. David (AC-EP)', 'advisor.acep.3@uni.ac.th'),
('advisor_acep_4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'Aj. Sarah (AC-EP)', 'advisor.acep.4@uni.ac.th'),
('advisor_acep_5', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'Aj. Michael (AC-EP)', 'advisor.acep.5@uni.ac.th'),
('advisor_acep_6', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'Aj. Jennifer (AC-EP)', 'advisor.acep.6@uni.ac.th'),
('advisor_acep_7', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'Aj. Robert (AC-EP)', 'advisor.acep.7@uni.ac.th'),
('advisor_acep_8', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'Aj. Linda (AC-EP)', 'advisor.acep.8@uni.ac.th'),
('advisor_acep_9', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'Aj. William (AC-EP)', 'advisor.acep.9@uni.ac.th'),
('advisor_acep_10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'Aj. Elizabeth (AC-EP)', 'advisor.acep.10@uni.ac.th');

INSERT INTO advisors (user_id, major_id) 
SELECT id, 'AC (EP)' FROM users WHERE username LIKE 'advisor_acep_%' AND id NOT IN (SELECT user_id FROM advisors);

-- BC: คอมพิวเตอร์ธุรกิจ
INSERT INTO users (username, password, role, full_name, email) VALUES
('advisor_bc_1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ชัยวัฒน์ (BC)', 'advisor.bc.1@uni.ac.th'),
('advisor_bc_2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ชไมพร (BC)', 'advisor.bc.2@uni.ac.th'),
('advisor_bc_3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ชัยณรงค์ (BC)', 'advisor.bc.3@uni.ac.th'),
('advisor_bc_4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ชมพู่ (BC)', 'advisor.bc.4@uni.ac.th'),
('advisor_bc_5', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ชัยสิทธิ์ (BC)', 'advisor.bc.5@uni.ac.th'),
('advisor_bc_6', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ชลดา (BC)', 'advisor.bc.6@uni.ac.th'),
('advisor_bc_7', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ชัยยศ (BC)', 'advisor.bc.7@uni.ac.th'),
('advisor_bc_8', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ช่อทิพย์ (BC)', 'advisor.bc.8@uni.ac.th'),
('advisor_bc_9', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ชัยพร (BC)', 'advisor.bc.9@uni.ac.th'),
('advisor_bc_10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ชุติมา (BC)', 'advisor.bc.10@uni.ac.th');

INSERT INTO advisors (user_id, major_id) 
SELECT id, 'BC' FROM users WHERE username LIKE 'advisor_bc_%' AND id NOT IN (SELECT user_id FROM advisors);

-- BIT: เทคโนโลยีสารสนเทศธุรกิจ
INSERT INTO users (username, password, role, full_name, email) VALUES
('advisor_bit_1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ธนากร (BIT)', 'advisor.bit.1@uni.ac.th'),
('advisor_bit_2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ธนัชชา (BIT)', 'advisor.bit.2@uni.ac.th'),
('advisor_bit_3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ธนินทร์ (BIT)', 'advisor.bit.3@uni.ac.th'),
('advisor_bit_4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ธนภรณ์ (BIT)', 'advisor.bit.4@uni.ac.th'),
('advisor_bit_5', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ธนวัฒน์ (BIT)', 'advisor.bit.5@uni.ac.th'),
('advisor_bit_6', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ธนวรรณ (BIT)', 'advisor.bit.6@uni.ac.th'),
('advisor_bit_7', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ธนดล (BIT)', 'advisor.bit.7@uni.ac.th'),
('advisor_bit_8', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ธนารีย์ (BIT)', 'advisor.bit.8@uni.ac.th'),
('advisor_bit_9', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ธนกฤต (BIT)', 'advisor.bit.9@uni.ac.th'),
('advisor_bit_10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ธนิกา (BIT)', 'advisor.bit.10@uni.ac.th');

INSERT INTO advisors (user_id, major_id) 
SELECT id, 'BIT' FROM users WHERE username LIKE 'advisor_bit_%' AND id NOT IN (SELECT user_id FROM advisors);

-- IB: ธุรกิจระหว่างประเทศ
INSERT INTO users (username, password, role, full_name, email) VALUES
('advisor_ib_1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.นพดล (IB)', 'advisor.ib.1@uni.ac.th'),
('advisor_ib_2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.นพวรรณ (IB)', 'advisor.ib.2@uni.ac.th'),
('advisor_ib_3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.นพรัตน์ (IB)', 'advisor.ib.3@uni.ac.th'),
('advisor_ib_4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.นพมาศ (IB)', 'advisor.ib.4@uni.ac.th'),
('advisor_ib_5', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.นพสิทธิ์ (IB)', 'advisor.ib.5@uni.ac.th'),
('advisor_ib_6', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.นพเก้า (IB)', 'advisor.ib.6@uni.ac.th'),
('advisor_ib_7', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.นพพล (IB)', 'advisor.ib.7@uni.ac.th'),
('advisor_ib_8', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.นพดา (IB)', 'advisor.ib.8@uni.ac.th'),
('advisor_ib_9', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.นพคุณ (IB)', 'advisor.ib.9@uni.ac.th'),
('advisor_ib_10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.นพสร (IB)', 'advisor.ib.10@uni.ac.th');

INSERT INTO advisors (user_id, major_id) 
SELECT id, 'IB' FROM users WHERE username LIKE 'advisor_ib_%' AND id NOT IN (SELECT user_id FROM advisors);

-- BE: เศรษฐศาสตร์ธุรกิจ
INSERT INTO users (username, password, role, full_name, email) VALUES
('advisor_be_1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วรพล (BE)', 'advisor.be.1@uni.ac.th'),
('advisor_be_2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วรวรรณ (BE)', 'advisor.be.2@uni.ac.th'),
('advisor_be_3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วรวิทย์ (BE)', 'advisor.be.3@uni.ac.th'),
('advisor_be_4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วรภา (BE)', 'advisor.be.4@uni.ac.th'),
('advisor_be_5', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วรพจน์ (BE)', 'advisor.be.5@uni.ac.th'),
('advisor_be_6', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วรนุช (BE)', 'advisor.be.6@uni.ac.th'),
('advisor_be_7', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วรยศ (BE)', 'advisor.be.7@uni.ac.th'),
('advisor_be_8', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วรดา (BE)', 'advisor.be.8@uni.ac.th'),
('advisor_be_9', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วรชาติ (BE)', 'advisor.be.9@uni.ac.th'),
('advisor_be_10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วรินทร (BE)', 'advisor.be.10@uni.ac.th');

INSERT INTO advisors (user_id, major_id) 
SELECT id, 'BE' FROM users WHERE username LIKE 'advisor_be_%' AND id NOT IN (SELECT user_id FROM advisors);
