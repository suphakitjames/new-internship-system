<?php
// src/Student/process_time_sheet.php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    die("Unauthorized");
}

$date = $_POST['date'] ?? '';
$time_in = $_POST['time_in'] ?? '';
$time_out = $_POST['time_out'] ?? null;
$notes = $_POST['notes'] ?? '';
$user_id = $_SESSION['user']['id'];

if (empty($date) || empty($time_in)) {
    echo "<script>alert('กรุณาระบุวันที่และเวลาเข้า'); window.history.back();</script>";
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO time_sheets (student_id, date, time_in, time_out, notes) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $date, $time_in, $time_out ?: null, $notes]);

    echo "<script>alert('บันทึกเวลาเรียบร้อย'); window.location.href='index.php?page=student&action=time_sheet';</script>";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
