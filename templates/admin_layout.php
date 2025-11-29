<?php
// templates/admin_layout.php
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - ระบบบริหารจัดการการฝึกประสบการณ์วิชาชีพ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
        }

        /* Sidebar Transition */
        .sidebar {
            transition: transform 0.3s ease-in-out;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }
        }

        @media (min-width: 769px) {
            .sidebar {
                transform: translateX(0) !important;
            }
        }

        /* Card Hover Effects */
        .stat-card {
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
        }

        /* Smooth Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800">

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar w-64 bg-gradient-to-b from-blue-600 to-blue-800 text-white flex-shrink-0 fixed md:static h-full z-30 shadow-2xl">
            <div class="p-6 border-b border-blue-500/30">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-bold text-lg">Admin Panel</h2>
                        <p class="text-xs text-blue-200">ผู้ดูแลระบบ</p>
                    </div>
                </div>
            </div>

            <nav class="p-4 space-y-2 overflow-y-auto h-[calc(100vh-180px)]">
                <!-- Dashboard -->
                <a href="index.php?page=admin&action=dashboard" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all <?= ($action === 'dashboard') ? 'bg-white/20 shadow-lg' : '' ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="font-medium">หน้าหลัก</span>
                </a>

                <!-- Approval Section -->
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-blue-200 uppercase tracking-wider">การอนุมัติ</p>
                </div>

                <a href="index.php?page=admin&action=internship_requests" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all <?= ($action === 'internship_requests') ? 'bg-white/20 shadow-lg' : '' ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="font-medium">อนุมัติคำขอฝึกงาน</span>
                    <?php
                    $stmt = $pdo->query("SELECT COUNT(*) FROM internship_requests WHERE status = 'pending'");
                    $pending_requests = $stmt->fetchColumn();
                    if ($pending_requests > 0):
                    ?>
                        <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full font-bold"><?= $pending_requests ?></span>
                    <?php endif; ?>
                </a>

                <a href="index.php?page=admin&action=companies" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all <?= ($action === 'companies') ? 'bg-white/20 shadow-lg' : '' ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <span class="font-medium">อนุมัติสถานที่ฝึกงาน</span>
                    <?php
                    $stmt = $pdo->query("SELECT COUNT(*) FROM companies WHERE status = 'pending'");
                    $pending_companies = $stmt->fetchColumn();
                    if ($pending_companies > 0):
                    ?>
                        <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full font-bold"><?= $pending_companies ?></span>
                    <?php endif; ?>
                </a>

                <!-- Master Data Section -->
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-blue-200 uppercase tracking-wider">จัดการข้อมูล</p>
                </div>

                <a href="index.php?page=admin&action=students" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all <?= ($action === 'students') ? 'bg-white/20 shadow-lg' : '' ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span class="font-medium">จัดการข้อมูลนิสิต</span>
                </a>

                <a href="index.php?page=admin&action=student_import" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all <?= ($action === 'student_import') ? 'bg-white/20 shadow-lg' : '' ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    <span class="font-medium">นำเข้าข้อมูลนิสิต (Import)</span>
                </a>

                <a href="index.php?page=admin&action=internship_rounds" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all <?= ($action === 'internship_rounds') ? 'bg-white/20 shadow-lg' : '' ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="font-medium">จัดการรอบฝึกงาน</span>
                </a>

                <a href="index.php?page=admin&action=all_companies" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all <?= ($action === 'all_companies') ? 'bg-white/20 shadow-lg' : '' ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <span class="font-medium">จัดการสถานที่ฝึกงาน</span>
                </a>

                <!-- Documents Section -->
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-blue-200 uppercase tracking-wider">เอกสาร</p>
                </div>

                <a href="index.php?page=admin&action=print_letters" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all <?= ($action === 'print_letters') ? 'bg-white/20 shadow-lg' : '' ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    <span class="font-medium">พิมพ์หนังสือส่งตัว</span>
                </a>

                <a href="index.php?page=admin&action=reports" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all <?= ($action === 'reports') ? 'bg-white/20 shadow-lg' : '' ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="font-medium">รายงานสรุป</span>
                </a>
            </nav>

            <!-- User Info at Bottom -->
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-blue-500/30 bg-blue-900/30">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate"><?= htmlspecialchars($_SESSION['user']['full_name'] ?? 'Admin') ?></p>
                        <p class="text-xs text-blue-200">ผู้ดูแลระบบ</p>
                    </div>
                    <a href="index.php?page=auth&action=logout" class="text-white/80 hover:text-white transition-colors" title="ออกจากระบบ">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar with Hamburger Button -->
            <header class="bg-white shadow-sm border-b border-slate-200 px-4 md:px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <!-- Hamburger Menu Button (Mobile/Tablet only) -->
                    <button id="sidebarToggle" class="md:hidden text-slate-600 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg p-2 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    <!-- Page Title -->
                    <div>
                        <h1 class="text-lg md:text-xl font-semibold text-slate-800">ระบบบริหารจัดการการฝึกประสบการณ์วิชาชีพ</h1>
                    </div>
                </div>

                <!-- Home Link (Desktop only) -->
                <a href="index.php" class="hidden md:inline-flex items-center gap-2 text-sm text-slate-600 hover:text-blue-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span>กลับหน้าหลัก</span>
                </a>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-slate-50 p-4 md:p-6">
                <?php
                // Load the specific admin page content
                $admin_file = "src/Admin/$action.php";
                if (file_exists($admin_file)) {
                    include $admin_file;
                } else {
                    echo "<div class='text-center py-20'><h2 class='text-2xl font-bold text-slate-400'>404 - Page Not Found</h2></div>";
                }
                ?>
            </main>
        </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-20 hidden md:hidden"></div>

    <script>
        // Sidebar Toggle for Mobile
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        sidebarToggle?.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            sidebarOverlay.classList.toggle('hidden');
        });

        sidebarOverlay?.addEventListener('click', () => {
            sidebar.classList.remove('open');
            sidebarOverlay.classList.add('hidden');
        });
    </script>
</body>

</html>