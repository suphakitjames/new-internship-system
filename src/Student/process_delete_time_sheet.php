<?php
// src/Student/process_delete_time_sheet.php

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

if (empty($time_id)) {
    $_SESSION['error'] = 'ไม่พบรหัสบันทึก';
    header('Location: index.php?page=student&action=time_sheet');
    exit;
}

try {
    // Verify ownership and delete
    $stmt = $pdo->prepare("DELETE FROM time_sheets WHERE id = ? AND student_id = ?");
    $stmt->execute([$time_id, $user_id]);
    
    if ($stmt->rowCount() > 0) {
        $_SESSION['success'] = 'ลบบันทึกเวลาเรียบร้อยแล้ว';
    } else {
        $_SESSION['error'] = 'ไม่พบบันทึกนี้หรือคุณไม่มีสิทธิ์ลบ';
    }
} catch (Exception $e) {
    $_SESSION['error'] = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
}

header('Location: index.php?page=student&action=time_sheet');
exit;
