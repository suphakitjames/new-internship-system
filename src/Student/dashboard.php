<?php
// src/Student/dashboard.php

use Models\StudentModel;
use Core\View;

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header('Location: index.php?page=auth&action=login');
    exit;
}

$user_id = $_SESSION['user']['id'];

// Instantiate Model
$studentModel = new StudentModel($pdo);

// Fetch Data
$student = $studentModel->getProfile($user_id);
$request = $studentModel->getInternshipStatus($user_id);
$evaluation = $studentModel->getEvaluationResult($user_id);

// Render View
View::render('student/dashboard', [
    'student' => $student,
    'request' => $request,
    'evaluation' => $evaluation
]);
