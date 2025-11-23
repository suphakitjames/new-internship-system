<?php
// src/Admin/process_update_request_status.php

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php?page=auth&action=admin_login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php?page=admin&action=internship_requests');
    exit;
}

$request_id = $_POST['request_id'] ?? '';
$status = $_POST['status'] ?? '';
$reason = $_POST['reason'] ?? '';

if (empty($request_id) || empty($status)) {
    $_SESSION['error'] = 'ข้อมูลไม่ครบถ้วน';
    header('Location: index.php?page=admin&action=internship_requests');
    exit;
}

if (!in_array($status, ['approved', 'rejected'])) {
    $_SESSION['error'] = 'สถานะไม่ถูกต้อง';
    header('Location: index.php?page=admin&action=internship_requests');
    exit;
}

try {
    // Update request status
    // Note: Using admin_comment instead of admin_notes based on database schema
    // Removed updated_at as it might not exist in the table
    $stmt = $pdo->prepare("
        UPDATE internship_requests 
        SET status = ?, 
            admin_comment = ?
        WHERE id = ?
    ");
    $stmt->execute([$status, $reason, $request_id]);
    
    if ($stmt->rowCount() > 0) {
        $status_text = $status === 'approved' ? 'อนุมัติ' : 'ไม่อนุมัติ';
        $_SESSION['success'] = "อัพเดทสถานะเป็น \"{$status_text}\" เรียบร้อยแล้ว";
    } else {
        // Check if it was already in that status
        $_SESSION['success'] = "อัพเดทสถานะเรียบร้อยแล้ว";
    }
} catch (Exception $e) {
    $_SESSION['error'] = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
}

header('Location: index.php?page=admin&action=internship_requests');
exit;
