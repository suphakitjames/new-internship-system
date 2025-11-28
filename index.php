<?php
// index.php
session_start();
require_once 'config/database.php';

// Debug: Enable errors (can be removed in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Autoloader
spl_autoload_register(function ($class) {
    $base_dir = __DIR__ . '/src/';
    $file = $base_dir . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

// CSRF Protection: Generate Token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Simple Router
$page = $_GET['page'] ?? 'home';
$action = $_GET['action'] ?? 'index';

// Check for AJAX/JSON requests that shouldn't have HTML layout
if ($page === 'admin' && ($action === 'save_status_reply' || $action === 'process_import')) {
    $file = "src/Admin/$action.php";
    if (file_exists($file)) {
        include $file;
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'File not found']);
    }
    exit;
}

// Check for Process Actions (Form Submissions that redirect) or Logout
if (str_starts_with($action, 'process_') || $action === 'logout' || $action === 'add_company' || $action === 'generate_company_user') {
    $file = "src/" . ucfirst($page) . "/$action.php";
    if (file_exists($file)) {
        include $file;
        exit;
    }
}

// Basic Auth Check (Placeholder)
$user = $_SESSION['user'] ?? null;

?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบบริหารจัดการการฝึกประสบการณ์วิชาชีพ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800">

    <!-- Navbar -->
    <?php
    // Hide navbar for admin pages, employer pages, and advisor pages
    $hideNavbar = ($page === 'admin' && $user && $user['role'] === 'admin') || ($page === 'employer') || ($page === 'advisor') || ($page === 'auth' && $action === 'admin_login');

    if (!$hideNavbar):
    ?>
        <nav class="bg-white shadow-md">
            <div class="container mx-auto px-4 py-3 flex justify-between items-center">
                <a href="index.php" class="text-xl font-bold text-blue-600 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                    </svg>
                    Internship System
                </a>
                <div class="hidden md:flex space-x-6">
                    <a href="index.php" class="hover:text-blue-600">หน้าหลัก</a>
                    <a href="index.php?page=internships" class="hover:text-blue-600">สถานที่ฝึกงาน</a>
                    <a href="index.php?page=about" class="hover:text-blue-600">เกี่ยวกับเรา</a>
                </div>
                <div>
                    <?php if ($user):
                        $dashboardPage = 'student';
                        if ($user['role'] === 'admin') $dashboardPage = 'admin';
                        if ($user['role'] === 'employer') $dashboardPage = 'employer';
                        if ($user['role'] === 'advisor') $dashboardPage = 'advisor';
                    ?>
                        <a href="index.php?page=<?= $dashboardPage ?>&action=dashboard" class="mr-4 font-medium hover:text-blue-600 transition-colors">สวัสดี, <?= htmlspecialchars($user['full_name']) ?></a>
                        <a href="index.php?page=auth&action=logout" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">ออกจากระบบ</a>
                    <?php else: ?>
                        <a href="index.php?page=auth&action=login" class="text-blue-600 hover:text-blue-800 mr-4">เข้าสู่ระบบ</a>
                        <a href="index.php?page=auth&action=register" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition shadow-lg shadow-blue-500/30">ลงทะเบียน</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    <?php endif; ?>

    <!-- Main Content -->
    <?php
    // Check if this is an admin page
    if ($page === 'admin' && $user && $user['role'] === 'admin') {
        // Use admin layout with sidebar
        include 'templates/admin_layout.php';
    } else {
        // Use regular layout
    ?>
        <main class="container mx-auto px-4 py-8 min-h-screen">
            <?php
            // Simple Routing Logic
            $file = "src/" . ucfirst($page) . "/$action.php";
            if ($page === 'home') {
                include 'templates/home.php';
            } elseif (file_exists($file)) {
                include $file;
            } else {
                echo "<div class='text-center py-20'><h2 class='text-2xl font-bold text-gray-400'>404 - Page Not Found</h2></div>";
            }
            ?>
        </main>
    <?php
    }
    ?>

    <!-- Footer -->
    <?php if (!$hideNavbar): ?>
        <footer class="bg-gray-800 text-white py-8 mt-12">
            <div class="container mx-auto px-4 text-center">
                <p>&copy; <?= date('Y') ?> Internship Management System. All rights reserved.</p>
            </div>
        </footer>
    <?php endif; ?>

</body>

</html>