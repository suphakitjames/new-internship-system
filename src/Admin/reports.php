<?php
// src/Admin/reports.php

// Ensure user is admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php?page=auth&action=login');
    exit;
}

// Fetch Internship Rounds for Filter
try {
    $stmt = $pdo->query("SELECT * FROM internship_rounds ORDER BY start_date DESC");
    $rounds = $stmt->fetchAll();
} catch (PDOException $e) {
    $rounds = [];
}

// Fetch Provinces for Filter
try {
    // Try to fetch from provinces table first
    $stmt = $pdo->query("SELECT * FROM provinces ORDER BY name_th ASC");
    $provinces = $stmt->fetchAll();
} catch (PDOException $e) {
    // Fallback: Fetch distinct provinces from companies table
    $stmt = $pdo->query("SELECT DISTINCT province as name_th, province as id FROM companies WHERE province IS NOT NULL AND province != '' ORDER BY province ASC");
    $provinces = $stmt->fetchAll();
}

// Build Query for Report
$round_id = $_GET['round_id'] ?? '';
$province_id = $_GET['province_id'] ?? '';

$sql = "SELECT 
            ir.*,
            u.full_name,
            s.student_id,
            s.year_level,
            c.name as company_name,
            c.province as company_province,
            p.name_th as province_name_th,
            r.round_name,
            r.year as round_year
        FROM internship_requests ir
        LEFT JOIN students s ON ir.student_id = s.user_id
        LEFT JOIN users u ON s.user_id = u.id
        LEFT JOIN companies c ON ir.company_id = c.id
        LEFT JOIN internship_rounds r ON ir.round_id = r.id
        LEFT JOIN provinces p ON c.province = p.id
        WHERE 1=1";

$params = [];

if (!empty($round_id)) {
    $sql .= " AND ir.round_id = ?";
    $params[] = $round_id;
}

if (!empty($province_id)) {
    // Check if province_id is numeric (ID) or string (Name)
    // Assuming if it's numeric it's an ID from provinces table, otherwise it's a string from companies table
    // But to be safe, let's check against both if we are not sure about the schema consistency
    // Based on previous files, c.province might be an ID.
    $sql .= " AND (c.province = ? OR p.id = ?)";
    $params[] = $province_id;
    $params[] = $province_id;
}

$sql .= " ORDER BY ir.request_date DESC";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $report_data = $stmt->fetchAll();
} catch (PDOException $e) {
    $report_data = [];
    $error = "เกิดข้อผิดพลาด: " . $e->getMessage();
}
?>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

<style>
    /* Custom DataTables Styling to match Tailwind */
    .dataTables_wrapper .dataTables_length select {
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        padding: 0.25rem 2rem 0.25rem 0.5rem;
    }

    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        padding: 0.25rem 0.5rem;
        margin-left: 0.5rem;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #2563eb !important;
        color: white !important;
        border: 1px solid #2563eb !important;
        border-radius: 0.375rem;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #dbeafe !important;
        color: #1e40af !important;
        border: 1px solid #dbeafe !important;
    }

    table.dataTable.no-footer {
        border-bottom: 1px solid #e2e8f0;
    }

    /* Buttons Styling */
    .dt-buttons .dt-button {
        background-color: #f8fafc !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 0.5rem !important;
        color: #475569 !important;
        font-size: 0.875rem !important;
        padding: 0.5rem 1rem !important;
        margin-right: 0.5rem !important;
        transition: all 0.2s !important;
    }

    .dt-buttons .dt-button:hover {
        background-color: #e2e8f0 !important;
        color: #1e293b !important;
    }
</style>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 flex items-center gap-2">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                รายงานจำนวนนิสิตที่ฝึกงาน
            </h2>
            <p class="text-slate-500 text-sm mt-1">สรุปข้อมูลนิสิตที่ออกฝึกงาน แยกตามรอบและจังหวัด</p>
        </div>
    </div>

    <!-- Filters -->
    <form method="GET" action="index.php" class="bg-slate-50 p-4 rounded-xl border border-slate-200 mb-6">
        <input type="hidden" name="page" value="admin">
        <input type="hidden" name="action" value="reports">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">เลือกรอบการฝึกงาน</label>
                <select name="round_id" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">ทั้งหมด</option>
                    <?php foreach ($rounds as $r): ?>
                        <option value="<?= $r['id'] ?>" <?= $round_id == $r['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($r['round_name']) ?> (<?= $r['year'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">เลือกจังหวัด</label>
                <select name="province_id" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">ทั้งหมด</option>
                    <?php foreach ($provinces as $p): ?>
                        <?php
                        $val = isset($p['id']) ? $p['id'] : $p['name_th'];
                        $label = isset($p['name_th']) ? $p['name_th'] : $p['province'];
                        ?>
                        <option value="<?= htmlspecialchars($val) ?>" <?= $province_id == $val ? 'selected' : '' ?>>
                            <?= htmlspecialchars($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2 transition-colors shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    ค้นหา
                </button>
            </div>
        </div>
    </form>

    <div class="overflow-x-auto rounded-lg border border-slate-200 p-2">
        <table id="reportsTable" class="w-full text-left border-collapse display responsive nowrap" style="width:100%">
            <thead>
                <tr class="bg-slate-50 text-slate-600 text-sm uppercase tracking-wider">
                    <th class="p-4 font-semibold border-b border-slate-200">รหัสนิสิต</th>
                    <th class="p-4 font-semibold border-b border-slate-200">ชื่อ-นามสกุล</th>
                    <th class="p-4 font-semibold border-b border-slate-200">ชื่อบริษัท</th>
                    <th class="p-4 font-semibold border-b border-slate-200">ปีการศึกษา</th>
                    <th class="p-4 font-semibold border-b border-slate-200">สถานะ</th>
                    <th class="p-4 font-semibold border-b border-slate-200">จังหวัด</th>
                </tr>
            </thead>
            <tbody class="text-slate-700 text-sm divide-y divide-slate-100">
                <?php foreach ($report_data as $row): ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="p-4 font-medium text-slate-900"><?= htmlspecialchars($row['student_id']) ?></td>
                        <td class="p-4"><?= htmlspecialchars($row['full_name']) ?></td>
                        <td class="p-4"><?= htmlspecialchars($row['company_name']) ?></td>
                        <td class="p-4"><?= htmlspecialchars($row['round_year']) ?></td>
                        <td class="p-4">
                            <?php
                            $statusClass = match ($row['status']) {
                                'approved' => 'bg-green-100 text-green-800',
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'rejected' => 'bg-red-100 text-red-800',
                                default => 'bg-gray-100 text-gray-800'
                            };
                            $statusText = match ($row['status']) {
                                'approved' => 'อนุมัติ',
                                'pending' => 'รออนุมัติ',
                                'rejected' => 'ไม่อนุมัติ',
                                default => $row['status']
                            };
                            ?>
                            <span class="px-2 py-1 rounded-full text-xs font-medium <?= $statusClass ?>">
                                <?= $statusText ?>
                            </span>
                        </td>
                        <td class="p-4"><?= htmlspecialchars($row['province_name_th'] ?? $row['company_province']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- jQuery and DataTables JS -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<!-- DataTables Buttons -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function() {
        $('#reportsTable').DataTable({
            responsive: true,
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'excel',
                    text: 'Export Excel',
                    className: 'bg-green-600 text-white hover:bg-green-700 rounded px-3 py-2'
                },
                {
                    extend: 'print',
                    text: 'Print',
                    className: 'bg-gray-600 text-white hover:bg-gray-700 rounded px-3 py-2'
                }
            ],
            language: {
                "lengthMenu": "แสดง _MENU_ รายการ",
                "zeroRecords": "ไม่พบข้อมูลที่ค้นหา",
                "info": "แสดงหน้า _PAGE_ จาก _PAGES_",
                "infoEmpty": "ไม่มีข้อมูล",
                "infoFiltered": "(กรองจากทั้งหมด _MAX_ รายการ)",
                "search": "ค้นหา:",
                "paginate": {
                    "first": "หน้าแรก",
                    "last": "หน้าสุดท้าย",
                    "next": "ถัดไป",
                    "previous": "ก่อนหน้า"
                }
            }
        });
    });
</script>