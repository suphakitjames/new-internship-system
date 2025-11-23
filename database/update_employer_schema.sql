-- database/update_employer_schema.sql

-- Drop existing evaluations table if it exists to recreate with detailed columns
DROP TABLE IF EXISTS evaluations;

-- Create detailed evaluations table based on the user's requirements
CREATE TABLE evaluations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    employer_id INT NOT NULL, -- The user_id of the employer
    company_id INT NOT NULL, -- Link to the company
    
    -- 10 Evaluation Criteria (1-5 scale)
    criteria_1 INT COMMENT 'มีความเอาใจใส่และรับผิดชอบงานอย่างมีคุณภาพ',
    criteria_2 INT COMMENT 'ตั้งใจแสวงหาความรู้และเรียนรู้งาน',
    criteria_3 INT COMMENT 'การเอาใจใส่ระมัดระวังต่ออุปกรณ์และเครื่องมือเครื่องใช้',
    criteria_4 INT COMMENT 'มีความกระตือรือร้น ตรงต่อเวลา และซื่อสัตย์',
    criteria_5 INT COMMENT 'บุคลิกภาพและการแต่งกายที่เรียบร้อย',
    criteria_6 INT COMMENT 'มนุษยสัมพันธ์ในการทำงาน และมีความรอบรู้ทันต่อเหตุการณ์',
    criteria_7 INT COMMENT 'มีความสามารถในการปรับตัวและแก้ปัญหา',
    criteria_8 INT COMMENT 'การปฏิบัติตามระเบียบของหน่วยงาน',
    criteria_9 INT COMMENT 'การนำวิชาความรู้มาประยุกต์ใช้ในการทำงาน',
    criteria_10 INT COMMENT 'มีทักษะในการใช้ภาษาเพื่อการสื่อสารได้อย่างมีประสิทธิภาพ',
    
    suggestion TEXT COMMENT 'ความคิดเห็น และข้อเสนอแนะ',
    result_status ENUM('pass', 'fail') DEFAULT 'pass' COMMENT 'ผลการประเมิน (ผ่าน/ไม่ผ่าน)',
    
    evaluation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (student_id) REFERENCES students(user_id) ON DELETE CASCADE,
    FOREIGN KEY (employer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE
);

-- Ensure we have an employer user for testing (if not already exists)
-- Password is 'password'
INSERT INTO users (username, password, role, full_name, email) 
SELECT 'employer1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employer', 'บริษัท ตัวอย่าง จำกัด', 'hr@example.com'
WHERE NOT EXISTS (SELECT 1 FROM users WHERE username = 'employer1');

-- Link the employer to a company (Assuming we have a way to link users to companies)
-- For this implementation, we might need to add a column to users or companies to link them.
-- Let's add 'owner_id' to companies table if it doesn't exist, or use the existing 'created_by' if appropriate.
-- But 'created_by' might be the student.
-- Let's add a specific 'employer_user_id' to companies to be safe and explicit.

ALTER TABLE companies ADD COLUMN employer_user_id INT NULL;
ALTER TABLE companies ADD CONSTRAINT fk_company_employer FOREIGN KEY (employer_user_id) REFERENCES users(id) ON DELETE SET NULL;

-- Update a sample company to be owned by our test employer
UPDATE companies SET employer_user_id = (SELECT id FROM users WHERE username = 'employer1') LIMIT 1;
