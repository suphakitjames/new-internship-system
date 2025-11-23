<?php
// src/Employer/evaluation_form.php

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'employer') {
    header('Location: index.php?page=auth&action=employer_login');
    exit;
}

$employer_id = $_SESSION['user']['id'];
$student_user_id = $_GET['student_id'] ?? 0;
$company_id = $_GET['company_id'] ?? 0;

// Fetch Student and Company Details
$stmt = $pdo->prepare("
    SELECT 
        s.student_id, 
        u.full_name, 
        m.name as major_name,
        c.name as company_name,
        c.id as company_id
    FROM students s
    JOIN users u ON s.user_id = u.id
    LEFT JOIN majors m ON s.major = m.id
    JOIN internship_requests ir ON s.user_id = ir.student_id
    JOIN companies c ON ir.company_id = c.id
    WHERE s.user_id = ? AND c.id = ? AND ir.status = 'approved'
");
$stmt->execute([$student_user_id, $company_id]);
$data = $stmt->fetch();

if (!$data) {
    echo "<div class='p-8 text-center text-red-600'>ไม่พบข้อมูลนิสิตหรือคุณไม่มีสิทธิ์ประเมินนิสิตคนนี้</div>";
    exit;
}

// Check if already evaluated
$check_stmt = $pdo->prepare("SELECT id FROM evaluations WHERE student_id = ? AND employer_id = ?");
$check_stmt->execute([$student_user_id, $employer_id]);
if ($check_stmt->fetch()) {
    echo "<div class='p-8 text-center text-green-600'>นิสิตคนนี้ได้รับการประเมินเรียบร้อยแล้ว</div>";
    echo "<div class='text-center'><a href='index.php?page=employer&action=dashboard' class='text-blue-600 hover:underline'>กลับสู่หน้าหลัก</a></div>";
    exit;
}

$criteria_list = [
    1 => 'มีความเอาใจใส่และรับผิดชอบงานอย่างมีคุณภาพ',
    2 => 'ตั้งใจแสวงหาความรู้และเรียนรู้งาน',
    3 => 'การเอาใจใส่ระมัดระวังต่ออุปกรณ์และเครื่องมือเครื่องใช้',
    4 => 'มีความกระตือรือร้น ตรงต่อเวลา และซื่อสัตย์',
    5 => 'บุคลิกภาพและการแต่งกายที่เรียบร้อย',
    6 => 'มนุษยสัมพันธ์ในการทำงาน และมีความรอบรู้ทันต่อเหตุการณ์',
    7 => 'มีความสามารถในการปรับตัวและแก้ปัญหา',
    8 => 'การปฏิบัติตามระเบียบของหน่วยงาน',
    9 => 'การนำวิชาความรู้มาประยุกต์ใช้ในการทำงาน',
    10 => 'มีทักษะในการใช้ภาษาเพื่อการสื่อสารได้อย่างมีประสิทธิภาพ'
];

?>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header / Student Info -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="bg-blue-600 px-6 py-4">
            <h1 class="text-xl font-bold text-white flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                แบบประเมินผลการฝึกประสบการณ์วิชาชีพ
            </h1>
            <p class="text-blue-100 text-sm mt-1">คณะการบัญชีและการจัดการ มหาวิทยาลัยมหาสารคาม</p>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500">ชื่อ-สกุล (นิสิต)</label>
                    <div class="mt-1 text-lg font-semibold text-gray-900"><?= htmlspecialchars($data['full_name']) ?></div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">รหัสนิสิต</label>
                    <div class="mt-1 text-lg font-semibold text-gray-900"><?= htmlspecialchars($data['student_id']) ?></div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">สาขา</label>
                    <div class="mt-1 text-lg font-semibold text-gray-900"><?= htmlspecialchars($data['major_name']) ?></div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">สถานที่ฝึกประสบการณ์วิชาชีพ</label>
                    <div class="mt-1 text-lg font-semibold text-gray-900"><?= htmlspecialchars($data['company_name']) ?></div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">ชื่อผู้ประเมิน</label>
                    <div class="mt-1 text-lg font-semibold text-gray-900"><?= htmlspecialchars($_SESSION['user']['full_name']) ?></div>
                </div>
            </div>
        </div>
    </div>

    <form action="index.php?page=employer&action=save_evaluation" method="POST" class="space-y-8">
        <input type="hidden" name="student_id" value="<?= $student_user_id ?>">
        <input type="hidden" name="company_id" value="<?= $company_id ?>">

        <!-- Evaluation Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-lg font-bold text-gray-800">รายการประเมิน</h2>
                <p class="text-sm text-gray-500">โปรดระบุระดับคะแนนที่เหมาะสมที่สุด (5 = ดีมาก, 1 = แก้ไข)</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/2">หัวข้อการประเมิน</th>
                            <th scope="col" class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">ดีมาก (5)</th>
                            <th scope="col" class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">ดี (4)</th>
                            <th scope="col" class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">ปานกลาง (3)</th>
                            <th scope="col" class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">พอใช้ (2)</th>
                            <th scope="col" class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">แก้ไข (1)</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($criteria_list as $key => $criteria): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <span class="font-medium mr-2"><?= $key ?>.</span> <?= $criteria ?>
                                </td>
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <td class="px-2 py-4 text-center">
                                        <label class="inline-flex items-center justify-center w-full h-full cursor-pointer">
                                            <input type="radio" name="criteria_<?= $key ?>" value="<?= $i ?>" required class="form-radio h-5 w-5 text-blue-600 border-gray-300 focus:ring-blue-500 transition duration-150 ease-in-out">
                                        </label>
                                    </td>
                                <?php endfor; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Result & Suggestion -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">ผลการประเมิน</h3>
                <div class="space-y-4">
                    <div class="relative flex items-start">
                        <div class="flex items-center h-5">
                            <input id="status_pass" name="result_status" type="radio" value="pass" required class="focus:ring-blue-500 h-5 w-5 text-blue-600 border-gray-300">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="status_pass" class="font-medium text-gray-700 text-base">ผ่าน (Pass)</label>
                            <p class="text-gray-500">นิสิตผ่านเกณฑ์การฝึกงาน</p>
                        </div>
                    </div>
                    <div class="relative flex items-start">
                        <div class="flex items-center h-5">
                            <input id="status_fail" name="result_status" type="radio" value="fail" required class="focus:ring-red-500 h-5 w-5 text-red-600 border-gray-300">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="status_fail" class="font-medium text-gray-700 text-base">ไม่ผ่าน (Fail)</label>
                            <p class="text-gray-500">นิสิตไม่ผ่านเกณฑ์ ต้องพิจารณาใหม่</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">ความคิดเห็น และข้อเสนอแนะ</h3>
                <textarea name="suggestion" rows="4" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-xl p-3" placeholder="ระบุข้อเสนอแนะเพิ่มเติม..."></textarea>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-4 pt-4">
            <a href="index.php?page=employer&action=dashboard" class="bg-white py-3 px-6 border border-gray-300 rounded-xl shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                ยกเลิก
            </a>
            <button type="submit" class="bg-blue-600 py-3 px-8 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all shadow-blue-500/30">
                บันทึกการประเมิน
            </button>
        </div>
    </form>
</div>