<?php
// src/Employer/dashboard.php

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'employer') {
    header('Location: index.php?page=auth&action=employer_login');
    exit;
}

$employer_id = $_SESSION['user']['id'];

// Fetch students linked to this employer/company
// Assuming companies are linked to employer via employer_user_id
// And students are linked to companies via internship_requests (approved)

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

// If no students found via direct link, fallback to showing all approved students for demo purposes if the user hasn't set up the link yet.
// BUT, for security/correctness, we should stick to the link. 
// However, since I just added the link in SQL, it might be empty for existing data unless I updated it.
// The SQL script I wrote updates one company to be owned by 'employer1'.
// So if I login as 'employer1', I should see students for that company.

?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Employer Dashboard</h1>
            <p class="text-gray-600 mt-1">ยินดีต้อนรับ, <?= htmlspecialchars($_SESSION['user']['full_name']) ?></p>
        </div>
        <div class="flex gap-3">
            <a href="index.php?page=employer&action=change_password" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                เปลี่ยนรหัสผ่าน
            </a>
            <a href="index.php?page=auth&action=logout" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                ออกจากระบบ
            </a>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-sm font-medium text-gray-500">นิสิตฝึกงานทั้งหมด</h2>
                    <p class="text-2xl font-semibold text-gray-900"><?= count($students) ?> คน</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-sm font-medium text-gray-500">ประเมินแล้ว</h2>
                    <p class="text-2xl font-semibold text-gray-900">
                        <?= count(array_filter($students, fn($s) => !empty($s['evaluation_id']))) ?> คน
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-sm font-medium text-gray-500">รอการประเมิน</h2>
                    <p class="text-2xl font-semibold text-gray-900">
                        <?= count(array_filter($students, fn($s) => empty($s['evaluation_id']))) ?> คน
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                รายชื่อนิสิตฝึกงาน (Interns List)
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">รหัสนิสิต</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ชื่อ-นามสกุล</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">สาขา</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">สถานะการประเมิน</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($students)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                    <p>ไม่พบรายชื่อนิสิตฝึกงานในบริษัทของคุณ</p>
                                    <p class="text-xs text-gray-400 mt-1">โปรดตรวจสอบว่าบริษัทของคุณได้รับการอนุมัติและมีนิสิตเลือกฝึกงานแล้ว</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($students as $std): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">
                                    <?= htmlspecialchars($std['student_id']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold mr-3">
                                            <?= mb_substr($std['full_name'], 0, 1) ?>
                                        </div>
                                        <?= htmlspecialchars($std['full_name']) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="px-2 py-1 rounded-full bg-gray-100 text-gray-600 text-xs font-medium">
                                        <?= htmlspecialchars($std['major_name'] ?? '-') ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <?php if ($std['evaluation_id']): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            ประเมินแล้ว (<?= $std['result_status'] == 'pass' ? 'ผ่าน' : 'ไม่ผ่าน' ?>)
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            รอการประเมิน
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <?php if ($std['evaluation_id']): ?>
                                        <button disabled class="text-gray-400 cursor-not-allowed px-3 py-1 rounded-lg border border-gray-200 bg-gray-50">
                                            ประเมินแล้ว
                                        </button>
                                    <?php else: ?>
                                        <a href="index.php?page=employer&action=evaluation_form&student_id=<?= $std['student_user_id'] ?>&company_id=<?= $std['company_id'] ?>" class="inline-flex items-center text-white bg-blue-600 hover:bg-blue-700 px-3 py-1.5 rounded-lg shadow-sm hover:shadow transition-all text-xs">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            ประเมินผล
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>