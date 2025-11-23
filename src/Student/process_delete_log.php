<?php
// src/Student/process_delete_log.php

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header('Location: index.php?page=auth&action=login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php?page=student&action=daily_log');
    exit;
}

$user_id = $_SESSION['user']['id'];
$log_id = $_POST['log_id'] ?? '';

if (empty($log_id)) {
    $_SESSION['error'] = 'ไม่พบรหัสบันทึก';
    header('Location: index.php?page=student&action=daily_log');
    exit;
}

try {
    // Verify ownership and delete
    $stmt = $pdo->prepare("DELETE FROM daily_logs WHERE id = ? AND student_id = ?");
    $stmt->execute([$log_id, $user_id]);
    
    if ($stmt->rowCount() > 0) {
        $_SESSION['success'] = 'ลบบันทึกเรียบร้อยแล้ว';
    } else {
        $_SESSION['error'] = 'ไม่พบบันทึกนี้หรือคุณไม่มีสิทธิ์ลบ';
    }
} catch (Exception $e) {
    $_SESSION['error'] = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
}

header('Location: index.php?page=student&action=daily_log');
exit;
