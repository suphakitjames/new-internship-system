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
?>

<div class="max-w-5xl mx-auto space-y-8">
    <!-- Profile Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-6">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white shadow-lg ring-4 ring-blue-50 flex-shrink-0">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div class="text-center md:text-left">
                    <h1 class="text-2xl font-bold text-slate-900"><?= htmlspecialchars($advisor['full_name']) ?></h1>
                    <div class="flex items-center gap-2 text-slate-500 mt-1 justify-center md:justify-start">
                        <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-sm font-medium">
                            สาขา<?= htmlspecialchars($advisor['major_name']) ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="index.php?page=advisor&action=change_password" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors flex items-center gap-2 font-medium">
                    เปลี่ยนรหัสผ่าน
                </a>
                <a href="index.php?page=auth&action=logout" class="px-4 py-2 bg-red-50 text-red-600 border border-red-100 rounded-lg hover:bg-red-100 transition-colors flex items-center gap-2 font-medium">
                    ออกจากระบบ
                </a>
            </div>
        </div>
    </div>

    <!-- Message Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
        <h2 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-2">
            <svg class="w-6 h-6 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            เล่าสู่กันฟัง
        </h2>

        <form action="index.php" method="GET" class="mb-8">
            <input type="hidden" name="page" value="advisor">
            <input type="hidden" name="action" value="dashboard">
            <div class="flex items-center gap-4 max-w-2xl mx-auto">
                <label for="student_id" class="font-medium text-slate-700 whitespace-nowrap">เลือกรายชื่อนิสิต</label>
                <select name="student_id" id="student_id" onchange="this.form.submit()" class="flex-1 form-select rounded-lg border-slate-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition">
                    <option value="">-- เลือกนิสิต --</option>
                    <?php foreach ($students_with_messages as $student): ?>
                        <option value="<?= htmlspecialchars($student['student_id']) ?>" <?= $selected_student_id == $student['student_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($student['full_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>

        <?php if ($selected_student_id && !empty($messages)): ?>
            <div class="space-y-6">
                <div class="bg-blue-50 text-blue-800 px-4 py-3 rounded-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium">ประวัติการเล่าสู่กันฟังจาก: <?= htmlspecialchars($messages[0]['student_name']) ?></span>
                </div>

                <div class="space-y-4">
                    <?php foreach ($messages as $msg): ?>
                        <div class="border border-slate-200 rounded-xl p-6 hover:shadow-md transition-shadow bg-white">
                            <div class="flex items-center justify-between mb-4 pb-4 border-b border-slate-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center text-purple-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm text-slate-500">ส่งเมื่อ</div>
                                        <div class="font-medium text-slate-900">
                                            <?= date('d/m/Y H:i น.', strtotime($msg['created_at'])) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-slate-700 leading-relaxed whitespace-pre-wrap pl-13 border-l-4 border-purple-100 pl-4 ml-2">
                                <?= htmlspecialchars($msg['message']) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php elseif ($selected_student_id): ?>
            <div class="text-center py-12 bg-slate-50 rounded-xl border border-dashed border-slate-300">
                <svg class="w-12 h-12 text-slate-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <p class="text-slate-500">ไม่พบข้อความจากนิสิตคนนี้</p>
            </div>
        <?php else: ?>
            <div class="text-center py-16 bg-slate-50 rounded-xl border border-dashed border-slate-300">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-sm mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-slate-900 mb-1">กรุณาเลือกรายชื่อนิสิต</h3>
                <p class="text-slate-500">เลือกนิสิตจากเมนูด้านบนเพื่อดูประวัติการเล่าสู่กันฟัง</p>
            </div>
        <?php endif; ?>
    </div>
</div>