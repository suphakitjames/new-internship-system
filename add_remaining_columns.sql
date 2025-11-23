-- สคริปต์เพิ่มคอลัมน์ทีละคำสั่ง
-- ถ้า error "Duplicate column name" ให้ข้ามไปคำสั่งถัดไป (ไม่เป็นไร)

USE internship_system;

-- 1. ตรวจสอบคอลัมน์ที่มีอยู่แล้ว
SELECT 'กำลังตรวจสอบคอลัมน์ที่มีอยู่...' AS status;

-- 2. เพิ่มคอลัมน์ faculty_approval_date (ถ้ายังไม่มี)
-- ข้ามคำสั่งนี้ถ้า error
ALTER TABLE internship_requests 
ADD COLUMN faculty_approval_date DATETIME NULL COMMENT 'วันที่คณะพิจารณา';

-- 3. เพิ่มคอลัมน์ faculty_comment
ALTER TABLE internship_requests 
ADD COLUMN faculty_comment TEXT NULL COMMENT 'ความคิดเห็นจากคณะ';

-- 4. เพิ่มคอลัมน์ company_response_status
ALTER TABLE internship_requests 
ADD COLUMN company_response_status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending' COMMENT 'สถานะการตอบรับจากบริษัท';

-- 5. เพิ่มคอลัมน์ company_response_date
ALTER TABLE internship_requests 
ADD COLUMN company_response_date DATETIME NULL COMMENT 'วันที่บริษัทตอบกลับ';

-- 6. เพิ่มคอลัมน์ company_response_comment
ALTER TABLE internship_requests 
ADD COLUMN company_response_comment TEXT NULL COMMENT 'ความคิดเห็นจากบริษัท';

-- 7. เพิ่มคอลัมน์ document_response_status
ALTER TABLE internship_requests 
ADD COLUMN document_response_status ENUM('pending', 'submitted', 'approved', 'rejected') DEFAULT 'pending' COMMENT 'สถานะการตอบกลับเอกสาร';

-- 8. เพิ่มคอลัมน์ document_response_date
ALTER TABLE internship_requests 
ADD COLUMN document_response_date DATETIME NULL COMMENT 'วันที่ตอบกลับเอกสาร';

-- 9. เพิ่มคอลัมน์ document_files
ALTER TABLE internship_requests 
ADD COLUMN document_files TEXT NULL COMMENT 'ไฟล์เอกสารที่แนบ (JSON array)';

-- 10. เพิ่มคอลัมน์ round_id
ALTER TABLE internship_requests 
ADD COLUMN round_id INT NULL COMMENT 'รอบการฝึกงาน';

-- 11. เพิ่มคอลัมน์ updated_at
ALTER TABLE internship_requests 
ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'วันที่อัพเดทล่าสุด';

-- 12. อัพเดทข้อมูลเก่า
UPDATE internship_requests 
SET 
    faculty_approval_status = COALESCE(faculty_approval_status, 'pending'),
    company_response_status = COALESCE(company_response_status, 'pending'),
    document_response_status = COALESCE(document_response_status, 'pending')
WHERE id > 0;

-- 13. แสดงผลลัพธ์
SELECT 'เสร็จสิ้น! ตรวจสอบโครงสร้างตารางด้านล่าง' AS status;
DESCRIBE internship_requests;
