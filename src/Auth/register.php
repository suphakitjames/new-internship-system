<?php
// src/Auth/register.php

// Fetch Majors
$majors_stmt = $pdo->query("SELECT * FROM majors ORDER BY name ASC");
$majors = $majors_stmt->fetchAll();
?>
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-xl shadow-lg border border-gray-100">
        <div class="text-center">
            <h2 class="mt-2 text-3xl font-extrabold text-gray-900">ลงทะเบียนนิสิตใหม่</h2>
            <p class="mt-2 text-sm text-gray-600">
                กรอกข้อมูลเพื่อสร้างบัญชีผู้ใช้งานสำหรับยื่นขอฝึกงาน
            </p>
        </div>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <form class="mt-8 space-y-6" action="index.php?page=auth&action=process_register" method="POST">
            <div class="rounded-md shadow-sm space-y-4">
                <div>
                    <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">ชื่อ-นามสกุล</label>
                    <input id="full_name" name="full_name" type="text" required class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" placeholder="นาย สมชาย ใจดี">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="student_id" class="block text-sm font-medium text-gray-700 mb-1">รหัสนิสิต</label>
                        <input id="student_id" name="student_id" type="text" required class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" placeholder="6xxxxxxxx">
                    </div>
                    <div>
                        <label for="major" class="block text-sm font-medium text-gray-700 mb-1">สาขาวิชา</label>
                        <select id="major" name="major" required class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm">
                            <option value="">เลือกสาขา</option>
                            <?php foreach ($majors as $major): ?>
                                <option value="<?php echo htmlspecialchars($major['id']); ?>">
                                    <?php echo htmlspecialchars($major['name']); ?> (<?php echo htmlspecialchars($major['id']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">ชื่อผู้ใช้งาน (Username)</label>
                    <input id="username" name="username" type="text" required class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" placeholder="username">
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">รหัสผ่าน</label>
                    <input id="password" name="password" type="password" required class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" placeholder="••••••••">
                </div>

                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">ยืนยันรหัสผ่าน</label>
                    <input id="confirm_password" name="confirm_password" type="password" required class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" placeholder="••••••••">
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-lg shadow-blue-500/30 transition-all">
                    ลงทะเบียน
                </button>
            </div>
            
            <div class="text-center mt-4">
                <a href="index.php?page=auth&action=login" class="text-sm text-blue-600 hover:text-blue-500">มีบัญชีอยู่แล้ว? เข้าสู่ระบบ</a>
            </div>
        </form>
    </div>
</div>
