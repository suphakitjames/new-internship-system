USE internship_system;

-- 1. Create internship_rounds table
CREATE TABLE IF NOT EXISTS `internship_rounds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `round_name` varchar(100) NOT NULL COMMENT 'ชื่อรอบการฝึกงาน',
  `year` int(4) NOT NULL COMMENT 'ปีการศึกษา',
  `start_date` date NOT NULL COMMENT 'วันเริ่มต้น',
  `end_date` date NOT NULL COMMENT 'วันสิ้นสุด',
  `is_active` tinyint(1) DEFAULT 1 COMMENT 'สถานะเปิด/ปิด',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `internship_rounds` (`round_name`, `year`, `start_date`, `end_date`, `is_active`) VALUES
('รอบที่ 1/2567', 2024, '2024-06-01', '2024-08-31', 1),
('รอบที่ 2/2567', 2024, '2024-11-01', '2025-01-31', 1),
('รอบที่ 1/2568', 2025, '2025-06-01', '2025-08-31', 1);

-- 2. Add new columns to internship_requests
-- Using procedures to avoid errors if columns already exist (idempotent)
DROP PROCEDURE IF EXISTS UpgradeInternshipRequests;
DELIMITER //
CREATE PROCEDURE UpgradeInternshipRequests()
BEGIN
    -- faculty_approval_status
    IF NOT EXISTS (SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'internship_system' AND TABLE_NAME = 'internship_requests' AND COLUMN_NAME = 'faculty_approval_status') THEN
        ALTER TABLE internship_requests ADD COLUMN faculty_approval_status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending' COMMENT 'สถานะการพิจารณาจากคณะ';
    END IF;

    -- faculty_approval_date
    IF NOT EXISTS (SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'internship_system' AND TABLE_NAME = 'internship_requests' AND COLUMN_NAME = 'faculty_approval_date') THEN
        ALTER TABLE internship_requests ADD COLUMN faculty_approval_date DATETIME NULL COMMENT 'วันที่คณะพิจารณา';
    END IF;

    -- faculty_comment
    IF NOT EXISTS (SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'internship_system' AND TABLE_NAME = 'internship_requests' AND COLUMN_NAME = 'faculty_comment') THEN
        ALTER TABLE internship_requests ADD COLUMN faculty_comment TEXT NULL COMMENT 'ความคิดเห็นจากคณะ';
    END IF;

    -- company_response_status
    IF NOT EXISTS (SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'internship_system' AND TABLE_NAME = 'internship_requests' AND COLUMN_NAME = 'company_response_status') THEN
        ALTER TABLE internship_requests ADD COLUMN company_response_status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending' COMMENT 'สถานะการตอบรับจากบริษัท';
    END IF;

    -- company_response_date
    IF NOT EXISTS (SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'internship_system' AND TABLE_NAME = 'internship_requests' AND COLUMN_NAME = 'company_response_date') THEN
        ALTER TABLE internship_requests ADD COLUMN company_response_date DATETIME NULL COMMENT 'วันที่บริษัทตอบกลับ';
    END IF;

    -- company_response_comment
    IF NOT EXISTS (SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'internship_system' AND TABLE_NAME = 'internship_requests' AND COLUMN_NAME = 'company_response_comment') THEN
        ALTER TABLE internship_requests ADD COLUMN company_response_comment TEXT NULL COMMENT 'ความคิดเห็นจากบริษัท';
    END IF;

    -- document_response_status
    IF NOT EXISTS (SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'internship_system' AND TABLE_NAME = 'internship_requests' AND COLUMN_NAME = 'document_response_status') THEN
        ALTER TABLE internship_requests ADD COLUMN document_response_status ENUM('pending', 'submitted', 'approved', 'rejected') DEFAULT 'pending' COMMENT 'สถานะการตอบกลับเอกสาร';
    END IF;

    -- document_response_date
    IF NOT EXISTS (SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'internship_system' AND TABLE_NAME = 'internship_requests' AND COLUMN_NAME = 'document_response_date') THEN
        ALTER TABLE internship_requests ADD COLUMN document_response_date DATETIME NULL COMMENT 'วันที่ตอบกลับเอกสาร';
    END IF;

    -- document_files
    IF NOT EXISTS (SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'internship_system' AND TABLE_NAME = 'internship_requests' AND COLUMN_NAME = 'document_files') THEN
        ALTER TABLE internship_requests ADD COLUMN document_files TEXT NULL COMMENT 'ไฟล์เอกสารที่แนบ (JSON array)';
    END IF;

    -- round_id
    IF NOT EXISTS (SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'internship_system' AND TABLE_NAME = 'internship_requests' AND COLUMN_NAME = 'round_id') THEN
        ALTER TABLE internship_requests ADD COLUMN round_id INT NULL COMMENT 'รอบการฝึกงาน';
    END IF;

    -- updated_at
    IF NOT EXISTS (SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'internship_system' AND TABLE_NAME = 'internship_requests' AND COLUMN_NAME = 'updated_at') THEN
        ALTER TABLE internship_requests ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'วันที่อัพเดทล่าสุด';
    END IF;
END//
DELIMITER ;
CALL UpgradeInternshipRequests();
DROP PROCEDURE UpgradeInternshipRequests;

-- 3. Update old data
UPDATE internship_requests 
SET 
    faculty_approval_status = COALESCE(faculty_approval_status, 'pending'),
    company_response_status = COALESCE(company_response_status, 'pending'),
    document_response_status = COALESCE(document_response_status, 'pending')
WHERE id > 0;
