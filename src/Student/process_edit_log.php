<?php
// src/Student/process_edit_log.php

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
$log_date = $_POST['log_date'] ?? '';
$work_description = trim($_POST['work_description'] ?? '');
$problems = trim($_POST['problems'] ?? '');

if (empty($log_id) || empty($log_date) || empty($work_description)) {
    $_SESSION['error'] = 'กรุณากรอกข้อมูลให้ครบถ้วน';
    header('Location: index.php?page=student&action=daily_log');
    exit;
}

try {
    // Verify ownership
    $stmt = $pdo->prepare("SELECT id FROM daily_logs WHERE id = ? AND student_id = ?");
    $stmt->execute([$log_id, $user_id]);
    
    if (!$stmt->fetch()) {
        $_SESSION['error'] = 'ไม่พบบันทึกนี้หรือคุณไม่มีสิทธิ์แก้ไข';
        header('Location: index.php?page=student&action=daily_log');
        exit;
    }
    
    // Update log
    $stmt = $pdo->prepare("
        UPDATE daily_logs 
        SET log_date = ?, work_description = ?, problems = ? 
        WHERE id = ? AND student_id = ?
    ");
    $stmt->execute([$log_date, $work_description, $problems, $log_id, $user_id]);
    
    $_SESSION['success'] = 'แก้ไขบันทึกเรียบร้อยแล้ว';
} catch (Exception $e) {
    $_SESSION['error'] = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
}

header('Location: index.php?page=student&action=daily_log');
exit;
