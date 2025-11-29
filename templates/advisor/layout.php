<?php
// templates/advisor/layout.php
// Advisor layout wrapper using the shared responsive layout

$user_id = $_SESSION['user']['id'] ?? null;
$current_page = $_GET['page'] ?? '';
$current_action = $_GET['action'] ?? 'dashboard';

// Define Advisor Navigation Items
$navigation_items = [
    [
        'label' => 'หน้าหลัก',
        'url' => 'index.php?page=advisor&action=dashboard',
        'active' => ($current_action === 'dashboard'),
        'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                  </svg>'
    ],
    [
        'section' => 'การจัดการ'
    ],
    [
        'label' => 'เล่าสู่กันฟัง',
        'url' => 'index.php?page=advisor&action=dashboard',
        'active' => ($current_action === 'dashboard'),
        'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                  </svg>'
    ],
    [
        'section' => 'บัญชี'
    ],
    [
        'label' => 'เปลี่ยนรหัสผ่าน',
        'url' => 'index.php?page=advisor&action=change_password',
        'active' => ($current_action === 'change_password'),
        'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                  </svg>'
    ],
];

// Set page title
$page_titles = [
    'dashboard' => 'อาจารย์ที่ปรึกษา - หน้าหลัก',
    'change_password' => 'เปลี่ยนรหัสผ่าน',
];

$page_title = $page_titles[$current_action] ?? 'Advisor Dashboard';

// Set content file - for advisor, content is embedded in the dashboard file
// We'll need to modify how advisor pages work
$content_file = isset($layout_content_file) ? $layout_content_file : null;

// If no content file specified, render inline content
if (!$content_file) {
    // Store the action for use in inline rendering
    $advisor_action = $current_action;

    // Use inline content rendering
    ob_start();
    include "src/Advisor/$advisor_action.php";
    $content = ob_get_clean();

    // Include the shared responsive layout
    include 'templates/shared/responsive_layout.php';
} else {
    // Include the shared responsive layout
    include 'templates/shared/responsive_layout.php';
}
