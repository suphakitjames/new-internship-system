<?php
// src/Admin/process_company_approval.php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    die("Unauthorized");
}

$company_id = $_POST['company_id'] ?? 0;
$status = $_POST['status'] ?? '';

if (!in_array($status, ['approved', 'rejected'])) {
    die("Invalid status");
}

try {
    $stmt = $pdo->prepare("UPDATE companies SET status = ? WHERE id = ?");
    $stmt->execute([$status, $company_id]);

    echo "<script>alert('บันทึกผลการอนุมัติเรียบร้อย'); window.location.href='index.php?page=admin&action=companies';</script>";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
