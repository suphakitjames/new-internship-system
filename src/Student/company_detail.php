<?php
// src/Student/company_detail.php

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM companies WHERE id = ?");
$stmt->execute([$id]);
$company = $stmt->fetch();

if (!$company) {
    echo "Company not found";
    exit;
}

// Check if student already has a pending or approved request
$user_id = $_SESSION['user']['id'] ?? 0;
$can_request = false;
$request_status = null;

if ($user_id) {
    $stmt = $pdo->prepare("SELECT status FROM internship_requests WHERE student_id = ? AND status IN ('pending', 'approved')");
    $stmt->execute([$user_id]);
    $existing_request = $stmt->fetch();
    
    if (!$existing_request) {
        $can_request = true;
    } else {
        $request_status = $existing_request['status'];
    }
}
?>
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="index.php?page=student&action=internships" class="text-blue-600 hover:text-blue-800 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            ย้อนกลับ
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
        <div class="h-48 bg-gradient-to-r from-blue-600 to-indigo-700 flex items-center justify-center">
            <h1 class="text-4xl font-bold text-white"><?= htmlspecialchars($company['name']) ?></h1>
        </div>
        
        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="md:col-span-2 space-y-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">รายละเอียด</h3>
                        <p class="text-gray-600 leading-relaxed">
                            <?= nl2br(htmlspecialchars($company['description'] ?? 'ไม่มีรายละเอียด')) ?>
                        </p>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">ที่อยู่</h3>
                        <p class="text-gray-600">
                            <?= htmlspecialchars($company['address']) ?><br>
                            จ. <?= htmlspecialchars($company['province']) ?>
                        </p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">ข้อมูลติดต่อ</h3>
                        <ul class="space-y-3 text-sm text-gray-600">
                            <li class="flex items-start gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span><?= htmlspecialchars($company['contact_person'] ?? '-') ?></span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <span><?= htmlspecialchars($company['phone'] ?? '-') ?></span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span><?= htmlspecialchars($company['email'] ?? '-') ?></span>
                            </li>
                        </ul>
                    </div>

                    <?php if ($can_request): ?>
                        <form action="index.php?page=student&action=process_request" method="POST">
                            <input type="hidden" name="company_id" value="<?= $company['id'] ?>">
                            <button type="submit" class="w-full bg-blue-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-500/30 transform hover:-translate-y-0.5" onclick="return confirm('ยืนยันการขอฝึกงานที่นี่? คุณสามารถเลือกได้เพียง 1 ที่เท่านั้น')">
                                ยื่นขอฝึกงาน
                            </button>
                        </form>
                    <?php elseif ($request_status): ?>
                        <div class="bg-yellow-50 text-yellow-800 p-4 rounded-xl border border-yellow-200 text-center">
                            <span class="font-medium">คุณมีรายการที่อยู่ระหว่างดำเนินการ (<?= $request_status ?>)</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
