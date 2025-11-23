<?php
// src/Student/advisor_message.php

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header('Location: index.php?page=auth&action=login');
    exit;
}

$user_id = $_SESSION['user']['id'];

// Get student info (major)
$stmt = $pdo->prepare("SELECT major FROM students WHERE user_id = ?");
$stmt->execute([$user_id]);
$student = $stmt->fetch();
$student_major = $student['major'] ?? '';

// Get advisors in the same major
$advisors = [];
if ($student_major) {
    $stmt = $pdo->prepare("
        SELECT u.id, u.full_name 
        FROM users u 
        JOIN advisors a ON u.id = a.user_id 
        WHERE a.major_id = ? AND u.role = 'advisor'
        ORDER BY u.full_name ASC
    ");
    $stmt->execute([$student_major]);
    $advisors = $stmt->fetchAll();
}

// Get previous messages
$stmt = $pdo->prepare("
    SELECT am.*, u.full_name as advisor_name
    FROM advisor_messages am
    LEFT JOIN users u ON am.advisor_id = u.id
    WHERE am.student_id = ?
    ORDER BY am.created_at DESC
");
$stmt->execute([$user_id]);
$messages = $stmt->fetchAll();
?>

<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center text-white shadow-lg">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-900">เล่าสู่กันฟัง</h1>
                <p class="text-sm text-slate-500">ส่งข้อความปรึกษาปัญหาหรือแจ้งเรื่องต่างๆ กับอาจารย์ที่ปรึกษา</p>
            </div>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            <?= htmlspecialchars($_SESSION['success']) ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            <?= htmlspecialchars($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Send Message Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            ส่งข้อความใหม่
        </h2>

        <form action="index.php?page=student&action=process_advisor_message" method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">ถึง</label>
                <div class="relative">
                    <select name="advisor_id" required class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-lg appearance-none focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-slate-900">
                        <option value="" disabled selected>เลือกอาจารย์ที่ปรึกษา</option>
                        <?php foreach ($advisors as $advisor): ?>
                            <option value="<?= $advisor['id'] ?>"><?= htmlspecialchars($advisor['full_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
                <?php if (empty($advisors)): ?>
                    <p class="text-xs text-red-500 mt-1">ไม่พบรายชื่ออาจารย์ในสาขาของคุณ (<?= htmlspecialchars($student_major) ?>)</p>
                <?php endif; ?>
            </div>

            <div>
                <label for="message" class="block text-sm font-medium text-slate-700 mb-2">ข้อความ</label>
                <textarea
                    id="message"
                    name="message"
                    rows="6"
                    required
                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 resize-none"
                    placeholder="พิมพ์ข้อความที่ต้องการส่งถึงอาจารย์ที่ปรึกษา..."></textarea>
                <p class="text-xs text-slate-500 mt-1">สามารถแจ้งปัญหา ข้อสงสัย หรือขอคำปรึกษาเกี่ยวกับการฝึกงานได้</p>
            </div>

            <div class="flex justify-end">
                <button
                    type="submit"
                    class="px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-xl font-medium hover:from-purple-700 hover:to-purple-800 transition-all shadow-lg shadow-purple-500/30 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                    ส่งข้อความ
                </button>
            </div>
        </form>
    </div>

    <!-- Message History -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            ประวัติข้อความ
        </h2>

        <?php if (empty($messages)): ?>
            <div class="text-center py-12">
                <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <p class="text-slate-500">ยังไม่มีข้อความ</p>
                <p class="text-sm text-slate-400 mt-1">เริ่มต้นส่งข้อความถึงอาจารย์ที่ปรึกษาของคุณ</p>
            </div>
        <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($messages as $msg): ?>
                    <div class="border border-slate-200 rounded-xl p-4 hover:bg-slate-50 transition-colors">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-slate-900">
                                        ถึง: <?= htmlspecialchars($msg['advisor_name'] ?? 'อาจารย์ที่ปรึกษา') ?>
                                    </div>
                                    <div class="text-xs text-slate-500">
                                        <?= date('d/m/Y H:i น.', strtotime($msg['created_at'])) ?>
                                    </div>
                                </div>
                            </div>
                            <?php if ($msg['is_read']): ?>
                                <span class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded-full">อ่านแล้ว</span>
                            <?php else: ?>
                                <span class="text-xs px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full">รอการอ่าน</span>
                            <?php endif; ?>
                        </div>
                        <div class="text-slate-700 text-sm leading-relaxed pl-10">
                            <?= nl2br(htmlspecialchars($msg['message'])) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>