<?php
// templates/student/layout.php
// Student layout wrapper using the shared responsive layout

$user_id = $_SESSION['user']['id'] ?? null;
$current_page = $_GET['page'] ?? '';
$current_action = $_GET['action'] ?? 'dashboard';

// Define Student Navigation Items
$navigation_items = [
    [
        'label' => 'หน้าหลัก',
        'url' => 'index.php?page=student&action=dashboard',
        'active' => ($current_action === 'dashboard'),
        'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                  </svg>'
    ],
    [
        'section' => 'ฝึกงาน'
    ],
    [
        'label' => 'ค้นหาสถานที่ฝึกงาน',
        'url' => 'index.php?page=internships',
        'active' => ($current_page === 'internships'),
        'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                  </svg>'
    ],
    [
        'label' => 'คู่มือการฝึกงาน',
        'url' => 'index.php?page=student&action=internship_steps',
        'active' => ($current_action === 'internship_steps'),
        'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                  </svg>'
    ],
    [
        'section' => 'บันทึกการปฏิบัติงาน'
    ],
    [
        'label' => 'บันทึกการปฏิบัติงาน',
        'url' => 'index.php?page=student&action=daily_log',
        'active' => ($current_action === 'daily_log'),
        'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                  </svg>'
    ],
    [
        'label' => 'บันทึกเวลาเข้า-ออก',
        'url' => 'index.php?page=student&action=time_sheet',
        'active' => ($current_action === 'time_sheet'),
        'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>'
    ],
    [
        'section' => 'บัญชี'
    ],
    [
        'label' => 'ข้อมูลส่วนตัว',
        'url' => 'index.php?page=student&action=profile',
        'active' => ($current_action === 'profile'),
        'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                  </svg>'
    ],
    [
        'label' => 'เปลี่ยนรหัสผ่าน',
        'url' => 'index.php?page=student&action=change_password',
        'active' => ($current_action === 'change_password'),
        'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                  </svg>'
    ],
    [
        'label' => 'เล่าสู่กันฟัง',
        'url' => 'index.php?page=student&action=advisor_message',
        'active' => ($current_action === 'advisor_message'),
        'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                  </svg>'
    ],
];

// Set page title
$page_titles = [
    'dashboard' => 'หน้าหลัก',
    'internship_steps' => 'คู่มือการฝึกงาน',
    'daily_log' => 'บันทึกการปฏิบัติงาน',
    'time_sheet' => 'บันทึกเวลาเข้า-ออก',
    'profile' => 'ข้อมูลส่วนตัว',
    'change_password' => 'เปลี่ยนรหัสผ่าน',
    'advisor_message' => 'เล่าสู่กันฟัง',
    'add_company' => 'เพิ่มสถานที่ฝึกงาน',
    'company_detail' => 'รายละเอียดสถานที่ฝึกงาน',
];

$page_title = $page_titles[$current_action] ?? 'Student Dashboard';

// Set content file
$content_file = isset($layout_content_file) ? $layout_content_file : "templates/student/$current_action.php";

// Include the shared responsive layout
include 'templates/shared/responsive_layout.php';
