<?php
// src/Auth/process_login.php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php?page=auth&action=login');
    exit;
}

// CSRF Check
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die('Invalid CSRF Token');
}

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$role_type = $_POST['role_type'] ?? 'student'; // student or admin

if (empty($username) || empty($password)) {
    $_SESSION['error'] = 'กรุณากรอกชื่อผู้ใช้งานและรหัสผ่าน';
    $redirect = $role_type === 'admin' ? 'admin_login' : 'login';
    header("Location: index.php?page=auth&action=$redirect");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    // Check if role matches the login page
    if ($role_type === 'admin' && $user['role'] !== 'admin') {
        $_SESSION['error'] = 'คุณไม่มีสิทธิ์เข้าถึงระบบ Admin';
        header('Location: index.php?page=auth&action=admin_login');
        exit;
    }

    if ($role_type === 'student' && $user['role'] === 'admin') {
        $_SESSION['error'] = 'กรุณาใช้หน้า Admin Login';
        header('Location: index.php?page=auth&action=login');
        exit;
    }

    if ($role_type === 'advisor' && $user['role'] !== 'advisor') {
        $_SESSION['error'] = 'คุณไม่มีสิทธิ์เข้าถึงระบบอาจารย์ที่ปรึกษา';
        header('Location: index.php?page=auth&action=advisor_login');
        exit;
    }

    // Login Success
    $_SESSION['user'] = [
        'id' => $user['id'],
        'username' => $user['username'],
        'role' => $user['role'],
        'full_name' => $user['full_name']
    ];

    // Redirect based on role
    switch ($user['role']) {
        case 'admin':
            header('Location: index.php?page=admin&action=dashboard');
            break;
        case 'student':
            header('Location: index.php?page=student&action=dashboard');
            break;
        case 'employer':
            header('Location: index.php?page=employer&action=dashboard');
            break;
        case 'advisor':
            header('Location: index.php?page=advisor&action=dashboard');
            break;
        default:
            header('Location: index.php');
    }
    exit;
} else {
    $_SESSION['error'] = 'ชื่อผู้ใช้งานหรือรหัสผ่านไม่ถูกต้อง';

    $redirect = 'login';
    if ($role_type === 'admin') {
        $redirect = 'admin_login';
    } elseif ($role_type === 'employer') {
        $redirect = 'employer_login';
    } elseif ($role_type === 'advisor') {
        $redirect = 'advisor_login';
    }

    header("Location: index.php?page=auth&action=$redirect");
    exit;
}
