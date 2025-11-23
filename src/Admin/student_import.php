<?php
// src/Admin/student_import.php

// Ensure admin is logged in
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php?page=auth&action=login');
    exit;
}
?>

<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-800">นำเข้าข้อมูลนิสิต (Import Students)</h1>
        <p class="text-slate-500 mt-2">เพิ่มข้อมูลนิสิตจำนวนมากเข้าสู่ระบบด้วยไฟล์ CSV</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Upload Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-slate-50">
                    <h2 class="text-lg font-semibold text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                        อัปโหลดไฟล์ CSV
                    </h2>
                </div>

                <div class="p-8">
                    <?php if (isset($_GET['status'])): ?>
                        <?php if ($_GET['status'] === 'success'): ?>
                            <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4 flex items-start gap-3">
                                <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <h3 class="font-semibold text-green-800">นำเข้าข้อมูลสำเร็จ</h3>
                                    <p class="text-sm text-green-600 mt-1">
                                        เพิ่ม: <?= $_GET['added'] ?? 0 ?> คน,
                                        อัปเดต: <?= $_GET['updated'] ?? 0 ?> คน,
                                        ข้าม: <?= $_GET['skipped'] ?? 0 ?> คน
                                    </p>
                                </div>
                            </div>
                        <?php elseif ($_GET['status'] === 'error'): ?>
                            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3">
                                <svg class="w-5 h-5 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <h3 class="font-semibold text-red-800">เกิดข้อผิดพลาด</h3>
                                    <p class="text-sm text-red-600 mt-1"><?= htmlspecialchars($_GET['message'] ?? 'ไม่สามารถนำเข้าข้อมูลได้') ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <form action="index.php?page=admin&action=process_import" method="POST" enctype="multipart/form-data" class="space-y-6">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                        <!-- Drag & Drop Zone -->
                        <div class="relative group">
                            <input type="file" name="csv_file" id="csv_file" accept=".csv" required
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                onchange="updateFileName(this)">

                            <div class="border-2 border-dashed border-slate-300 rounded-xl p-10 text-center transition-all group-hover:border-blue-500 group-hover:bg-blue-50">
                                <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-slate-700 mb-1">ลากไฟล์มาวาง หรือ คลิกเพื่อเลือกไฟล์</h3>
                                <p class="text-sm text-slate-500 mb-4">รองรับไฟล์นามสกุล .csv (UTF-8)</p>
                                <p id="file-name" class="text-sm font-semibold text-blue-600 h-6"></p>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                            <button type="reset" class="px-4 py-2 text-slate-600 hover:bg-slate-100 rounded-lg transition-colors font-medium">
                                ล้างค่า
                            </button>
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-all font-medium flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                                นำเข้าข้อมูล
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Instructions -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 space-y-6">
                <div>
                    <h3 class="font-semibold text-slate-800 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        คำแนะนำการใช้งาน
                    </h3>
                    <ul class="space-y-3 text-sm text-slate-600">
                        <li class="flex gap-2">
                            <span class="bg-slate-100 text-slate-600 w-5 h-5 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">1</span>
                            <span>เตรียมไฟล์ Excel ที่มีข้อมูลนิสิต</span>
                        </li>
                        <li class="flex gap-2">
                            <span class="bg-slate-100 text-slate-600 w-5 h-5 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">2</span>
                            <span>จัดเรียงคอลัมน์ตามลำดับ: <br><strong>รหัสนิสิต, ชื่อ-นามสกุล, สาขา (รหัส), เกรดเฉลี่ย</strong></span>
                        </li>
                        <li class="flex gap-2">
                            <span class="bg-slate-100 text-slate-600 w-5 h-5 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">3</span>
                            <span>บันทึกไฟล์เป็น <strong>CSV (Comma delimited) (*.csv)</strong></span>
                        </li>
                        <li class="flex gap-2">
                            <span class="bg-slate-100 text-slate-600 w-5 h-5 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">4</span>
                            <span>อัปโหลดไฟล์ในหน้านี้</span>
                        </li>
                    </ul>
                </div>

                <div class="border-t border-slate-100 pt-6">
                    <h3 class="font-semibold text-slate-800 mb-3">ตัวอย่างข้อมูลในไฟล์ CSV</h3>
                    <div class="bg-slate-800 rounded-lg p-4 overflow-x-auto">
                        <code class="text-xs text-green-400 font-mono whitespace-pre">6301123456,สมชาย ใจดี,IT,3.50
                            6301123457,สมหญิง รักเรียน,CS,3.75
                            6301123458,มานะ อดทน,BC,2.80</code>
                    </div>
                    <p class="text-xs text-slate-500 mt-2">* บรรทัดแรกควรเป็นข้อมูลเลย (ไม่มีหัวตาราง)</p>
                </div>

                <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                    <h4 class="text-sm font-semibold text-blue-800 mb-1">หมายเหตุ</h4>
                    <p class="text-xs text-blue-600">
                        ระบบจะสร้างบัญชีผู้ใช้ให้อัตโนมัติ โดยใช้ <strong>รหัสนิสิต</strong> เป็นทั้ง Username และ Password เริ่มต้น
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function updateFileName(input) {
        const fileName = input.files[0]?.name;
        document.getElementById('file-name').textContent = fileName ? 'ไฟล์ที่เลือก: ' + fileName : '';
    }
</script>