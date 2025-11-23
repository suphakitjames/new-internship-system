<?php
// src/Employer/save_evaluation.php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php?page=employer&action=dashboard');
    exit;
}

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'employer') {
    header('Location: index.php?page=auth&action=employer_login');
    exit;
}

$employer_id = $_SESSION['user']['id'];
$student_id = $_POST['student_id'];
$company_id = $_POST['company_id'];
$suggestion = $_POST['suggestion'] ?? '';
$result_status = $_POST['result_status'];

// Prepare columns for insertion
$columns = [
    'student_id',
    'employer_id',
    'company_id',
    'criteria_1',
    'criteria_2',
    'criteria_3',
    'criteria_4',
    'criteria_5',
    'criteria_6',
    'criteria_7',
    'criteria_8',
    'criteria_9',
    'criteria_10',
    'suggestion',
    'result_status'
];

$placeholders = array_fill(0, count($columns), '?');
$sql = "INSERT INTO evaluations (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";

$params = [
    $student_id,
    $employer_id,
    $company_id
];

// Add criteria scores
for ($i = 1; $i <= 10; $i++) {
    $params[] = $_POST["criteria_$i"] ?? 0;
}

$params[] = $suggestion;
$params[] = $result_status;

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    // Redirect with success message
    // We can use a session flash message if the layout supports it, or just a simple alert script
    // But better to redirect to dashboard with a success flag

    // Since we don't have a robust flash message system visible in the code I read, 
    // I'll just redirect to dashboard. The dashboard can check for a success param.

    echo "<script>alert('บันทึกการประเมินเรียบร้อยแล้ว'); window.location.href='index.php?page=employer&action=dashboard';</script>";
} catch (PDOException $e) {
    // Handle error
    echo "Error: " . $e->getMessage();
    // In production, log this and show a friendly error
}
