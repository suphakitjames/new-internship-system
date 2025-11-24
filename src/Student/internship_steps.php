<?php
// src/Student/internship_steps.php

// Public page - No auth check required
?>

<div class="max-w-6xl mx-auto py-12 px-4">
    <div class="text-center mb-16">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">ขั้นตอนการยื่นเรื่องฝึกประสบการณ์วิชาชีพ</h1>
        <p class="text-xl text-gray-600">คู่มือการใช้งานระบบสำหรับนิสิต ตั้งแต่เริ่มต้นจนถึงการพิมพ์เอกสาร</p>
    </div>

    <div class="space-y-24 relative">
        <!-- Connecting Line -->
        <div class="hidden lg:block absolute left-1/2 top-0 bottom-0 w-0.5 bg-blue-100 -translate-x-1/2 z-0"></div>

        <!-- Step 1 -->
        <div class="relative z-10">
            <div class="flex flex-col lg:flex-row items-center gap-12">
                <div class="flex-1 text-right lg:pr-12">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-600 text-white text-2xl font-bold mb-6 shadow-lg shadow-blue-500/30 lg:hidden">1</div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">1. ค้นหาและเลือกสถานประกอบการ</h3>
                    <p class="text-gray-600 text-lg leading-relaxed mb-6">
                        เข้าสู่เมนู "สถานที่ฝึกงาน" ท่านสามารถค้นหาสถานประกอบการที่สนใจได้ตามจังหวัด หรือค้นหาจากชื่อ
                        เมื่อพบสถานที่ที่ต้องการ ให้กดปุ่ม <span class="text-blue-600 font-semibold">"ยื่นเรื่อง"</span> เพื่อส่งคำขอฝึกงาน
                    </p>
                    <div class="flex justify-end">
                        <a href="index.php?page=internships" class="inline-flex items-center gap-2 text-blue-600 font-medium hover:text-blue-800 transition">
                            ไปที่หน้าค้นหา
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="hidden lg:flex absolute left-1/2 -translate-x-1/2 items-center justify-center w-16 h-16 rounded-full bg-blue-600 text-white text-2xl font-bold shadow-xl shadow-blue-500/30 border-4 border-white">1</div>

                <div class="flex-1 lg:pl-12">
                    <div class="bg-white p-2 rounded-2xl shadow-xl border border-gray-100 transform hover:scale-105 transition duration-300">
                        <img src="guide_step_search_1763965366785.png" alt="หน้าค้นหาสถานที่ฝึกงาน" class="rounded-xl w-full shadow-sm">
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 2 -->
        <div class="relative z-10">
            <div class="flex flex-col lg:flex-row-reverse items-center gap-12">
                <div class="flex-1 text-left lg:pl-12">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-600 text-white text-2xl font-bold mb-6 shadow-lg shadow-blue-500/30 lg:hidden">2</div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">2. รอการตอบรับและอนุมัติ</h3>
                    <p class="text-gray-600 text-lg leading-relaxed mb-6">
                        หลังจากยื่นเรื่องแล้ว ระบบจะแสดงสถานะการดำเนินการ ท่านสามารถติดตามผลได้ที่หน้า Dashboard
                        <br><br>
                        <span class="flex items-center gap-2 mb-2">
                            <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                            <strong>รอการตอบรับ:</strong> รอสถานประกอบการพิจารณา
                        </span>
                        <span class="flex items-center gap-2">
                            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                            <strong>อนุมัติ:</strong> ผ่านการพิจารณาจากทั้งสถานประกอบการและคณะ
                        </span>
                    </p>
                </div>

                <div class="hidden lg:flex absolute left-1/2 -translate-x-1/2 items-center justify-center w-16 h-16 rounded-full bg-blue-600 text-white text-2xl font-bold shadow-xl shadow-blue-500/30 border-4 border-white">2</div>

                <div class="flex-1 lg:pr-12">
                    <div class="bg-white p-2 rounded-2xl shadow-xl border border-gray-100 transform hover:scale-105 transition duration-300">
                        <img src="guide_step_dashboard_1763965392873.png" alt="หน้าตรวจสอบสถานะ" class="rounded-xl w-full shadow-sm">
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 3 -->
        <div class="relative z-10">
            <div class="flex flex-col lg:flex-row items-center gap-12">
                <div class="flex-1 text-right lg:pr-12">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-600 text-white text-2xl font-bold mb-6 shadow-lg shadow-blue-500/30 lg:hidden">3</div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">3. พิมพ์เอกสารและเริ่มฝึกงาน</h3>
                    <p class="text-gray-600 text-lg leading-relaxed mb-6">
                        เมื่อสถานะทุกอย่างเป็น "อนุมัติ" ปุ่ม <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-sm font-semibold">พิมพ์หนังสือขอความอนุเคราะห์</span> จะปรากฏขึ้น
                        ท่านสามารถกดเพื่อดาวน์โหลดหรือพิมพ์เอกสาร นำไปยื่นที่สถานประกอบการในวันเริ่มฝึกงานได้ทันที
                    </p>
                </div>

                <div class="hidden lg:flex absolute left-1/2 -translate-x-1/2 items-center justify-center w-16 h-16 rounded-full bg-blue-600 text-white text-2xl font-bold shadow-xl shadow-blue-500/30 border-4 border-white">3</div>

                <div class="flex-1 lg:pl-12">
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-8 rounded-3xl border border-blue-100 text-center">
                        <div class="w-20 h-20 bg-white rounded-2xl flex items-center justify-center text-blue-600 shadow-md mx-auto mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-2">พร้อมสำหรับการฝึกงาน!</h4>
                        <p class="text-gray-600">ขอให้โชคดีกับการฝึกประสบการณ์วิชาชีพ</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="mt-20 text-center">
        <a href="index.php" class="inline-flex items-center gap-2 bg-gray-800 text-white px-8 py-3 rounded-xl hover:bg-gray-900 transition shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            กลับสู่หน้าหลัก
        </a>
    </div>
</div>