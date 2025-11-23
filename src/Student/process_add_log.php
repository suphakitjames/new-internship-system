<?php
// src/Student/process_add_log.php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    die("Unauthorized");
}

$log_date = $_POST['log_date'] ?? '';
$work_description = $_POST['work_description'] ?? '';
$problems = $_POST['problems'] ?? '';
$user_id = $_SESSION['user']['id'];

if (empty($log_date) || empty($work_description)) {
    echo "<script>alert('กรุณากรอกข้อมูลให้ครบถ้วน'); window.history.back();</script>";
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO daily_logs (student_id, log_date, work_description, problems) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $log_date, $work_description, $problems]);

    echo "<script>alert('บันทึกข้อมูลเรียบร้อย'); window.location.href='index.php?page=student&action=daily_log';</script>";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
