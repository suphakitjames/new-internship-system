<?php
// src/Admin/get_request_details.php

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$request_id = $_GET['id'] ?? '';

if (empty($request_id)) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing request ID']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT 
            ir.id,
            ir.student_id,
            ir.company_id,
            ir.status,
            ir.request_date,
            ir.start_date,
            ir.end_date,
            ir.admin_comment,
            ir.faculty_approval_status,
            ir.faculty_approval_date,
            ir.faculty_comment,
            ir.company_response_status,
            ir.company_response_date,
            ir.company_response_comment,
            ir.document_response_status,
            ir.document_response_date,
            u.full_name,
            u.email,
            u.phone,
            s.student_id as student_code,
            s.major,
            s.year_level,
            s.gpa,
            c.name as company_name,
            c.address as company_address,
            c.province as province_name
        FROM internship_requests ir
        JOIN users u ON ir.student_id = u.id
        JOIN students s ON u.id = s.user_id
        JOIN companies c ON ir.company_id = c.id
        WHERE ir.id = ?
    ");
    $stmt->execute([$request_id]);
    $request = $stmt->fetch();
    
    if (!$request) {
        http_response_code(404);
        echo json_encode(['error' => 'Request not found']);
        exit;
    }
    
    // Add round info if available (handling missing table gracefully)
    try {
        if (!empty($request['round_id'])) {
            $round_stmt = $pdo->prepare("SELECT round_name, year FROM internship_rounds WHERE id = ?");
            $round_stmt->execute([$request['round_id']]);
            $round = $round_stmt->fetch();
            if ($round) {
                $request['round_name'] = $round['round_name'];
                $request['year'] = $round['year'];
            }
        }
    } catch (Exception $e) {
        // Ignore if rounds table doesn't exist or other error
    }
    
    header('Content-Type: application/json');
    echo json_encode($request);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
exit;
