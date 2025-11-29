<?php
// src/Employer/dashboard.php

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'employer') {
    header('Location: index.php?page=auth&action=employer_login');
    exit;
}

$employer_id = $_SESSION['user']['id'];

// Fetch students linked to this employer/company
$stmt = $pdo->prepare("
    SELECT 
        s.student_id, 
        u.full_name, 
        m.name as major_name,
        c.name as company_name,
        c.id as company_id,
        s.user_id as student_user_id,
        e.id as evaluation_id,
        e.result_status
    FROM companies c
    JOIN internship_requests ir ON c.id = ir.company_id
    JOIN students s ON ir.student_id = s.user_id
    JOIN users u ON s.user_id = u.id
    LEFT JOIN majors m ON s.major = m.id
    LEFT JOIN evaluations e ON (s.user_id = e.student_id AND e.employer_id = ?)
    WHERE c.employer_user_id = ? 
    AND ir.status = 'approved'
");

$stmt->execute([$employer_id, $employer_id]);
$students = $stmt->fetchAll();

// Use the employer layout wrapper
include 'templates/employer/layout.php';
