<?php
// src/Advisor/dashboard.php

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'advisor') {
    header('Location: index.php?page=auth&action=login');
    exit;
}

$user_id = $_SESSION['user']['id'];

// Fetch Advisor Info
$stmt = $pdo->prepare("
    SELECT u.full_name, m.name as major_name, m.id as major_id
    FROM users u
    JOIN advisors a ON u.id = a.user_id
    JOIN majors m ON a.major_id = m.id
    WHERE u.id = ?
");
$stmt->execute([$user_id]);
$advisor = $stmt->fetch();

if (!$advisor) {
    echo "<div class='p-8 text-center text-red-500'>ไม่พบข้อมูลสาขาวิชาของอาจารย์ กรุณาติดต่อผู้ดูแลระบบ</div>";
    exit;
}

// Fetch list of students who have sent messages for the dropdown
$stmt = $pdo->prepare("
    SELECT DISTINCT s.student_id, u.full_name, u.id as user_id
    FROM advisor_messages am
    JOIN students s ON am.student_id = s.user_id
    JOIN users u ON s.user_id = u.id
    WHERE am.advisor_id = ?
    ORDER BY u.full_name ASC
");
$stmt->execute([$user_id]);
$students_with_messages = $stmt->fetchAll();

// Handle Student Selection
$selected_student_id = $_GET['student_id'] ?? null;
$messages = []; // Initialize $messages as an empty array

if ($selected_student_id) {
    // Fetch all messages from this student
    $stmt = $pdo->prepare("
        SELECT am.*, u.full_name as student_name
        FROM advisor_messages am
        JOIN students s ON am.student_id = s.user_id
        JOIN users u ON s.user_id = u.id
        WHERE am.advisor_id = ? AND s.student_id = ?
        ORDER BY am.created_at DESC
    ");
    $stmt->execute([$user_id, $selected_student_id]);
    $messages = $stmt->fetchAll();

    // Mark unread messages as read
    if ($messages) {
        $unread_ids = [];
        foreach ($messages as $msg) {
            if (!$msg['is_read']) {
                $unread_ids[] = $msg['id'];
            }
        }

        if (!empty($unread_ids)) {
            $placeholders = str_repeat('?,', count($unread_ids) - 1) . '?';
            $stmtUpdate = $pdo->prepare("UPDATE advisor_messages SET is_read = 1 WHERE id IN ($placeholders)");
            $stmtUpdate->execute($unread_ids);
        }
    }
}

// Use the advisor layout wrapper
include 'templates/advisor/layout.php';
