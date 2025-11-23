-- อัพเดทตาราง internship_requests เพื่อเพิ่มฟีเจอร์ใหม่
-- เพิ่มคอลัมน์สำหรับผลการพิจารณาของคณะ, ผลการตอบรับจากหน่วยงาน, และผลการตอบกลับเอกสาร

USE internship_system;

-- ตรวจสอบและเพิ่มคอลัมน์ทีละคอลัมน์ (ถ้ายังไม่มี)
-- หมายเหตุ: ถ้าคอลัมน์มีอยู่แล้ว จะเกิด error แต่ไม่เป็นไร ให้ข้ามไปคอลัมน์ถัดไป

-- เพิ่มคอลัมน์สำหรับผลการพิจารณาของคณะ
ALTER TABLE internship_requests
ADD COLUMN faculty_approval_status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending' COMMENT 'สถานะการพิจารณาจากคณะ';

ALTER TABLE internship_requests
ADD COLUMN faculty_approval_date DATETIME NULL COMMENT 'วันที่คณะพิจารณา';

ALTER TABLE internship_requests
ADD COLUMN faculty_comment TEXT NULL COMMENT 'ความคิดเห็นจากคณะ';

-- เพิ่มคอลัมน์สำหรับผลการตอบรับจากหน่วยงาน/บริษัท
ALTER TABLE internship_requests
ADD COLUMN company_response_status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending' COMMENT 'สถานะการตอบรับจากบริษัท';

ALTER TABLE internship_requests
ADD COLUMN company_response_date DATETIME NULL COMMENT 'วันที่บริษัทตอบกลับ';

ALTER TABLE internship_requests
ADD COLUMN company_response_comment TEXT NULL COMMENT 'ความคิดเห็นจากบริษัท';

-- เพิ่มคอลัมน์สำหรับผลการตอบกลับเอกสาร
ALTER TABLE internship_requests
ADD COLUMN document_response_status ENUM('pending', 'submitted', 'approved', 'rejected') DEFAULT 'pending' COMMENT 'สถานะการตอบกลับเอกสาร';

ALTER TABLE internship_requests
ADD COLUMN document_response_date DATETIME NULL COMMENT 'วันที่ตอบกลับเอกสาร';

ALTER TABLE internship_requests
ADD COLUMN document_files TEXT NULL COMMENT 'ไฟล์เอกสารที่แนบ (JSON array)';

-- เพิ่มคอลัมน์สำหรับข้อมูลเพิ่มเติม
ALTER TABLE internship_requests
ADD COLUMN round_id INT NULL COMMENT 'รอบการฝึกงาน';

ALTER TABLE internship_requests
ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'วันที่อัพเดทล่าสุด';

-- เพิ่ม Foreign Key สำหรับ round_id (ถ้ามีตาราง internship_rounds)
-- ALTER TABLE internship_requests
-- ADD CONSTRAINT fk_round FOREIGN KEY (round_id) REFERENCES internship_rounds(id) ON DELETE SET NULL;

-- อัพเดทข้อมูลเก่าให้มีค่าเริ่มต้น
UPDATE internship_requests 
SET 
    faculty_approval_status = COALESCE(faculty_approval_status, 'pending'),
    company_response_status = COALESCE(company_response_status, 'pending'),
    document_response_status = COALESCE(document_response_status, 'pending')
WHERE id > 0;
