<?php
// src/Auth/process_register.php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php?page=auth&action=register');
    exit;
}

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$full_name = $_POST['full_name'] ?? '';
$student_id = $_POST['student_id'] ?? '';
$major = $_POST['major'] ?? '';

// Basic Validation
if (empty($username) || empty($password) || empty($full_name) || empty($student_id)) {
    $_SESSION['error'] = 'กรุณากรอกข้อมูลให้ครบถ้วน';
    header('Location: index.php?page=auth&action=register');
    exit;
}

if ($password !== $confirm_password) {
    $_SESSION['error'] = 'รหัสผ่านไม่ตรงกัน';
    header('Location: index.php?page=auth&action=register');
    exit;
}

// Check if username exists
$stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
$stmt->execute([$username]);
if ($stmt->fetch()) {
    $_SESSION['error'] = 'ชื่อผู้ใช้งานนี้ถูกใช้ไปแล้ว';
    header('Location: index.php?page=auth&action=register');
    exit;
}

// Check if student_id exists
$stmt = $pdo->prepare("SELECT user_id FROM students WHERE student_id = ?");
$stmt->execute([$student_id]);
if ($stmt->fetch()) {
    $_SESSION['error'] = 'รหัสนิสิตนี้ถูกลงทะเบียนไปแล้ว';
    header('Location: index.php?page=auth&action=register');
    exit;
}

try {
    $pdo->beginTransaction();

    // Insert into users
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, password, role, full_name) VALUES (?, ?, 'student', ?)");
    $stmt->execute([$username, $hashed_password, $full_name]);
    $user_id = $pdo->lastInsertId();

    // Insert into students
    $stmt = $pdo->prepare("INSERT INTO students (user_id, student_id, major) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $student_id, $major]);

    $pdo->commit();

    $_SESSION['success'] = 'ลงทะเบียนสำเร็จ! กรุณาเข้าสู่ระบบ';
    header('Location: index.php?page=auth&action=login');
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['error'] = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
    header('Location: index.php?page=auth&action=register');
    exit;
}
