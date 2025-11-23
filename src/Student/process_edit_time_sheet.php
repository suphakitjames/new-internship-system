<?php
// src/Student/process_edit_time_sheet.php

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header('Location: index.php?page=auth&action=login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php?page=student&action=time_sheet');
    exit;
}

$user_id = $_SESSION['user']['id'];
$time_id = $_POST['time_id'] ?? '';
$date = $_POST['date'] ?? '';
$time_in = $_POST['time_in'] ?? '';
$time_out = $_POST['time_out'] ?? '';
$notes = trim($_POST['notes'] ?? '');

if (empty($time_id) || empty($date) || empty($time_in) || empty($time_out)) {
    $_SESSION['error'] = 'กรุณากรอกข้อมูลให้ครบถ้วน';
    header('Location: index.php?page=student&action=time_sheet');
    exit;
}

try {
    // Verify ownership
    $stmt = $pdo->prepare("SELECT id FROM time_sheets WHERE id = ? AND student_id = ?");
    $stmt->execute([$time_id, $user_id]);
    
    if (!$stmt->fetch()) {
        $_SESSION['error'] = 'ไม่พบบันทึกนี้หรือคุณไม่มีสิทธิ์แก้ไข';
        header('Location: index.php?page=student&action=time_sheet');
        exit;
    }
    
    // Combine date and time
    $time_in_full = $date . ' ' . $time_in . ':00';
    $time_out_full = $date . ' ' . $time_out . ':00';
    
    // Update time sheet
    $stmt = $pdo->prepare("
        UPDATE time_sheets 
        SET date = ?, time_in = ?, time_out = ?, notes = ? 
        WHERE id = ? AND student_id = ?
    ");
    $stmt->execute([$date, $time_in_full, $time_out_full, $notes, $time_id, $user_id]);
    
    $_SESSION['success'] = 'แก้ไขบันทึกเวลาเรียบร้อยแล้ว';
} catch (Exception $e) {
    $_SESSION['error'] = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
}

header('Location: index.php?page=student&action=time_sheet');
exit;
