<?php
// templates/student/dashboard.php
?>
<div class="max-w-7xl mx-auto">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">ยินดีต้อนรับ, <?= htmlspecialchars($_SESSION['user']['full_name']) ?></h1>
            <p class="text-gray-600 mt-1">รหัสนิสิต: <?= htmlspecialchars($student['student_id']) ?> | สาขา: <?= htmlspecialchars($student['major']) ?></p>
        </div>
        <a href="index.php?page=internships" class="bg-blue-600 text-white px-6 py-2.5 rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-500/30 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            ค้นหาสถานที่ฝึกงาน
        </a>
    </div>

    <!-- Status Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Internship Status -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">ตรวจสอบสถานะ</h3>
            <?php if ($request): ?>
                <div class="space-y-4">
                    <!-- 1. Faculty Approval -->
                    <div>
                        <p class="text-sm text-gray-600 mb-1">สถานะการยื่นขอฝึกงาน</p>
                        <?php
                        $facStatus = $request['faculty_approval_status'] ?? 'pending';
                        $facClass = match ($facStatus) {
                            'approved' => 'text-green-600 font-bold',
                            'rejected' => 'text-red-600 font-bold',
                            default => 'text-amber-500 font-bold'
                        };
                        $facText = match ($facStatus) {
                            'approved' => 'อนุมัติ',
                            'rejected' => 'ไม่อนุมัติ',
                            default => 'รออนุมัติ'
                        };
                        ?>
                        <p class="<?= $facClass ?>"><?= $facText ?></p>
                    </div>

                    <!-- 2. Company Response -->
                    <div>
                        <p class="text-sm text-gray-600 mb-1">สถานะการตอบรับของหน่วยงาน</p>
                        <?php
                        $compStatus = $request['company_response_status'] ?? 'pending';
                        $compClass = match ($compStatus) {
                            'accepted' => 'text-green-600 font-bold',
                            'rejected' => 'text-red-600 font-bold',
                            default => 'text-amber-500 font-bold'
                        };
                        $compText = match ($compStatus) {
                            'accepted' => 'รับเข้าฝึกงาน',
                            'rejected' => 'ปฏิเสธ',
                            default => 'รอการตอบรับ'
                        };
                        ?>
                        <p class="<?= $compClass ?>"><?= $compText ?></p>
                    </div>

                    <!-- 3. Document Response -->
                    <div>
                        <p class="text-sm text-gray-600 mb-1">สถานะการตอบกลับเอกสาร</p>
                        <?php
                        $docStatus = $request['document_response_status'] ?? 'pending';
                        $docClass = match ($docStatus) {
                            'approved' => 'text-green-600 font-bold',
                            'rejected' => 'text-red-600 font-bold',
                            'submitted' => 'text-blue-600 font-bold',
                            default => 'text-amber-500 font-bold'
                        };
                        $docText = match ($docStatus) {
                            'approved' => 'ผ่าน',
                            'rejected' => 'ไม่ผ่าน',
                            'submitted' => 'ส่งแล้ว',
                            default => 'รอตรวจสอบ'
                        };
                        ?>
                        <p class="<?= $docClass ?>"><?= $docText ?></p>
                        <?php if (!empty($request['document_comment'])): ?>
                            <div class="mt-2 p-3 bg-red-50 border border-red-100 rounded-lg">
                                <p class="text-xs text-red-800 font-semibold mb-1">ข้อความจากเจ้าหน้าที่:</p>
                                <p class="text-sm text-red-600"><?= nl2br(htmlspecialchars($request['document_comment'])) ?></p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Print Letter Button -->
                    <div class="mt-6 pt-4 border-t border-gray-100">
                        <form action="src/Admin/print_letter.php" method="POST" target="_blank">
                            <input type="hidden" name="request_ids[]" value="<?= $request['id'] ?>">
                            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 transition-colors shadow-md hover:shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                <span class="font-medium">พิมพ์หนังสือขอความอนุเคราะห์</span>
                            </button>
                        </form>
                    </div>

                    <!-- 4. Evaluation Result -->
                    <div class="mt-6 pt-4 border-t border-gray-100">
                        <p class="text-sm text-gray-600 mb-1">ผลการประเมินฝึกงาน</p>
                        <?php
                        if ($evaluation) {
                            $result = $evaluation['result_status'];
                            $resultClass = match ($result) {
                                'pass' => 'text-green-600 font-bold text-xl',
                                'fail' => 'text-red-600 font-bold text-xl',
                                default => 'text-gray-500 font-bold'
                            };
                            $resultText = match ($result) {
                                'pass' => 'S (Satisfactory)',
                                'fail' => 'U (Unsatisfactory)',
                                default => '-'
                            };
                            echo "<p class=\"$resultClass\">$resultText</p>";
                        } else {
                            echo '<p class="text-gray-400 italic">รอการประเมิน</p>';
                        }
                        ?>
                    </div>
                </div>

                <?php if ($request['status'] === 'approved'): ?>
                    <div class="mt-6 pt-4 border-t border-gray-100">
                        <a href="index.php?page=student&action=daily_log" class="flex items-center gap-2 text-blue-600 hover:text-blue-800 text-sm font-medium mb-2 transition-colors">
                            <span>📝</span> บันทึกการปฏิบัติงาน
                        </a>
                        <a href="index.php?page=student&action=time_sheet" class="flex items-center gap-2 text-blue-600 hover:text-blue-800 text-sm font-medium mb-2 transition-colors">
                            <span>⏰</span> บันทึกเวลาเข้า-ออก
                        </a>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-500 mb-4">ยังไม่ได้ยื่นเรื่องขอฝึกงาน</p>
                    <a href="index.php?page=internships" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                        เริ่มค้นหาที่ฝึกงาน
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Daily Logs Summary -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">บันทึกการทำงานล่าสุด</h3>
            <div class="text-center py-8 text-gray-400">
                <p>ยังไม่มีข้อมูลบันทึก</p>
            </div>
        </div>

        <!-- Advisor Message -->
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-2xl shadow-sm border border-purple-200">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-purple-600 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-slate-900">เล่าสู่กันฟัง</h3>
            </div>
            <p class="text-sm text-slate-600 mb-4">ส่งข้อความปรึกษาปัญหาหรือแจ้งเรื่องต่างๆ กับอาจารย์ที่ปรึกษา</p>
            <a href="index.php?page=student&action=advisor_message" class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition-all shadow-lg shadow-purple-500/30 font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                </svg>
                ส่งข้อความ
            </a>
        </div>
    </div>
</div>