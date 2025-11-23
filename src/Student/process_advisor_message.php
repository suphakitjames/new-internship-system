<?php
// src/Student/process_advisor_message.php

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header('Location: index.php?page=auth&action=login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php?page=student&action=advisor_message');
    exit;
}

$user_id = $_SESSION['user']['id'];
$message = trim($_POST['message'] ?? '');
$advisor_id = $_POST['advisor_id'] ?? null;

if (empty($message)) {
    $_SESSION['error'] = 'กรุณากรอกข้อความ';
    header('Location: index.php?page=student&action=advisor_message');
    exit;
}

if (empty($advisor_id)) {
    $_SESSION['error'] = 'กรุณาเลือกอาจารย์ที่ปรึกษา';
    header('Location: index.php?page=student&action=advisor_message');
    exit;
}

try {
    // Verify advisor exists and is an advisor
    $stmt = $pdo->prepare("SELECT id FROM users WHERE id = ? AND role = 'advisor'");
    $stmt->execute([$advisor_id]);
    if (!$stmt->fetch()) {
        throw new Exception('ไม่พบข้อมูลอาจารย์ที่ปรึกษา');
    }

    $stmt = $pdo->prepare("
        INSERT INTO advisor_messages (student_id, advisor_id, message, is_read) 
        VALUES (?, ?, ?, FALSE)
    ");
    $stmt->execute([$user_id, $advisor_id, $message]);

    $_SESSION['success'] = 'ส่งข้อความเรียบร้อยแล้ว';
} catch (Exception $e) {
    $_SESSION['error'] = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
}

header('Location: index.php?page=student&action=advisor_message');
exit;
