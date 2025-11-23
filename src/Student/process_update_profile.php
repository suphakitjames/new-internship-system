<?php
// src/Student/process_update_profile.php

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header('Location: index.php?page=auth&action=login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php?page=student&action=profile');
    exit;
}

$user_id = $_SESSION['user']['id'];
$full_name = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');
$major = trim($_POST['major'] ?? '');
$year_level = $_POST['year_level'] ?? null;
$gpa = $_POST['gpa'] ?? null;

if (empty($full_name)) {
    $_SESSION['error'] = 'กรุณากรอกชื่อ-นามสกุล';
    header('Location: index.php?page=student&action=profile');
    exit;
}

try {
    $pdo->beginTransaction();
    
    // Update users table
    $stmt = $pdo->prepare("
        UPDATE users 
        SET full_name = ?, email = ?, phone = ? 
        WHERE id = ?
    ");
    $stmt->execute([$full_name, $email, $phone, $user_id]);
    
    // Update students table
    $stmt = $pdo->prepare("
        UPDATE students 
        SET major = ?, year_level = ?, gpa = ?, address = ? 
        WHERE user_id = ?
    ");
    $stmt->execute([$major, $year_level, $gpa, $address, $user_id]);
    
    // Update session
    $_SESSION['user']['full_name'] = $full_name;
    
    $pdo->commit();
    
    $_SESSION['success'] = 'บันทึกข้อมูลเรียบร้อยแล้ว';
} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['error'] = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
}

header('Location: index.php?page=student&action=profile');
exit;
