-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 24, 2025 at 06:39 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `internship_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `advisors`
--

CREATE TABLE `advisors` (
  `user_id` int(11) NOT NULL,
  `major_id` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `advisors`
--

INSERT INTO `advisors` (`user_id`, `major_id`) VALUES
(78, 'AC'),
(79, 'AC'),
(80, 'AC'),
(81, 'AC'),
(82, 'AC'),
(83, 'AC'),
(84, 'AC'),
(85, 'AC'),
(86, 'AC'),
(87, 'AC'),
(88, 'AC (EP)'),
(89, 'AC (EP)'),
(90, 'AC (EP)'),
(91, 'AC (EP)'),
(92, 'AC (EP)'),
(93, 'AC (EP)'),
(94, 'AC (EP)'),
(95, 'AC (EP)'),
(96, 'AC (EP)'),
(97, 'AC (EP)'),
(98, 'BC'),
(99, 'BC'),
(100, 'BC'),
(101, 'BC'),
(102, 'BC'),
(103, 'BC'),
(104, 'BC'),
(105, 'BC'),
(106, 'BC'),
(107, 'BC'),
(128, 'BE'),
(129, 'BE'),
(130, 'BE'),
(131, 'BE'),
(132, 'BE'),
(133, 'BE'),
(134, 'BE'),
(135, 'BE'),
(136, 'BE'),
(137, 'BE'),
(108, 'BIT'),
(109, 'BIT'),
(110, 'BIT'),
(111, 'BIT'),
(112, 'BIT'),
(113, 'BIT'),
(114, 'BIT'),
(115, 'BIT'),
(116, 'BIT'),
(117, 'BIT'),
(48, 'ECOM'),
(49, 'ECOM'),
(50, 'ECOM'),
(51, 'ECOM'),
(52, 'ECOM'),
(53, 'ECOM'),
(54, 'ECOM'),
(55, 'ECOM'),
(56, 'ECOM'),
(57, 'ECOM'),
(28, 'ENT'),
(29, 'ENT'),
(30, 'ENT'),
(31, 'ENT'),
(32, 'ENT'),
(33, 'ENT'),
(34, 'ENT'),
(35, 'ENT'),
(36, 'ENT'),
(37, 'ENT'),
(68, 'FM'),
(69, 'FM'),
(70, 'FM'),
(71, 'FM'),
(72, 'FM'),
(73, 'FM'),
(74, 'FM'),
(75, 'FM'),
(76, 'FM'),
(77, 'FM'),
(18, 'GM'),
(19, 'GM'),
(20, 'GM'),
(21, 'GM'),
(22, 'GM'),
(23, 'GM'),
(24, 'GM'),
(25, 'GM'),
(26, 'GM'),
(27, 'GM'),
(38, 'HRM'),
(39, 'HRM'),
(40, 'HRM'),
(41, 'HRM'),
(42, 'HRM'),
(43, 'HRM'),
(44, 'HRM'),
(45, 'HRM'),
(46, 'HRM'),
(47, 'HRM'),
(118, 'IB'),
(119, 'IB'),
(120, 'IB'),
(121, 'IB'),
(122, 'IB'),
(123, 'IB'),
(124, 'IB'),
(125, 'IB'),
(126, 'IB'),
(127, 'IB'),
(58, 'MK'),
(59, 'MK'),
(60, 'MK'),
(61, 'MK'),
(62, 'MK'),
(63, 'MK'),
(64, 'MK'),
(65, 'MK'),
(66, 'MK'),
(67, 'MK');

-- --------------------------------------------------------

--
-- Table structure for table `advisor_messages`
--

CREATE TABLE `advisor_messages` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `advisor_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `advisor_messages`
--

INSERT INTO `advisor_messages` (`id`, `student_id`, `advisor_id`, `message`, `is_read`, `created_at`) VALUES
(1, 138, 18, 'มีปัญหากับที่ฝึกงาน หัวหน้าไม่เอาใจใส่ ไม่สอนงาน เพื่าอนร่วมงานไม่เป็นมิตร ทุกคนดูไม่น่าคบ ช่วยคุยกับหัวหน้างานหน่อยครับอาจารย์', 1, '2025-11-23 14:14:26');

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `address` text DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `employer_user_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `name`, `address`, `province`, `contact_person`, `phone`, `email`, `description`, `status`, `created_by`, `created_at`, `employer_user_id`, `user_id`) VALUES
(1, 'บริษัท ปตท. จำกัด (มหาชน)', '555 ถนนวิภาวดีรังสิต แขวงจตุจักร เขตจตุจักร กรุงเทพฯ 10900', '10', 'คุณสมชาย ใจดี', '02-537-2000', 'hr@pttplc.com', 'บริษัทพลังงานแห่งชาติ ผู้นำด้านธุรกิจน้ำมันและก๊าซธรรมชาติ', 'approved', NULL, '2025-11-21 08:44:44', 10, 17),
(2, 'บริษัท เอสซีจี เคมิคอลส์ จำกัด (มหาชน)', '1 ถนนปูนซิเมนต์ไทย บางซื่อ กรุงเทพฯ 10800', '10', 'คุณวิภาดา รักงาน', '02-586-3333', 'recruitment@scg.com', 'ผู้นำด้านนวัตกรรมเคมีภัณฑ์ครบวงจร', 'approved', NULL, '2025-11-21 08:44:44', NULL, NULL),
(3, 'บริษัท ไออาร์พีซี จำกัด (มหาชน)', '299 หมู่ 5 ถนนสุขุมวิท อำเภอเมืองระยอง ระยอง 21000', '62', 'คุณนิภา พัฒนา', '038-611-333', 'hr@irpc.co.th', 'ผู้ประกอบการอุตสาหกรรมปิโตรเคมีครบวงจรแห่งแรกในเอเชียตะวันออกเฉียงใต้', 'approved', NULL, '2025-11-21 08:44:44', NULL, NULL),
(4, 'โรงงานน้ำตาลมิตรผล (เลย)', '99 หมู่ 9 ถนนเลย-ด่านซ้าย อำเภอวังสะพุง เลย 42130', '43', 'คุณประเสริฐ มั่นคง', '042-801-111', 'hr_loei@mitrphol.com', 'โรงงานผลิตน้ำตาลทรายดิบและน้ำตาลทรายขาวบริสุทธิ์', 'approved', NULL, '2025-11-21 08:44:44', NULL, NULL),
(5, 'บริษัท เชียงใหม่โฟรเซ่นฟู้ดส์ จำกัด (มหาชน)', '149/34 ซอยแองโกลพลาซ่า ถนนสุรวงศ์ บางรัก กรุงเทพฯ (สำนักงานใหญ่)', '1', 'คุณอารียา สดใส', '053-222-111', 'hr@cmfrozen.com', 'ผลิตและส่งออกผักแช่แข็งคุณภาพสูง', 'approved', NULL, '2025-11-21 08:44:44', NULL, NULL),
(6, 'บริษัท ชลบุรี คอนกรีต จำกัด', '123 หมู่ 1 ถนนสุขุมวิท อำเภอเมืองชลบุรี ชลบุรี 20000', '61', 'คุณสมศักดิ์ ก่อสร้าง', '038-123-456', 'contact@chonburiconcrete.com', 'ผลิตคอนกรีตผสมเสร็จและผลิตภัณฑ์คอนกรีต', 'approved', NULL, '2025-11-21 08:44:44', NULL, NULL),
(7, 'โรงแรมภูเก็ต เกรซแลนด์ รีสอร์ท แอนด์ สปา', '190 ถนนทวีวงศ์ หาดป่าตอง อำเภอกะทู้ ภูเก็ต 83150', '69', 'HR Manager', '076-370-500', 'hr@phuketgraceland.com', 'โรงแรมและรีสอร์ทหรูระดับ 5 ดาว ริมหาดป่าตอง', 'approved', NULL, '2025-11-21 08:44:44', NULL, NULL),
(8, 'ธนาคารแห่งประเทศไทย สำนักงานภาคตะวันออกเฉียงเหนือ', '190 ถนนศรีจันทร์ อำเภอเมืองขอนแก่น ขอนแก่น 40000', '41', 'ผู้อำนวยการฝ่ายทรัพยากรบุคคล', '043-333-000', 'hr_ne@bot.or.th', 'ธนาคารกลาง ดูแลเสถียรภาพทางการเงินของประเทศ', 'approved', NULL, '2025-11-21 08:44:44', NULL, NULL),
(10, 'ddf', 'fggbcvc', '47', 'fddfdfd', '4151521', NULL, 'fdfddfdd', 'approved', 1, '2025-11-23 08:13:13', NULL, 16);

-- --------------------------------------------------------

--
-- Table structure for table `daily_logs`
--

CREATE TABLE `daily_logs` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `log_date` date NOT NULL,
  `work_description` text NOT NULL,
  `problems` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `daily_logs`
--

INSERT INTO `daily_logs` (`id`, `student_id`, `log_date`, `work_description`, `problems`, `created_at`) VALUES
(1, 2, '2025-11-22', 'test11120', 'ขี้เกียจ', '2025-11-21 12:38:58'),
(2, 2, '2025-11-23', 'test2 ', 'เจ้านายขี้โมโห ', '2025-11-21 12:39:30'),
(3, 11, '2025-11-23', 'fdffffddf', 'dfdffdfdf', '2025-11-23 05:58:55'),
(4, 11, '2025-11-24', 'fdfffff', 'fffvccctest', '2025-11-23 05:59:09');

-- --------------------------------------------------------

--
-- Table structure for table `evaluations`
--

CREATE TABLE `evaluations` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `employer_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `criteria_1` int(11) DEFAULT NULL COMMENT 'มีความเอาใจใส่และรับผิดชอบงานอย่างมีคุณภาพ',
  `criteria_2` int(11) DEFAULT NULL COMMENT 'ตั้งใจแสวงหาความรู้และเรียนรู้งาน',
  `criteria_3` int(11) DEFAULT NULL COMMENT 'การเอาใจใส่ระมัดระวังต่ออุปกรณ์และเครื่องมือเครื่องใช้',
  `criteria_4` int(11) DEFAULT NULL COMMENT 'มีความกระตือรือร้น ตรงต่อเวลา และซื่อสัตย์',
  `criteria_5` int(11) DEFAULT NULL COMMENT 'บุคลิกภาพและการแต่งกายที่เรียบร้อย',
  `criteria_6` int(11) DEFAULT NULL COMMENT 'มนุษยสัมพันธ์ในการทำงาน และมีความรอบรู้ทันต่อเหตุการณ์',
  `criteria_7` int(11) DEFAULT NULL COMMENT 'มีความสามารถในการปรับตัวและแก้ปัญหา',
  `criteria_8` int(11) DEFAULT NULL COMMENT 'การปฏิบัติตามระเบียบของหน่วยงาน',
  `criteria_9` int(11) DEFAULT NULL COMMENT 'การนำวิชาความรู้มาประยุกต์ใช้ในการทำงาน',
  `criteria_10` int(11) DEFAULT NULL COMMENT 'มีทักษะในการใช้ภาษาเพื่อการสื่อสารได้อย่างมีประสิทธิภาพ',
  `suggestion` text DEFAULT NULL COMMENT 'ความคิดเห็น และข้อเสนอแนะ',
  `result_status` enum('pass','fail') DEFAULT 'pass' COMMENT 'ผลการประเมิน (ผ่าน/ไม่ผ่าน)',
  `evaluation_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `evaluations`
--

INSERT INTO `evaluations` (`id`, `student_id`, `employer_id`, `company_id`, `criteria_1`, `criteria_2`, `criteria_3`, `criteria_4`, `criteria_5`, `criteria_6`, `criteria_7`, `criteria_8`, `criteria_9`, `criteria_10`, `suggestion`, `result_status`, `evaluation_date`) VALUES
(1, 2, 10, 1, 5, 4, 4, 5, 5, 5, 5, 4, 5, 5, '', 'pass', '2025-11-23 05:00:10'),
(2, 11, 10, 1, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 'ดีมาก มีความรับผิดชอบต่อหน้าที่', 'pass', '2025-11-23 05:09:59');

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `internship_requests`
--

CREATE TABLE `internship_requests` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `round_id` int(11) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `admin_notes` text DEFAULT NULL,
  `request_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `admin_comment` text DEFAULT NULL,
  `faculty_approval_status` enum('pending','approved','rejected') DEFAULT 'pending' COMMENT 'สถานะการพิจารณาจากคณะ',
  `faculty_approval_date` datetime DEFAULT NULL COMMENT 'วันที่คณะพิจารณา',
  `faculty_comment` text DEFAULT NULL COMMENT 'ความคิดเห็นจากคณะ',
  `company_response_status` enum('pending','accepted','rejected') DEFAULT 'pending' COMMENT 'สถานะการตอบรับจากบริษัท',
  `company_response_date` datetime DEFAULT NULL COMMENT 'วันที่บริษัทตอบกลับ',
  `company_response_comment` text DEFAULT NULL COMMENT 'ความคิดเห็นจากบริษัท',
  `document_response_status` enum('pending','submitted','approved','rejected') DEFAULT 'pending' COMMENT 'สถานะการตอบกลับเอกสาร',
  `document_response_date` datetime DEFAULT NULL COMMENT 'วันที่ตอบกลับเอกสาร',
  `document_files` text DEFAULT NULL COMMENT 'ไฟล์เอกสารที่แนบ (JSON array)',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'วันที่อัพเดทล่าสุด',
  `document_comment` text DEFAULT NULL COMMENT 'ความคิดเห็นตอบกลับเอกสาร'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `internship_requests`
--

INSERT INTO `internship_requests` (`id`, `student_id`, `company_id`, `round_id`, `status`, `admin_notes`, `request_date`, `start_date`, `end_date`, `admin_comment`, `faculty_approval_status`, `faculty_approval_date`, `faculty_comment`, `company_response_status`, `company_response_date`, `company_response_comment`, `document_response_status`, `document_response_date`, `document_files`, `updated_at`, `document_comment`) VALUES
(2, 2, 1, NULL, 'approved', NULL, '2025-11-21 12:24:23', NULL, NULL, NULL, 'approved', '2025-11-22 13:05:35', NULL, 'accepted', '2025-11-22 13:05:35', NULL, 'approved', '2025-11-22 13:05:35', NULL, '2025-11-22 06:05:35', 'fgfgffg'),
(4, 4, 7, NULL, 'approved', NULL, '2025-11-21 13:32:38', NULL, NULL, NULL, 'approved', '2025-11-22 13:05:35', NULL, 'accepted', '2025-11-22 13:05:35', NULL, 'approved', '2025-11-22 13:05:35', NULL, '2025-11-22 06:05:35', 'dddcvff'),
(5, 5, 8, NULL, 'approved', NULL, '2025-11-21 23:22:24', NULL, NULL, NULL, 'approved', '2025-11-22 13:05:35', NULL, 'accepted', '2025-11-22 13:05:35', NULL, 'approved', '2025-11-22 13:05:35', NULL, '2025-11-22 06:05:35', 'ดดเเอออ'),
(6, 6, 6, NULL, 'approved', NULL, '2025-11-22 03:19:21', NULL, NULL, NULL, 'approved', '2025-11-22 13:05:35', NULL, 'accepted', '2025-11-22 13:05:35', NULL, 'approved', '2025-11-22 13:05:35', NULL, '2025-11-22 06:05:35', 'เอกสารไม่ครบถ้วน ส่งมาเพิ่ม'),
(7, 8, 3, NULL, 'approved', NULL, '2025-11-22 04:05:46', NULL, NULL, NULL, 'approved', '2025-11-22 13:08:12', NULL, 'accepted', '2025-11-22 13:08:12', NULL, 'approved', '2025-11-22 13:08:12', NULL, '2025-11-22 06:08:12', NULL),
(8, 11, 1, NULL, 'approved', NULL, '2025-11-23 05:08:11', NULL, NULL, NULL, 'approved', '2025-11-23 12:08:31', NULL, 'accepted', '2025-11-23 12:08:31', NULL, 'approved', '2025-11-23 12:08:31', NULL, '2025-11-23 05:08:31', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `internship_rounds`
--

CREATE TABLE `internship_rounds` (
  `id` int(11) NOT NULL,
  `round_name` varchar(100) NOT NULL,
  `year` int(4) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `internship_rounds`
--

INSERT INTO `internship_rounds` (`id`, `round_name`, `year`, `start_date`, `end_date`, `is_active`, `created_at`) VALUES
(1, 'รอบที่ 1', 2024, '2024-06-01', '2024-08-31', 0, '2025-11-21 13:25:49'),
(2, 'รอบที่ 2', 2024, '2024-11-01', '2025-01-31', 0, '2025-11-21 13:25:49'),
(3, 'รอบที่ 1', 2025, '2025-06-01', '2025-08-31', 1, '2025-11-21 13:25:49'),
(4, 'รอบที่ 1/2567', 2024, '2024-06-01', '2024-08-31', 0, '2025-11-21 22:50:11'),
(5, 'รอบที่ 2/2567', 2024, '2024-11-01', '2025-01-31', 0, '2025-11-21 22:50:11'),
(6, 'รอบที่ 1/2568', 2025, '2025-06-01', '2025-08-31', 0, '2025-11-21 22:50:11');

-- --------------------------------------------------------

--
-- Table structure for table `majors`
--

CREATE TABLE `majors` (
  `id` varchar(10) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `majors`
--

INSERT INTO `majors` (`id`, `name`) VALUES
('AC', 'การบัญชี'),
('AC (EP)', 'การบัญชี (หลักสูตรภาษาอังกฤษ)'),
('BC', 'คอมพิวเตอร์ธุรกิจ'),
('BE', 'เศรษฐศาสตร์ธุรกิจ'),
('BIT', 'เทคโนโลยีสารสนเทศธุรกิจ'),
('ECOM', 'การจัดการพาณิชย์อิเล็กทรอนิกส์'),
('ENT', 'การจัดการการประกอบการ'),
('FM', 'การบริหารการเงิน'),
('GM', 'การจัดการ'),
('HRM', 'การจัดการทรัพยากรมนุษย์'),
('IB', 'ธุรกิจระหว่างประเทศ'),
('MK', 'การตลาด');

-- --------------------------------------------------------

--
-- Table structure for table `provinces`
--

CREATE TABLE `provinces` (
  `id` int(11) NOT NULL,
  `name_th` varchar(100) DEFAULT NULL,
  `region_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `provinces`
--

INSERT INTO `provinces` (`id`, `name_th`, `region_id`) VALUES
(1, 'กระบี่', 4),
(2, 'กรุงเทพมหานคร', 1),
(3, 'กาฬสินธุ์', 2),
(4, 'ขอนแก่น', 2),
(5, 'จันทบุรี', 5),
(6, 'ฉะเชิงเทรา', 5),
(7, 'ชลบุรี', 5),
(8, 'ชัยนาท', 1),
(9, 'ชัยภูมิ', 2),
(10, 'เชียงใหม่', 3),
(11, 'นครปฐม', 1),
(12, 'นครพนม', 2),
(13, 'นครราชสีมา', 2),
(14, 'นครศรีธรรมราช', 4),
(15, 'นนทบุรี', 1),
(16, 'บึงกาฬ', 2),
(17, 'บุรีรัมย์', 2),
(18, 'ปทุมธานี', 1),
(19, 'ปราจีนบุรี', 5),
(20, 'พระนครศรีอยุธยา', 1),
(21, 'พังงา', 4),
(22, 'พิษณุโลก', 3),
(23, 'ภูเก็ต', 4),
(24, 'มหาสารคาม', 2),
(25, 'มุกดาหาร', 2),
(26, 'ยโสธร', 2),
(27, 'ยะลา', 4),
(28, 'ร้อยเอ็ด', 2),
(29, 'ระยอง', 5),
(30, 'ลพบุรี', 1),
(31, 'ลำปาง', 3),
(32, 'เลย', 2),
(33, 'ศรีสะเกษ', 2),
(34, 'สกลนคร', 2),
(35, 'สงขลา', 4),
(36, 'สมุทรปราการ', 1),
(37, 'สมุทรสาคร', 1),
(38, 'สระบุรี', 1),
(39, 'สุพรรณบุรี', 1),
(40, 'สุราษฎร์ธานี', 4),
(41, 'สุรินทร์', 2),
(42, 'หนองคาย', 2),
(43, 'หนองบัวลำภู', 2),
(44, 'อ่างทอง', 1),
(45, 'อำนาจเจริญ', 2),
(46, 'อุดรธานี', 2),
(47, 'อุบลราชธานี', 2),
(48, 'ประจวบคีรีขันธ์', 6),
(49, 'นราธิวาส', 4),
(50, 'สตูล', 4),
(51, 'กาญจนบุรี', 6),
(52, 'กำแพงเพชร', 1),
(53, 'ชุมพร', 4),
(54, 'เชียงราย', 3),
(55, 'ตรัง', 4),
(56, 'ตราด', 5),
(57, 'ตาก', 6),
(58, 'นครนายก', 1),
(59, 'นครสวรรค์', 1),
(60, 'น่าน', 3),
(61, 'ปัตตานี', 4),
(62, 'พะเยา', 3),
(63, 'พัทลุง', 4),
(64, 'พิจิตร', 1),
(65, 'เพชรบุรี', 6),
(66, 'เพชรบูรณ์', 1),
(67, 'แพร่', 3),
(68, 'แม่ฮ่องสอน', 3),
(69, 'ระนอง', 4),
(70, 'ราชบุรี', 6),
(71, 'ลำพูน', 3),
(72, 'สมุทรสงคราม', 1),
(73, 'สระแก้ว', 5),
(74, 'สิงห์บุรี', 1),
(75, 'สุโขทัย', 1),
(76, 'อุตรดิตถ์', 3),
(77, 'อุทัยธานี', 1),
(78, 'ต่างประเทศ', 7);

-- --------------------------------------------------------

--
-- Table structure for table `regions`
--

CREATE TABLE `regions` (
  `id` int(11) NOT NULL,
  `name_en` varchar(50) DEFAULT NULL,
  `name_th` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `regions`
--

INSERT INTO `regions` (`id`, `name_en`, `name_th`) VALUES
(1, 'Central', 'ภาคกลาง'),
(2, 'Northeast', 'ภาคตะวันออกเฉียงเหนือ'),
(3, 'North', 'ภาคเหนือ'),
(4, 'South', 'ภาคใต้'),
(5, 'East', 'ภาคตะวันออก'),
(6, 'West', 'ภาคตะวันตก'),
(7, 'International', 'ต่างประเทศ');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `user_id` int(11) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `major` varchar(100) DEFAULT NULL,
  `year_level` int(11) DEFAULT NULL,
  `gpa` decimal(3,2) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `advisor_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`user_id`, `student_id`, `major`, `year_level`, `gpa`, `address`, `advisor_id`) VALUES
(2, '05785236450', 'mk', NULL, NULL, NULL, NULL),
(4, '55654221122', 'BIT', NULL, NULL, NULL, NULL),
(5, '225631223', 'AC', NULL, NULL, NULL, NULL),
(6, '5454554511', 'BC', NULL, NULL, NULL, NULL),
(7, '45456145631', 'AC (EP)', NULL, NULL, NULL, NULL),
(8, '5265645', 'BE', NULL, NULL, NULL, NULL),
(9, '12352200', 'IB', 3, 3.75, NULL, NULL),
(11, '99454614251', 'BC', NULL, NULL, NULL, NULL),
(12, '58010915682', 'BC', NULL, 3.55, NULL, NULL),
(13, '58010912669', 'BC', NULL, 3.25, NULL, NULL),
(14, '58010369520', 'AC', NULL, 2.45, NULL, NULL),
(15, '58010912668', 'MK', NULL, 2.95, NULL, NULL),
(138, '45645614', 'GM', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `time_sheets`
--

CREATE TABLE `time_sheets` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time_in` time DEFAULT NULL,
  `time_out` time DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `time_sheets`
--

INSERT INTO `time_sheets` (`id`, `student_id`, `date`, `time_in`, `time_out`, `notes`) VALUES
(1, 2, '2025-11-21', '19:41:00', '20:46:00', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','admin','employer','advisor') NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `full_name`, `email`, `phone`, `profile_image`, `created_at`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'System Administrator', NULL, NULL, NULL, '2025-11-21 07:57:27'),
(2, 'namimoto', '253939', 'student', 'test nana', NULL, NULL, NULL, '2025-11-21 08:26:32'),
(4, 'james', '$2y$10$2ieD8ukPNX4vp/i4U6ORle7OZIhy2UJkWXlI0aNE/kW4eYpokLSh2', 'student', 'ues33', NULL, NULL, NULL, '2025-11-21 13:32:24'),
(5, 'aa11', '$2y$10$EYRNfdm.AdD8VKd7yzcccukFc/yBoDwICrdYaj6rcRY8qmdkSLH7C', 'student', 'test55', NULL, NULL, NULL, '2025-11-21 23:22:07'),
(6, 'nato', '$2y$10$X6sG1wzl3oKnS0CvkHPWR.WGN5NwyFlQFH8PjUvncrpn1g056NjJa', 'student', 'ttes782', NULL, NULL, NULL, '2025-11-22 03:19:05'),
(7, 'ืืnn', '$2y$10$.5xbzY/4OApO1jodj5DdfOWIHoEgsc5Ny0W9Ai4NMbHxRJVYeAuu.', 'student', 'เจมะติ', NULL, NULL, NULL, '2025-11-22 04:04:46'),
(8, 'kk', '$2y$10$v/eKx022Z9IZiqFq.27DCunqRRNgzDHt.iXyvxzM/21GGdtGREKQ.', 'student', 'ดามิกา', NULL, NULL, NULL, '2025-11-22 04:05:35'),
(9, '12352200', '$2y$10$9COtJKeG4T64iwn17IzD9.5eNoLryXM2x6h9EjEDzMR8TZ9i9/L76', 'student', 'aa bb', NULL, NULL, NULL, '2025-11-22 13:14:05'),
(10, 'employer1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employer', 'บริษัท ตัวอย่าง จำกัด', 'hr@example.com', NULL, NULL, '2025-11-23 04:44:02'),
(11, 'james253939', '$2y$10$UyR2mdvaQlSXgQ1krv9/E.HMmdc3KE3QPxDRALJFMbIvX.nuT19S.', 'student', 'jaes22250', NULL, NULL, NULL, '2025-11-23 05:07:52'),
(12, '58010915682', '$2y$10$.wYQIxtGhBUNhofi75wbceeNjYuieSyYD3U9lCyUTWfJUTeb6b5jm', 'student', 'นาย จันดี มหาริพู', NULL, NULL, NULL, '2025-11-23 05:50:14'),
(13, '58010912669', '$2y$10$Pj1.3jD0KxNNhh2uvQ0rG.bzoXnsFc4iPLlN8AM8RlSdXiFqKkjBe', 'student', 'นางสาว ภานิดา จันดา', NULL, NULL, NULL, '2025-11-23 05:50:14'),
(14, '58010369520', '$2y$10$jTdthtJsYvvIGZCougJPauphZXJywqTMtGZr4gbEApKtqS.wGaEdS', 'student', 'นายอดิมหา สามรัยตา', NULL, NULL, NULL, '2025-11-23 05:50:14'),
(15, '58010912668', '$2y$10$W0q2bOMaEhYuSj8SjTm7LudECCiD4CtSuz2pwIdmRZs6DblOlcdWe', 'student', 'นางสาวดิราสิ วาระดี', NULL, NULL, NULL, '2025-11-23 05:50:14'),
(16, 'comp_9b470', '$2y$10$UNDPdcAtJnAsAvn0YY8a5.5IN/vJHjPaZE07FNgrFLSuDk6wYKqp6', 'employer', 'ddf', NULL, NULL, NULL, '2025-11-23 08:17:25'),
(17, 'comp_a8e05', '$2y$10$lgebKyWi39D1849fo5mrRObVNH8pFIHxyxnFON9q24n38on/xdZwW', 'employer', 'บริษัท ปตท. จำกัด (มหาชน)', NULL, NULL, NULL, '2025-11-23 08:24:25'),
(18, 'advisor_gm_1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สมชาย (GM)', 'advisor.gm.1@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(19, 'advisor_gm_2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วิไล (GM)', 'advisor.gm.2@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(20, 'advisor_gm_3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สุชาติ (GM)', 'advisor.gm.3@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(21, 'advisor_gm_4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.กานดา (GM)', 'advisor.gm.4@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(22, 'advisor_gm_5', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ณัฐพล (GM)', 'advisor.gm.5@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(23, 'advisor_gm_6', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ปวีณา (GM)', 'advisor.gm.6@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(24, 'advisor_gm_7', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ธนพล (GM)', 'advisor.gm.7@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(25, 'advisor_gm_8', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.นภา (GM)', 'advisor.gm.8@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(26, 'advisor_gm_9', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ชัย (GM)', 'advisor.gm.9@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(27, 'advisor_gm_10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ชลธิชา (GM)', 'advisor.gm.10@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(28, 'advisor_ent_1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.กิตติ (ENT)', 'advisor.ent.1@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(29, 'advisor_ent_2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.มณี (ENT)', 'advisor.ent.2@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(30, 'advisor_ent_3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วิชัย (ENT)', 'advisor.ent.3@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(31, 'advisor_ent_4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.รัตนา (ENT)', 'advisor.ent.4@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(32, 'advisor_ent_5', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สมศักดิ์ (ENT)', 'advisor.ent.5@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(33, 'advisor_ent_6', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.อารี (ENT)', 'advisor.ent.6@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(34, 'advisor_ent_7', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.บุญมี (ENT)', 'advisor.ent.7@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(35, 'advisor_ent_8', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.จินตนา (ENT)', 'advisor.ent.8@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(36, 'advisor_ent_9', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ประดิษฐ์ (ENT)', 'advisor.ent.9@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(37, 'advisor_ent_10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วรรณา (ENT)', 'advisor.ent.10@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(38, 'advisor_hrm_1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สมปอง (HRM)', 'advisor.hrm.1@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(39, 'advisor_hrm_2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สมศรี (HRM)', 'advisor.hrm.2@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(40, 'advisor_hrm_3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สมบัติ (HRM)', 'advisor.hrm.3@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(41, 'advisor_hrm_4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สมใจ (HRM)', 'advisor.hrm.4@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(42, 'advisor_hrm_5', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สมหมาย (HRM)', 'advisor.hrm.5@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(43, 'advisor_hrm_6', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สมหวัง (HRM)', 'advisor.hrm.6@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(44, 'advisor_hrm_7', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สมนึก (HRM)', 'advisor.hrm.7@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(45, 'advisor_hrm_8', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สมพร (HRM)', 'advisor.hrm.8@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(46, 'advisor_hrm_9', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สมยศ (HRM)', 'advisor.hrm.9@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(47, 'advisor_hrm_10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สมเกียรติ (HRM)', 'advisor.hrm.10@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(48, 'advisor_ecom_1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วิชัย (ECOM)', 'advisor.ecom.1@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(49, 'advisor_ecom_2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วิยะดา (ECOM)', 'advisor.ecom.2@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(50, 'advisor_ecom_3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วิโรจน์ (ECOM)', 'advisor.ecom.3@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(51, 'advisor_ecom_4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วิภา (ECOM)', 'advisor.ecom.4@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(52, 'advisor_ecom_5', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วิทวัส (ECOM)', 'advisor.ecom.5@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(53, 'advisor_ecom_6', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วิมล (ECOM)', 'advisor.ecom.6@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(54, 'advisor_ecom_7', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วิศิษฐ์ (ECOM)', 'advisor.ecom.7@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(55, 'advisor_ecom_8', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วิไลวรรณ (ECOM)', 'advisor.ecom.8@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(56, 'advisor_ecom_9', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วิชาญ (ECOM)', 'advisor.ecom.9@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(57, 'advisor_ecom_10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วิชิต (ECOM)', 'advisor.ecom.10@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(58, 'advisor_mk_1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ประเสริฐ (MK)', 'advisor.mk.1@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(59, 'advisor_mk_2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ปราณี (MK)', 'advisor.mk.2@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(60, 'advisor_mk_3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ประวิทย์ (MK)', 'advisor.mk.3@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(61, 'advisor_mk_4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ประไพ (MK)', 'advisor.mk.4@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(62, 'advisor_mk_5', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ประยุทธ์ (MK)', 'advisor.mk.5@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(63, 'advisor_mk_6', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ประนอม (MK)', 'advisor.mk.6@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(64, 'advisor_mk_7', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ประสิทธิ์ (MK)', 'advisor.mk.7@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(65, 'advisor_mk_8', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ประภา (MK)', 'advisor.mk.8@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(66, 'advisor_mk_9', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ประจักษ์ (MK)', 'advisor.mk.9@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(67, 'advisor_mk_10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ประทีป (MK)', 'advisor.mk.10@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(68, 'advisor_fm_1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สุรพล (FM)', 'advisor.fm.1@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(69, 'advisor_fm_2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สุรีย์ (FM)', 'advisor.fm.2@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(70, 'advisor_fm_3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สุรชัย (FM)', 'advisor.fm.3@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(71, 'advisor_fm_4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สุภา (FM)', 'advisor.fm.4@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(72, 'advisor_fm_5', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สุรินทร์ (FM)', 'advisor.fm.5@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(73, 'advisor_fm_6', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สุนิสา (FM)', 'advisor.fm.6@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(74, 'advisor_fm_7', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สุริยา (FM)', 'advisor.fm.7@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(75, 'advisor_fm_8', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สุวรรณา (FM)', 'advisor.fm.8@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(76, 'advisor_fm_9', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สุรศักดิ์ (FM)', 'advisor.fm.9@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(77, 'advisor_fm_10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.สุจินดา (FM)', 'advisor.fm.10@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(78, 'advisor_ac_1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.อำนาจ (AC)', 'advisor.ac.1@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(79, 'advisor_ac_2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.อำไพ (AC)', 'advisor.ac.2@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(80, 'advisor_ac_3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.อำนวย (AC)', 'advisor.ac.3@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(81, 'advisor_ac_4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.อำภา (AC)', 'advisor.ac.4@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(82, 'advisor_ac_5', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.อำพล (AC)', 'advisor.ac.5@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(83, 'advisor_ac_6', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.อุไร (AC)', 'advisor.ac.6@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(84, 'advisor_ac_7', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.อุดม (AC)', 'advisor.ac.7@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(85, 'advisor_ac_8', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.อุษา (AC)', 'advisor.ac.8@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(86, 'advisor_ac_9', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.อุทัย (AC)', 'advisor.ac.9@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(87, 'advisor_ac_10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.อุบล (AC)', 'advisor.ac.10@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(88, 'advisor_acep_1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'Aj. John (AC-EP)', 'advisor.acep.1@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(89, 'advisor_acep_2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'Aj. Mary (AC-EP)', 'advisor.acep.2@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(90, 'advisor_acep_3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'Aj. David (AC-EP)', 'advisor.acep.3@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(91, 'advisor_acep_4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'Aj. Sarah (AC-EP)', 'advisor.acep.4@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(92, 'advisor_acep_5', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'Aj. Michael (AC-EP)', 'advisor.acep.5@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(93, 'advisor_acep_6', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'Aj. Jennifer (AC-EP)', 'advisor.acep.6@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(94, 'advisor_acep_7', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'Aj. Robert (AC-EP)', 'advisor.acep.7@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(95, 'advisor_acep_8', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'Aj. Linda (AC-EP)', 'advisor.acep.8@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(96, 'advisor_acep_9', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'Aj. William (AC-EP)', 'advisor.acep.9@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(97, 'advisor_acep_10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'Aj. Elizabeth (AC-EP)', 'advisor.acep.10@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(98, 'advisor_bc_1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ชัยวัฒน์ (BC)', 'advisor.bc.1@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(99, 'advisor_bc_2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ชไมพร (BC)', 'advisor.bc.2@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(100, 'advisor_bc_3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ชัยณรงค์ (BC)', 'advisor.bc.3@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(101, 'advisor_bc_4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ชมพู่ (BC)', 'advisor.bc.4@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(102, 'advisor_bc_5', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ชัยสิทธิ์ (BC)', 'advisor.bc.5@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(103, 'advisor_bc_6', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ชลดา (BC)', 'advisor.bc.6@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(104, 'advisor_bc_7', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ชัยยศ (BC)', 'advisor.bc.7@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(105, 'advisor_bc_8', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ช่อทิพย์ (BC)', 'advisor.bc.8@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(106, 'advisor_bc_9', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ชัยพร (BC)', 'advisor.bc.9@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(107, 'advisor_bc_10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ชุติมา (BC)', 'advisor.bc.10@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(108, 'advisor_bit_1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ธนากร (BIT)', 'advisor.bit.1@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(109, 'advisor_bit_2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ธนัชชา (BIT)', 'advisor.bit.2@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(110, 'advisor_bit_3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ธนินทร์ (BIT)', 'advisor.bit.3@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(111, 'advisor_bit_4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ธนภรณ์ (BIT)', 'advisor.bit.4@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(112, 'advisor_bit_5', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ธนวัฒน์ (BIT)', 'advisor.bit.5@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(113, 'advisor_bit_6', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ธนวรรณ (BIT)', 'advisor.bit.6@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(114, 'advisor_bit_7', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ธนดล (BIT)', 'advisor.bit.7@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(115, 'advisor_bit_8', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ธนารีย์ (BIT)', 'advisor.bit.8@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(116, 'advisor_bit_9', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ธนกฤต (BIT)', 'advisor.bit.9@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(117, 'advisor_bit_10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.ธนิกา (BIT)', 'advisor.bit.10@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(118, 'advisor_ib_1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.นพดล (IB)', 'advisor.ib.1@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(119, 'advisor_ib_2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.นพวรรณ (IB)', 'advisor.ib.2@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(120, 'advisor_ib_3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.นพรัตน์ (IB)', 'advisor.ib.3@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(121, 'advisor_ib_4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.นพมาศ (IB)', 'advisor.ib.4@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(122, 'advisor_ib_5', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.นพสิทธิ์ (IB)', 'advisor.ib.5@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(123, 'advisor_ib_6', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.นพเก้า (IB)', 'advisor.ib.6@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(124, 'advisor_ib_7', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.นพพล (IB)', 'advisor.ib.7@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(125, 'advisor_ib_8', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.นพดา (IB)', 'advisor.ib.8@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(126, 'advisor_ib_9', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.นพคุณ (IB)', 'advisor.ib.9@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(127, 'advisor_ib_10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.นพสร (IB)', 'advisor.ib.10@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(128, 'advisor_be_1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วรพล (BE)', 'advisor.be.1@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(129, 'advisor_be_2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วรวรรณ (BE)', 'advisor.be.2@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(130, 'advisor_be_3', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วรวิทย์ (BE)', 'advisor.be.3@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(131, 'advisor_be_4', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วรภา (BE)', 'advisor.be.4@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(132, 'advisor_be_5', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วรพจน์ (BE)', 'advisor.be.5@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(133, 'advisor_be_6', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วรนุช (BE)', 'advisor.be.6@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(134, 'advisor_be_7', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วรยศ (BE)', 'advisor.be.7@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(135, 'advisor_be_8', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วรดา (BE)', 'advisor.be.8@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(136, 'advisor_be_9', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วรชาติ (BE)', 'advisor.be.9@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(137, 'advisor_be_10', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'advisor', 'อ.วรินทร (BE)', 'advisor.be.10@uni.ac.th', NULL, NULL, '2025-11-23 13:05:22'),
(138, 'eakkanit', '$2y$10$CeiI/tLNvNoGi1XeQdlNZ.dN7mDI8fZmfFN/m8gxiL1owD99cwVbm', 'student', 'เอกนิท สามารถพยุน', NULL, NULL, NULL, '2025-11-23 14:13:09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `advisors`
--
ALTER TABLE `advisors`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `major_id` (`major_id`);

--
-- Indexes for table `advisor_messages`
--
ALTER TABLE `advisor_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `advisor_id` (`advisor_id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `fk_company_employer` (`employer_user_id`),
  ADD KEY `fk_companies_user` (`user_id`);

--
-- Indexes for table `daily_logs`
--
ALTER TABLE `daily_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `evaluations`
--
ALTER TABLE `evaluations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `employer_id` (`employer_id`),
  ADD KEY `company_id` (`company_id`);

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `company_id` (`company_id`);

--
-- Indexes for table `internship_requests`
--
ALTER TABLE `internship_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `company_id` (`company_id`);

--
-- Indexes for table `internship_rounds`
--
ALTER TABLE `internship_rounds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `majors`
--
ALTER TABLE `majors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `provinces`
--
ALTER TABLE `provinces`
  ADD PRIMARY KEY (`id`),
  ADD KEY `region_id` (`region_id`);

--
-- Indexes for table `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `student_id` (`student_id`),
  ADD KEY `advisor_id` (`advisor_id`);

--
-- Indexes for table `time_sheets`
--
ALTER TABLE `time_sheets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `advisor_messages`
--
ALTER TABLE `advisor_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `daily_logs`
--
ALTER TABLE `daily_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `internship_requests`
--
ALTER TABLE `internship_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `internship_rounds`
--
ALTER TABLE `internship_rounds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `time_sheets`
--
ALTER TABLE `time_sheets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `advisors`
--
ALTER TABLE `advisors`
  ADD CONSTRAINT `advisors_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `advisors_ibfk_2` FOREIGN KEY (`major_id`) REFERENCES `majors` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `advisor_messages`
--
ALTER TABLE `advisor_messages`
  ADD CONSTRAINT `advisor_messages_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `advisor_messages_ibfk_2` FOREIGN KEY (`advisor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `companies`
--
ALTER TABLE `companies`
  ADD CONSTRAINT `companies_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_companies_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_company_employer` FOREIGN KEY (`employer_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `daily_logs`
--
ALTER TABLE `daily_logs`
  ADD CONSTRAINT `daily_logs_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `evaluations`
--
ALTER TABLE `evaluations`
  ADD CONSTRAINT `evaluations_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `evaluations_ibfk_2` FOREIGN KEY (`employer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `evaluations_ibfk_3` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD CONSTRAINT `feedbacks_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `feedbacks_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `internship_requests`
--
ALTER TABLE `internship_requests`
  ADD CONSTRAINT `internship_requests_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `internship_requests_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `provinces`
--
ALTER TABLE `provinces`
  ADD CONSTRAINT `provinces_ibfk_1` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `students_ibfk_2` FOREIGN KEY (`advisor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `time_sheets`
--
ALTER TABLE `time_sheets`
  ADD CONSTRAINT `time_sheets_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
