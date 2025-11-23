-- สร้างตาราง internship_rounds และอัพเดทตาราง internship_requests
-- รันไฟล์นี้ก่อนไฟล์ update_internship_requests_table.sql

USE internship_system;

-- สร้างตาราง internship_rounds (ถ้ายังไม่มี)
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

-- เพิ่มข้อมูลตัวอย่าง (ถ้ายังไม่มี)
INSERT INTO `internship_rounds` (`round_name`, `year`, `start_date`, `end_date`, `is_active`) 
SELECT * FROM (
    SELECT 'รอบที่ 1/2567' as round_name, 2024 as year, '2024-06-01' as start_date, '2024-08-31' as end_date, 1 as is_active
    UNION ALL
    SELECT 'รอบที่ 2/2567', 2024, '2024-11-01', '2025-01-31', 1
    UNION ALL
    SELECT 'รอบที่ 1/2568', 2025, '2025-06-01', '2025-08-31', 1
) AS tmp
WHERE NOT EXISTS (
    SELECT 1 FROM `internship_rounds` WHERE `year` = tmp.year AND `round_name` = tmp.round_name
) LIMIT 3;

-- แสดงข้อความสำเร็จ
SELECT 'ตาราง internship_rounds ถูกสร้างเรียบร้อยแล้ว' AS status;
