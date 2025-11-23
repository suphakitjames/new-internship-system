<?php
// src/Admin/requests.php

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php?page=auth&action=login');
    exit;
}

// Fetch pending requests
$stmt = $pdo->query("
    SELECT ir.*, s.student_id, u.full_name, c.name as company_name 
    FROM internship_requests ir
    JOIN students s ON ir.student_id = s.user_id
    JOIN users u ON s.user_id = u.id
    JOIN companies c ON ir.company_id = c.id
    WHERE ir.status = 'pending'
    ORDER BY ir.request_date ASC
");
$requests = $stmt->fetchAll();
?>
<div class="max-w-7xl mx-auto">
    <div class="mb-8 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">อนุมัติคำขอฝึกงาน</h1>
        <a href="index.php?page=admin&action=dashboard" class="text-blue-600 hover:text-blue-800">กลับหน้า Dashboard</a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">วันที่ยื่น</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">นิสิต</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">สถานที่ฝึกงาน</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($requests)): ?>
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">ไม่มีคำขอที่รอการตรวจสอบ</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($requests as $req): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= date('d/m/Y H:i', strtotime($req['request_date'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($req['full_name']) ?></div>
                                    <div class="text-sm text-gray-500"><?= htmlspecialchars($req['student_id']) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= htmlspecialchars($req['company_name']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <form action="index.php?page=admin&action=process_request_approval" method="POST" class="inline-flex gap-2">
                                        <input type="hidden" name="request_id" value="<?= $req['id'] ?>">
                                        <button type="submit" name="status" value="approved" class="text-green-600 hover:text-green-900 bg-green-50 px-3 py-1 rounded-lg border border-green-200">อนุมัติ</button>
                                        <button type="submit" name="status" value="rejected" class="text-red-600 hover:text-red-900 bg-red-50 px-3 py-1 rounded-lg border border-red-200" onclick="return confirm('ยืนยันการไม่อนุมัติ?')">ไม่อนุมัติ</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
