<?php
// src/Admin/get_company_students.php
header('Content-Type: application/json');
require_once '../../config/database.php';

// Debug: เก็บ log
$debug_info = [
    'company_id' => $_GET['company_id'] ?? 'NOT_SET',
    'round_id' => $_GET['round_id'] ?? 'NOT_SET'
];

if (!isset($_GET['company_id']) || !isset($_GET['round_id'])) {
    echo json_encode(['error' => 'Missing parameters', 'debug' => $debug_info]);
    exit;
}

try {
    $company_id = $_GET['company_id'];
    $round_id = $_GET['round_id'];

    // ดึงข้อมูลนิสิตในบริษัทนี้
    $stmt = $pdo->prepare("
        SELECT 
            ir.id as request_id,
            COALESCE(u.full_name, 'ไม่ระบุ') as full_name,
            COALESCE(s.student_id, ir.student_id) as std_code,
            'ไม่ระบุ' as major_name,
            ir.status,
            ir.company_id,
            ir.round_id
        FROM internship_requests ir
        LEFT JOIN students s ON ir.student_id = s.user_id
        LEFT JOIN users u ON s.user_id = u.id
        WHERE ir.company_id = ?
        ORDER BY s.student_id ASC
    ");
    
    $stmt->execute([$company_id]);
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ส่งข้อมูลกลับไป (ไม่ต้องมี debug info ถ้าเจอข้อมูล)
    echo json_encode($students);

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage(), 'debug' => $debug_info]);
}
