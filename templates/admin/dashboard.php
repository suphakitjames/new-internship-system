<?php
// templates/admin/dashboard.php
?>
<div class="space-y-6 fade-in">
    <!-- Stats Grid with Modern Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1: Students (Green) -->
        <div class="stat-card bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-lg hover:shadow-xl p-6 text-white transform transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div class="text-right">
                    <div class="text-3xl font-bold"><?= $student_count ?></div>
                    <div class="text-sm text-white/80">คน</div>
                </div>
            </div>
            <div class="text-sm font-medium text-white/90">อนุมัติการขอฝึกงาน</div>
        </div>

        <!-- Card 2: Companies (Orange) -->
        <div class="stat-card bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl shadow-lg hover:shadow-xl p-6 text-white transform transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                </div>
                <div class="text-right">
                    <div class="text-3xl font-bold"><?= $company_count ?></div>
                    <div class="text-sm text-white/80">แห่ง</div>
                </div>
            </div>
            <div class="text-sm font-medium text-white/90">ทบทวนการประเมินฝึกงาน</div>
        </div>

        <!-- Card 3: Pending Requests (Blue) -->
        <div class="stat-card bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg hover:shadow-xl p-6 text-white transform transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="text-right">
                    <div class="text-3xl font-bold"><?= $pending_requests ?></div>
                    <div class="text-sm text-white/80">คำขอ</div>
                </div>
            </div>
            <div class="text-sm font-medium text-white/90">จัดการข้อมูลนิสิต</div>
        </div>

        <!-- Card 4: Pending Companies (Red) -->
        <div class="stat-card bg-gradient-to-br from-red-500 to-red-600 rounded-2xl shadow-lg hover:shadow-xl p-6 text-white transform transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div class="text-right">
                    <div class="text-3xl font-bold"><?= $pending_companies ?></div>
                    <div class="text-sm text-white/80">แห่ง</div>
                </div>
            </div>
            <div class="text-sm font-medium text-white/90">จัดการสถานที่ฝึกงาน</div>
        </div>
    </div>

    <!-- Chart and Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chart Section (2/3 width) -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-900">สถิติสถานที่ฝึกงานแยกตามจังหวัด</h3>
                    <p class="text-sm text-slate-500 mt-1">แสดงจำนวนสถานที่ฝึกงานในแต่ละจังหวัด (Top 15)</p>
                </div>
                <div class="flex items-center gap-2 text-sm">
                    <div class="w-3 h-3 bg-blue-500 rounded"></div>
                    <span class="text-slate-600">จำนวน</span>
                </div>
            </div>
            <div class="relative h-80">
                <canvas id="provinceChart"></canvas>
            </div>
        </div>

        <!-- Quick Actions (1/3 width) -->
        <div class="space-y-6">
            <!-- Approval Actions -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    การอนุมัติ
                </h3>
                <div class="space-y-3">
                    <a href="index.php?page=admin&action=requests" class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl hover:from-blue-100 hover:to-blue-200 transition-all group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center text-white group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <span class="font-medium text-slate-700">คำขอฝึกงาน</span>
                        </div>
                        <?php if ($pending_requests > 0): ?>
                            <span class="bg-red-500 text-white text-xs px-3 py-1 rounded-full font-bold animate-pulse"><?= $pending_requests ?></span>
                        <?php endif; ?>
                    </a>

                    <a href="index.php?page=admin&action=companies" class="flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-xl hover:from-green-100 hover:to-green-200 transition-all group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center text-white group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <span class="font-medium text-slate-700">สถานที่ฝึกงาน</span>
                        </div>
                        <?php if ($pending_companies > 0): ?>
                            <span class="bg-red-500 text-white text-xs px-3 py-1 rounded-full font-bold animate-pulse"><?= $pending_companies ?></span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>

            <!-- Management Actions -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    จัดการข้อมูล
                </h3>
                <div class="space-y-3">
                    <a href="index.php?page=admin&action=students" class="flex items-center gap-3 p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-all group">
                        <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center text-white group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <span class="font-medium text-slate-700">ข้อมูลนิสิต</span>
                    </a>

                    <a href="index.php?page=admin&action=print_letters" class="flex items-center gap-3 p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-all group">
                        <div class="w-10 h-10 bg-orange-600 rounded-lg flex items-center justify-center text-white group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                        </div>
                        <span class="font-medium text-slate-700">พิมพ์หนังสือ</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Province Chart
    const ctx = document.getElementById('provinceChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($province_labels) ?>,
                datasets: [{
                    label: 'จำนวนสถานที่ฝึกงาน',
                    data: <?= json_encode($province_counts) ?>,
                    backgroundColor: 'rgba(59, 130, 246, 0.8)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 2,
                    borderRadius: 8,
                    hoverBackgroundColor: 'rgba(37, 99, 235, 0.9)',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        borderColor: 'rgba(59, 130, 246, 0.5)',
                        borderWidth: 1,
                        cornerRadius: 8
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: {
                                size: 12
                            },
                            color: '#64748b'
                        },
                        grid: {
                            color: 'rgba(148, 163, 184, 0.1)',
                            drawBorder: false
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 11
                            },
                            color: '#64748b',
                            maxRotation: 45,
                            minRotation: 45
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
</script>