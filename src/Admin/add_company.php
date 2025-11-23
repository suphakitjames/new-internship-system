<?php
// src/Admin/add_company.php

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php?page=auth&action=login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();

        // 1. Generate User Credentials
        // Username: company_ + random 4 digits (or based on name, but simple is safer for uniqueness)
        // Password: Random 8 chars

        $username = 'comp_' . strtolower(substr(md5(uniqid()), 0, 5));
        $raw_password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);
        $hashed_password = password_hash($raw_password, PASSWORD_DEFAULT);
        $full_name = $_POST['name']; // Use company name as user's full name

        // 2. Create User
        $stmtUser = $pdo->prepare("INSERT INTO users (username, password, role, full_name) VALUES (?, ?, 'employer', ?)");
        $stmtUser->execute([$username, $hashed_password, $full_name]);
        $user_id = $pdo->lastInsertId();

        // 3. Create Company linked to User
        $stmtCompany = $pdo->prepare("
            INSERT INTO companies (
                name, address, province, phone, contact_person, description, 
                status, created_by, created_at, user_id
            ) VALUES (?, ?, ?, ?, ?, ?, 'approved', ?, NOW(), ?)
        ");

        $stmtCompany->execute([
            $_POST['name'],
            $_POST['address'],
            $_POST['province'],
            $_POST['phone'] ?? null,
            $_POST['contact_person'] ?? null,
            $_POST['description'] ?? null,
            $_SESSION['user']['id'],
            $user_id
        ]);

        $pdo->commit();

        $_SESSION['success_message'] = "เพิ่มบริษัทสำเร็จ! <br><strong>ชื่อผู้ใช้:</strong> $username <br><strong>รหัสผ่าน:</strong> $raw_password <br>(กรุณาจดจำรหัสผ่านนี้)";
        header('Location: index.php?page=admin&action=companies');
        exit;
    } catch (PDOException $e) {
        $pdo->rollBack();
        $_SESSION['error_message'] = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
        header('Location: index.php?page=admin&action=companies');
        exit;
    }
}
