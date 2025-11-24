<?php
// src/Admin/dashboard.php

use Models\AdminModel;
use Core\View;

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php?page=auth&action=login');
    exit;
}

// Instantiate Model
$adminModel = new AdminModel($pdo);

// Fetch Data
$stats = $adminModel->getDashboardStats();
$provinceStats = $adminModel->getProvinceStats();

// Render View
View::render('admin/dashboard', array_merge($stats, [
    'province_labels' => $provinceStats['labels'],
    'province_counts' => $provinceStats['counts']
]));
