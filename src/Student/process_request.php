<?php
// src/Student/process_request.php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    die("Unauthorized");
}

$company_id = $_POST['company_id'] ?? 0;
$user_id = $_SESSION['user']['id'];

// Double check if student can request
$stmt = $pdo->prepare("SELECT status FROM internship_requests WHERE student_id = ? AND status IN ('pending', 'approved')");
$stmt->execute([$user_id]);
if ($stmt->fetch()) {
    echo "<script>alert('คุณมีคำขอที่อยู่ระหว่างดำเนินการแล้ว'); window.history.back();</script>";
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO internship_requests (student_id, company_id, status) VALUES (?, ?, 'pending')");
    $stmt->execute([$user_id, $company_id]);

    echo "<script>alert('ยื่นคำขอสำเร็จ! กรุณารอผลการอนุมัติ'); window.location.href='index.php?page=student&action=dashboard';</script>";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
