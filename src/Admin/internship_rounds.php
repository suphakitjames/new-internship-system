<?php
// src/Admin/internship_rounds.php

// Ensure user is admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php?page=auth&action=login');
    exit;
}

$error = null;
$success = null;

// Handle Form Submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'add') {
            // Add New Round
            $round_name = trim($_POST['round_name']);
            $year = trim($_POST['year']);
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
            $is_active = isset($_POST['is_active']) ? 1 : 0;

            if (empty($round_name) || empty($year) || empty($start_date) || empty($end_date)) {
                $error = "กรุณากรอกข้อมูลให้ครบถ้วน";
            } else {
                try {
                    $stmt = $pdo->prepare("INSERT INTO internship_rounds (round_name, year, start_date, end_date, is_active) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$round_name, $year, $start_date, $end_date, $is_active]);
                    $success = "เพิ่มรอบฝึกงานเรียบร้อยแล้ว";
                } catch (Exception $e) {
                    $error = "เกิดข้อผิดพลาด: " . $e->getMessage();
                }
            }
        } elseif ($action === 'edit') {
            // Edit Round
            $id = $_POST['round_id'];
            $round_name = trim($_POST['round_name']);
            $year = trim($_POST['year']);
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
            $is_active = isset($_POST['is_active']) ? 1 : 0;

            try {
                $stmt = $pdo->prepare("UPDATE internship_rounds SET round_name = ?, year = ?, start_date = ?, end_date = ?, is_active = ? WHERE id = ?");
                $stmt->execute([$round_name, $year, $start_date, $end_date, $is_active, $id]);
                $success = "แก้ไขข้อมูลเรียบร้อยแล้ว";
            } catch (Exception $e) {
                $error = "เกิดข้อผิดพลาด: " . $e->getMessage();
            }
        } elseif ($action === 'delete') {
            // Delete Round
            $id = $_POST['round_id'];

            try {
                $stmt = $pdo->prepare("DELETE FROM internship_rounds WHERE id = ?");
                if ($stmt->execute([$id])) {
                    $success = "ลบข้อมูลเรียบร้อยแล้ว";
                } else {
                    $error = "เกิดข้อผิดพลาดในการลบข้อมูล";
                }
            } catch (Exception $e) {
                $error = "เกิดข้อผิดพลาด: " . $e->getMessage();
            }
        }
    }
}

// Fetch All Rounds
try {
    $stmt = $pdo->query("SELECT * FROM internship_rounds ORDER BY start_date DESC");
    $rounds = $stmt->fetchAll();
} catch (PDOException $e) {
    $rounds = [];
    $error = "ไม่สามารถดึงข้อมูลรอบฝึกงานได้: " . $e->getMessage();
}
?>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

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
</style>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 flex items-center gap-2">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                จัดการรอบฝึกงาน
            </h2>
            <p class="text-slate-500 text-sm mt-1">กำหนดช่วงเวลาการฝึกงานและสถานะการรับสมัคร</p>
        </div>

        <div class="flex gap-3 w-full md:w-auto">
            <button onclick="openModal('add')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors shadow-sm ml-auto">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                เพิ่มรอบฝึกงาน
            </button>
        </div>
    </div>

    <?php if ($success): ?>
        <div class="bg-green-50 text-green-700 p-4 rounded-lg mb-6 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <?= $success ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="bg-red-50 text-red-700 p-4 rounded-lg mb-6 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <?= $error ?>
        </div>
    <?php endif; ?>

    <div class="overflow-x-auto rounded-lg border border-slate-200 p-2">
        <table id="roundsTable" class="w-full text-left border-collapse display responsive nowrap" style="width:100%">
            <thead>
                <tr class="bg-slate-50 text-slate-600 text-sm uppercase tracking-wider">
                    <th class="p-4 font-semibold border-b border-slate-200">แก้ไข | ลบ</th>
                    <th class="p-4 font-semibold border-b border-slate-200">ชื่อรอบฝึกงาน</th>
                    <th class="p-4 font-semibold border-b border-slate-200">ปีการศึกษา</th>
                    <th class="p-4 font-semibold border-b border-slate-200">วันที่เริ่ม</th>
                    <th class="p-4 font-semibold border-b border-slate-200">วันที่สิ้นสุด</th>
                    <th class="p-4 font-semibold border-b border-slate-200">สถานะ</th>
                </tr>
            </thead>
            <tbody class="text-slate-700 text-sm divide-y divide-slate-100">
                <?php foreach ($rounds as $row): ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="p-4 flex gap-2">
                            <button onclick='openModal("edit", <?= json_encode($row) ?>)' class="p-1.5 bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200 transition-colors" title="แก้ไข">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button onclick="confirmDelete(<?= $row['id'] ?>, '<?= htmlspecialchars($row['round_name']) ?>')" class="p-1.5 bg-red-100 text-red-700 rounded hover:bg-red-200 transition-colors" title="ลบ">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </td>
                        <td class="p-4 font-medium text-slate-900"><?= htmlspecialchars($row['round_name']) ?></td>
                        <td class="p-4"><?= htmlspecialchars($row['year']) ?></td>
                        <td class="p-4"><?= date('d/m/Y', strtotime($row['start_date'])) ?></td>
                        <td class="p-4"><?= date('d/m/Y', strtotime($row['end_date'])) ?></td>
                        <td class="p-4">
                            <?php if ($row['is_active']): ?>
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    เปิดรับสมัคร
                                </span>
                            <?php else: ?>
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    ปิดรับสมัคร
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="roundModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg mx-4 overflow-hidden animate-fade-in-up">
        <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex justify-between items-center">
            <h3 id="modalTitle" class="text-lg font-bold text-slate-800">เพิ่มรอบฝึกงาน</h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form method="POST" class="p-6 space-y-4">
            <input type="hidden" name="action" id="formAction" value="add">
            <input type="hidden" name="round_id" id="roundId">

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">ชื่อรอบฝึกงาน</label>
                <input type="text" name="round_name" id="roundName" required placeholder="เช่น รอบฤดูร้อน" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">ปีการศึกษา</label>
                <input type="text" name="year" id="year" required placeholder="เช่น 2567" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">วันที่เริ่ม</label>
                    <input type="date" name="start_date" id="startDate" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">วันที่สิ้นสุด</label>
                    <input type="date" name="end_date" id="endDate" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" id="isActive" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <label for="isActive" class="text-sm font-medium text-slate-700">เปิดรับสมัคร</label>
            </div>

            <div class="pt-4 flex justify-end gap-3">
                <button type="button" onclick="closeModal()" class="px-4 py-2 text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">ยกเลิก</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm">บันทึกข้อมูล</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Form -->
<form id="deleteForm" method="POST" class="hidden">
    <input type="hidden" name="action" value="delete">
    <input type="hidden" name="round_id" id="deleteRoundId">
</form>

<!-- jQuery and DataTables JS -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<script>
    $(document).ready(function() {
        $('#roundsTable').DataTable({
            responsive: true,
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
            },
            columnDefs: [{
                orderable: false,
                targets: 0
            }]
        });
    });

    function openModal(mode, data = null) {
        const modal = document.getElementById('roundModal');
        const title = document.getElementById('modalTitle');
        const formAction = document.getElementById('formAction');

        // Reset form
        document.getElementById('roundId').value = '';
        document.getElementById('roundName').value = '';
        document.getElementById('year').value = '';
        document.getElementById('startDate').value = '';
        document.getElementById('endDate').value = '';
        document.getElementById('isActive').checked = true;

        if (mode === 'edit' && data) {
            title.textContent = 'แก้ไขรอบฝึกงาน';
            formAction.value = 'edit';

            document.getElementById('roundId').value = data.id;
            document.getElementById('roundName').value = data.round_name;
            document.getElementById('year').value = data.year;
            document.getElementById('startDate').value = data.start_date;
            document.getElementById('endDate').value = data.end_date;
            document.getElementById('isActive').checked = data.is_active == 1;
        } else {
            title.textContent = 'เพิ่มรอบฝึกงาน';
            formAction.value = 'add';
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        const modal = document.getElementById('roundModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function confirmDelete(id, name) {
        if (confirm('คุณต้องการลบข้อมูลรอบ ' + name + ' ใช่หรือไม่?')) {
            document.getElementById('deleteRoundId').value = id;
            document.getElementById('deleteForm').submit();
        }
    }

    // Close modal on outside click
    document.getElementById('roundModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
</script>