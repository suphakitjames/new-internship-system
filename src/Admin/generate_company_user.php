<?php
// src/Admin/generate_company_user.php

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php?page=auth&action=login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company_id = $_POST['company_id'] ?? null;

    if (!$company_id) {
        $_SESSION['error_message'] = 'ไม่พบข้อมูลบริษัท';
        header('Location: index.php?page=admin&action=companies');
        exit;
    }

    try {
        $pdo->beginTransaction();

        // Check if company exists and doesn't have a user
        $stmt = $pdo->prepare("SELECT * FROM companies WHERE id = ?");
        $stmt->execute([$company_id]);
        $company = $stmt->fetch();

        if (!$company) {
            throw new Exception("ไม่พบข้อมูลบริษัท");
        }

        if (!empty($company['user_id'])) {
            throw new Exception("บริษัทนี้มีบัญชีผู้ใช้อยู่แล้ว");
        }

        // 1. Generate User Credentials
        $username = 'comp_' . strtolower(substr(md5(uniqid()), 0, 5));
        $raw_password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);
        $hashed_password = password_hash($raw_password, PASSWORD_DEFAULT);
        $full_name = $company['name'];

        // 2. Create User
        $stmtUser = $pdo->prepare("INSERT INTO users (username, password, role, full_name) VALUES (?, ?, 'employer', ?)");
        $stmtUser->execute([$username, $hashed_password, $full_name]);
        $user_id = $pdo->lastInsertId();

        // 3. Update Company with user_id
        $stmtUpdate = $pdo->prepare("UPDATE companies SET user_id = ? WHERE id = ?");
        $stmtUpdate->execute([$user_id, $company_id]);

        $pdo->commit();

        $_SESSION['success_message'] = "สร้างบัญชีสำเร็จ! <br><strong>ชื่อผู้ใช้:</strong> $username <br><strong>รหัสผ่าน:</strong> $raw_password <br>(กรุณาจดจำรหัสผ่านนี้)";
        header('Location: index.php?page=admin&action=companies');
        exit;
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $_SESSION['error_message'] = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
        header('Location: index.php?page=admin&action=companies');
        exit;
    }
}
