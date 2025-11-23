<?php
// src/Student/add_company.php
// Database connection and session are already handled by index.php

// Check login
if (!isset($_SESSION['user'])) {
    echo "<script>alert('กรุณาเข้าสู่ระบบก่อนแจ้งเพิ่มสถานที่ฝึกงาน'); window.location.href='index.php?page=auth&action=login';</script>";
    exit;
}

// Fetch Provinces
$stmt = $pdo->query("SELECT * FROM provinces ORDER BY name_th ASC");
$provinces = $stmt->fetchAll();
?>

<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="index.php?page=internships" class="inline-flex items-center text-slate-500 hover:text-blue-600 transition-colors">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            ย้อนกลับ
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
            <h1 class="text-2xl font-bold text-slate-900">แบบฟอร์มแจ้งเพิ่มสถานที่ฝึกงานใหม่</h1>
            <p class="text-slate-600 mt-1">กรอกข้อมูลสถานที่ฝึกงานที่คุณต้องการไปฝึก เพื่อให้เจ้าหน้าที่ตรวจสอบและอนุมัติ</p>
        </div>
        
        <form action="src/Student/process_add_company.php" method="POST" class="p-8 space-y-6">
            <!-- Company Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 mb-1">ชื่อสถานประกอบการ / บริษัท <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" required class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm p-2.5 border" placeholder="เช่น บริษัท ตัวอย่าง จำกัด">
            </div>

            <!-- Address -->
            <div>
                <label for="address" class="block text-sm font-medium text-slate-700 mb-1">ที่อยู่ (เลขที่, หมู่, ซอย, ถนน, ตำบล/แขวง, อำเภอ/เขต) <span class="text-red-500">*</span></label>
                <textarea name="address" id="address" rows="3" required class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm p-2.5 border" placeholder="กรอกที่อยู่โดยละเอียด..."></textarea>
            </div>

            <!-- Province -->
            <div>
                <label for="province" class="block text-sm font-medium text-slate-700 mb-1">จังหวัด <span class="text-red-500">*</span></label>
                <select name="province" id="province" required class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm p-2.5 border">
                    <option value="">-- เลือกจังหวัด --</option>
                    <?php foreach ($provinces as $province): ?>
                        <option value="<?php echo $province['id']; ?>"><?php echo htmlspecialchars($province['name_th']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Contact Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="contact_person" class="block text-sm font-medium text-slate-700 mb-1">ชื่อผู้ติดต่อ / ผู้ประสานงาน <span class="text-red-500">*</span></label>
                    <input type="text" name="contact_person" id="contact_person" required class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm p-2.5 border" placeholder="เช่น คุณสมหญิง จริงใจ">
                </div>
                <div>
                    <label for="phone" class="block text-sm font-medium text-slate-700 mb-1">เบอร์โทรศัพท์ <span class="text-red-500">*</span></label>
                    <input type="text" name="phone" id="phone" required class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm p-2.5 border" placeholder="02-xxx-xxxx, 08x-xxx-xxxx">
                </div>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1">อีเมล (ถ้ามี)</label>
                <input type="email" name="email" id="email" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm p-2.5 border" placeholder="example@company.com">
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-slate-700 mb-1">รายละเอียดเพิ่มเติม / ลักษณะงาน</label>
                <textarea name="description" id="description" rows="4" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm p-2.5 border" placeholder="อธิบายเกี่ยวกับบริษัทหรือลักษณะงานที่เปิดรับ..."></textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg font-medium hover:bg-blue-700 transition-colors shadow-lg shadow-blue-500/30 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    แจ้งเพิ่มสถานที่ฝึกงาน
                </button>
            </div>
        </form>
    </div>
</div>
