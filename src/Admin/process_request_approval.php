<?php
// src/Admin/process_request_approval.php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    die("Unauthorized");
}

$request_id = $_POST['request_id'] ?? 0;
$status = $_POST['status'] ?? '';

if (!in_array($status, ['approved', 'rejected'])) {
    die("Invalid status");
}

try {
    $stmt = $pdo->prepare("UPDATE internship_requests SET status = ? WHERE id = ?");
    $stmt->execute([$status, $request_id]);

    echo "<script>alert('บันทึกผลการอนุมัติเรียบร้อย'); window.location.href='index.php?page=admin&action=requests';</script>";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
