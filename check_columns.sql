-- สคริปต์สำหรับเพิ่มคอลัมน์ใหม่โดยตรวจสอบว่ามีอยู่แล้วหรือไม่
-- รันทีละคำสั่ง ถ้า error "Duplicate column" ให้ข้ามไปคำสั่งถัดไป

USE internship_system;

-- ตรวจสอบว่าคอลัมน์ไหนมีอยู่แล้วบ้าง
SELECT 
    COLUMN_NAME,
    DATA_TYPE,
    COLUMN_TYPE,
    IS_NULLABLE,
    COLUMN_DEFAULT,
    COLUMN_COMMENT
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = 'internship_system'
AND TABLE_NAME = 'internship_requests'
AND COLUMN_NAME IN (
    'faculty_approval_status',
    'faculty_approval_date',
    'faculty_comment',
    'company_response_status',
    'company_response_date',
    'company_response_comment',
    'document_response_status',
    'document_response_date',
    'document_files',
    'round_id',
    'updated_at'
)
ORDER BY ORDINAL_POSITION;
