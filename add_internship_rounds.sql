-- Add internship_rounds table
CREATE TABLE IF NOT EXISTS `internship_rounds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `round_name` varchar(100) NOT NULL,
  `year` int(4) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample data
INSERT INTO `internship_rounds` (`round_name`, `year`, `start_date`, `end_date`, `is_active`) VALUES
('รอบที่ 1', 2024, '2024-06-01', '2024-08-31', 1),
('รอบที่ 2', 2024, '2024-11-01', '2025-01-31', 1),
('รอบที่ 1', 2025, '2025-06-01', '2025-08-31', 1);

-- Add round_id to internship_requests if not exists
ALTER TABLE `internship_requests` 
ADD COLUMN IF NOT EXISTS `round_id` int(11) DEFAULT NULL AFTER `company_id`,
ADD COLUMN IF NOT EXISTS `admin_notes` text DEFAULT NULL AFTER `status`;

-- Add foreign key
ALTER TABLE `internship_requests`
ADD CONSTRAINT `fk_internship_requests_round` 
FOREIGN KEY (`round_id`) REFERENCES `internship_rounds`(`id`) 
ON DELETE SET NULL ON UPDATE CASCADE;
