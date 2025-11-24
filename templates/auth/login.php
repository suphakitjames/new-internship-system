<?php
// templates/auth/login.php
?>
<div class="min-h-[calc(100vh-200px)] flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-2xl shadow-xl border border-gray-100">
        <div class="text-center">
            <div class="mx-auto h-16 w-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
            </div>
            <h2 class="mt-2 text-3xl font-extrabold text-gray-900">เข้าสู่ระบบนิสิต</h2>
            <p class="mt-2 text-sm text-gray-600">
                หรือ <a href="index.php?page=auth&action=register" class="font-medium text-blue-600 hover:text-blue-500 transition">ลงทะเบียนนิสิตใหม่</a>
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

        <form class="mt-8 space-y-6" action="index.php?page=auth&action=process_login" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <input type="hidden" name="role_type" value="student">
            <div class="rounded-md shadow-sm -space-y-px">
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">ชื่อผู้ใช้งาน</label>
                    <input id="username" name="username" type="text" required class="appearance-none rounded-lg relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm transition" placeholder="Username">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">รหัสผ่าน</label>
                    <input id="password" name="password" type="password" required class="appearance-none rounded-lg relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm transition" placeholder="Password">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="remember-me" class="ml-2 block text-sm text-gray-900">จำฉันไว้ในระบบ</label>
                </div>

                <div class="text-sm">
                    <a href="#" class="font-medium text-blue-600 hover:text-blue-500">ลืมรหัสผ่าน?</a>
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition shadow-lg shadow-blue-500/30">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-blue-500 group-hover:text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    เข้าสู่ระบบ
                </button>
            </div>

            <div class="text-center pt-4 border-t border-gray-200">
                <a href="index.php?page=auth&action=admin_login" class="text-sm text-slate-600 hover:text-blue-600 transition-colors flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    เข้าสู่ระบบสำหรับ Admin
                </a>
                <a href="index.php?page=auth&action=advisor_login" class="text-sm text-slate-600 hover:text-purple-600 transition-colors flex items-center justify-center gap-2 mt-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    เข้าสู่ระบบสำหรับอาจารย์ที่ปรึกษา
                </a>
            </div>
        </form>
    </div>
</div>