<?php
// src/Admin/process_import.php

// Ensure admin is logged in
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php?page=auth&action=login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php?page=admin&action=student_import');
    exit;
}

// CSRF Check
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die('Invalid CSRF Token');
}

if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
    header('Location: index.php?page=admin&action=student_import&status=error&message=กรุณาอัปโหลดไฟล์ให้ถูกต้อง');
    exit;
}

$file = $_FILES['csv_file']['tmp_name'];

// Security: Validate MIME Type
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $file);
finfo_close($finfo);

$allowedMimeTypes = [
    'text/plain',
    'text/csv',
    'application/vnd.ms-excel',
    'text/x-csv',
    'application/csv',
    'application/x-csv',
    'text/comma-separated-values',
    'text/x-comma-separated-values'
];

if (!in_array($mimeType, $allowedMimeTypes)) {
    header('Location: index.php?page=admin&action=student_import&status=error&message=รูปแบบไฟล์ไม่ถูกต้อง (ต้องเป็น CSV เท่านั้น)');
    exit;
}

$handle = fopen($file, "r");

if ($handle === FALSE) {
    header('Location: index.php?page=admin&action=student_import&status=error&message=ไม่สามารถเปิดไฟล์ได้');
    exit;
}

$added = 0;
$updated = 0;
$skipped = 0;

// Prepare statements
$checkUserStmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
$insertUserStmt = $pdo->prepare("INSERT INTO users (username, password, role, full_name) VALUES (?, ?, 'student', ?)");
$updateUserStmt = $pdo->prepare("UPDATE users SET full_name = ? WHERE id = ?");

$checkStudentStmt = $pdo->prepare("SELECT user_id FROM students WHERE student_id = ?");
$insertStudentStmt = $pdo->prepare("INSERT INTO students (user_id, student_id, major, gpa) VALUES (?, ?, ?, ?)");
$updateStudentStmt = $pdo->prepare("UPDATE students SET major = ?, gpa = ? WHERE user_id = ?");

// Map Major Codes to IDs (Simple mapping for now, can be expanded)
// In a real scenario, we might query the majors table to get IDs
$majorMap = [
    'CS' => 'CS',
    'วิทยาการคอมพิวเตอร์' => 'CS',
    'IT' => 'IT',
    'เทคโนโลยีสารสนเทศ' => 'IT',
    'CE' => 'CE',
    'วิศวกรรมคอมพิวเตอร์' => 'CE',
    'EE' => 'EE',
    'วิศวกรรมไฟฟ้า' => 'EE',
    'ME' => 'ME',
    'วิศวกรรมเครื่องกล' => 'ME',
    'BA' => 'BA',
    'บริหารธุรกิจ' => 'BA',
    'ACC' => 'ACC',
    'การบัญชี' => 'ACC',
    'MKT' => 'MKT',
    'การตลาด' => 'MKT',
    'ECON' => 'ECON',
    'เศรษฐศาสตร์' => 'ECON',
    'COMM' => 'COMM',
    'นิเทศศาสตร์' => 'COMM',
    'BC' => 'BC',
    'คอมพิวเตอร์ธุรกิจ' => 'BC' // Added based on user image example
];

while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    // Expected format: Student ID, Name, Major, GPA
    // Skip empty rows or rows with insufficient data
    if (count($data) < 2) continue;

    // Clean data
    // Remove BOM if present in first cell
    $student_id = trim(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $data[0]));
    $full_name = trim($data[1]);
    $major_input = isset($data[2]) ? trim($data[2]) : '';
    $gpa = isset($data[3]) ? floatval($data[3]) : 0.00;

    // Skip header row if detected (simple check: if student_id is not numeric)
    if (!is_numeric($student_id)) {
        continue;
    }

    // Resolve Major ID
    $major_id = $majorMap[$major_input] ?? $major_input; // Fallback to input if not mapped

    try {
        $pdo->beginTransaction();

        // 1. Check/Create User
        $checkUserStmt->execute([$student_id]);
        $user = $checkUserStmt->fetch();

        $user_id = null;

        if ($user) {
            // User exists, update name
            $user_id = $user['id'];
            $updateUserStmt->execute([$full_name, $user_id]);
        } else {
            // Create new user
            // Default password is student_id
            $password = password_hash($student_id, PASSWORD_DEFAULT);
            $insertUserStmt->execute([$student_id, $password, $full_name]);
            $user_id = $pdo->lastInsertId();
        }

        // 2. Check/Create Student Profile
        $checkStudentStmt->execute([$student_id]);
        $student = $checkStudentStmt->fetch();

        if ($student) {
            // Student profile exists, update info
            $updateStudentStmt->execute([$major_id, $gpa, $user_id]);
            $updated++;
        } else {
            // Create new student profile
            $insertStudentStmt->execute([$user_id, $student_id, $major_id, $gpa]);
            $added++;
        }

        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        $skipped++;
        // Ideally log the error
    }
}

fclose($handle);

header("Location: index.php?page=admin&action=student_import&status=success&added=$added&updated=$updated&skipped=$skipped");
exit;
