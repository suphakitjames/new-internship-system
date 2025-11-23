<?php
// src/Student/process_change_password.php

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header('Location: index.php?page=auth&action=login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php?page=student&action=change_password');
    exit;
}

$user_id = $_SESSION['user']['id'];
$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Validation
if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
    $_SESSION['error'] = 'กรุณากรอกข้อมูลให้ครบถ้วน';
    header('Location: index.php?page=student&action=change_password');
    exit;
}

if (strlen($new_password) < 6) {
    $_SESSION['error'] = 'รหัสผ่านใหม่ต้องมีอย่างน้อย 6 ตัวอักษร';
    header('Location: index.php?page=student&action=change_password');
    exit;
}

if ($new_password !== $confirm_password) {
    $_SESSION['error'] = 'รหัสผ่านใหม่และการยืนยันรหัสผ่านไม่ตรงกัน';
    header('Location: index.php?page=student&action=change_password');
    exit;
}

// Verify current password
$stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user || !password_verify($current_password, $user['password'])) {
    $_SESSION['error'] = 'รหัสผ่านปัจจุบันไม่ถูกต้อง';
    header('Location: index.php?page=student&action=change_password');
    exit;
}

// Update password
try {
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->execute([$hashed_password, $user_id]);
    
    $_SESSION['success'] = 'เปลี่ยนรหัสผ่านเรียบร้อยแล้ว';
    header('Location: index.php?page=student&action=change_password');
} catch (Exception $e) {
    $_SESSION['error'] = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
    header('Location: index.php?page=student&action=change_password');
}
exit;
