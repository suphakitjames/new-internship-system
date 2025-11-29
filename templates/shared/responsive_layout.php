<?php
// templates/shared/responsive_layout.php
// Shared responsive layout for Student, Advisor, Employer roles
// Usage: include this file and pass $page_title, $navigation_items, and $content_file

$user_role = $_SESSION['user']['role'] ?? 'guest';
$user_name = $_SESSION['user']['full_name'] ?? 'User';

// Set role-specific colors
$role_colors = [
    'student' => ['from' => 'from-purple-600', 'to' => 'to-purple-800', 'badge' => 'bg-purple-100 text-purple-700'],
    'advisor' => ['from' => 'from-green-600', 'to' => 'to-green-800', 'badge' => 'bg-green-100 text-green-700'],
    'employer' => ['from' => 'from-orange-600', 'to' => 'to-orange-800', 'badge' => 'bg-orange-100 text-orange-700'],
];

$colors = $role_colors[$user_role] ?? ['from' => 'from-blue-600', 'to' => 'to-blue-800', 'badge' => 'bg-blue-100 text-blue-700'];
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title ?? 'ระบบบริหารจัดการการฝึกประสบการณ์วิชาชีพ') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
        }

        /* Sidebar/Navigation Transition */
        .nav-sidebar {
            transition: transform 0.3s ease-in-out;
        }

        @media (max-width: 768px) {
            .nav-sidebar {
                transform: translateX(-100%);
            }

            .nav-sidebar.open {
                transform: translateX(0);
            }
        }

        @media (min-width: 769px) {
            .nav-sidebar {
                transform: translateX(0) !important;
            }
        }

        /* Card Animations */
        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }

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
                            </div>
                        <?php else: ?>
                            <!-- Navigation Link -->
                            <a href="<?= htmlspecialchars($item['url'] ?? '#') ?>"
                                class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all <?= ($item['active'] ?? false) ? 'bg-white/20 shadow-lg' : '' ?>">
                                <?php if (isset($item['icon'])): ?>
                                    <?= $item['icon'] ?>
                                <?php endif; ?>
                                <span class="font-medium"><?= htmlspecialchars($item['label'] ?? '') ?></span>
                                <?php if (isset($item['badge'])): ?>
                                    <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full font-bold"><?= htmlspecialchars($item['badge']) ?></span>
                                <?php endif; ?>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </nav>

            <!--  Logout Button -->
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-white/20 bg-black/10">
                <a href="index.php?page=auth&action=logout" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all text-white/90 hover:text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span class="font-medium">ออกจากระบบ</span>
                </a>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar with Hamburger Button -->
            <header class="bg-white shadow-sm border-b border-slate-200 px-4 md:px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <!-- Hamburger Menu Button (Mobile/Tablet only) -->
                    <button id="navToggle" class="md:hidden text-slate-600 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg p-2 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    <!-- Page Title -->
                    <div>
                        <h1 class="text-lg md:text-xl font-semibold text-slate-800"><?= htmlspecialchars($page_title ?? 'Dashboard') ?></h1>
                    </div>
                </div>

                <!-- User Badge (Desktop only) -->
                <div class="hidden md:flex items-center gap-2">
                    <span class="<?= $colors['badge'] ?> px-3 py-1 rounded-full text-sm font-medium">
                        <?= htmlspecialchars($user_name) ?>
                    </span>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-slate-50 p-4 md:p-6">
                <?php
                // Load the content
                if (isset($content_file) && file_exists($content_file)) {
                    include $content_file;
                } elseif (isset($content)) {
                    echo $content;
                } else {
                    echo "<div class='text-center py-20'><h2 class='text-2xl font-bold text-slate-400'>Content not found</h2></div>";
                }
                ?>
            </main>
        </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div id="navOverlay" class="fixed inset-0 bg-black/50 z-20 hidden md:hidden"></div>

    <script>
        // Navigation Toggle for Mobile
        const navSidebar = document.getElementById('navSidebar');
        const navToggle = document.getElementById('navToggle');
        const navOverlay = document.getElementById('navOverlay');

        navToggle?.addEventListener('click', () => {
            navSidebar.classList.toggle('open');
            navOverlay.classList.toggle('hidden');
        });

        navOverlay?.addEventListener('click', () => {
            navSidebar.classList.remove('open');
            navOverlay.classList.add('hidden');
        });
    </script>
</body>

</html>