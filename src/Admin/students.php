<?php
// src/Admin/students.php

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
            // Add New Student
            $student_id = trim($_POST['student_id']);
            $full_name = trim($_POST['full_name']);
            $major = trim($_POST['major']);
            $gpa = trim($_POST['gpa']);
            $year_level = trim($_POST['year_level']);

            // Basic Validation
            if (empty($student_id) || empty($full_name)) {
                $error = "กรุณากรอกรหัสนิสิตและชื่อ-นามสกุล";
            } else {
                try {
                    $pdo->beginTransaction();

                    // 1. Create User Account
                    // Default password is the student_id
                    $password = password_hash($student_id, PASSWORD_DEFAULT);
                    $username = $student_id; // Username is student_id

                    $stmt = $pdo->prepare("INSERT INTO users (username, password, role, full_name) VALUES (?, ?, 'student', ?)");
                    $stmt->execute([$username, $password, $full_name]);
                    $user_id = $pdo->lastInsertId();

                    // 2. Create Student Profile
                    $stmt = $pdo->prepare("INSERT INTO students (user_id, student_id, major, year_level, gpa) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$user_id, $student_id, $major, $year_level, $gpa]);

                    $pdo->commit();
                    $success = "เพิ่มข้อมูลนิสิตเรียบร้อยแล้ว";
                } catch (Exception $e) {
                    $pdo->rollBack();
                    $error = "เกิดข้อผิดพลาด: " . $e->getMessage();
                }
            }
        } elseif ($action === 'edit') {
            // Edit Student
            $user_id = $_POST['user_id'];
            $full_name = trim($_POST['full_name']);
            $major = trim($_POST['major']);
            $gpa = trim($_POST['gpa']);
            $year_level = trim($_POST['year_level']);
            $student_id = trim($_POST['student_id']); // Usually shouldn't change, but allowed here

            try {
                $pdo->beginTransaction();

                // Update User
                $stmt = $pdo->prepare("UPDATE users SET full_name = ? WHERE id = ?");
                $stmt->execute([$full_name, $user_id]);

                // Update Student
                $stmt = $pdo->prepare("UPDATE students SET student_id = ?, major = ?, year_level = ?, gpa = ? WHERE user_id = ?");
                $stmt->execute([$student_id, $major, $year_level, $gpa, $user_id]);

                $pdo->commit();
                $success = "แก้ไขข้อมูลเรียบร้อยแล้ว";
            } catch (Exception $e) {
                $pdo->rollBack();
                $error = "เกิดข้อผิดพลาด: " . $e->getMessage();
            }
        } elseif ($action === 'delete') {
            // Delete Student
            $user_id = $_POST['user_id'];

            try {
                // Cascade delete should handle the rest if set up correctly in DB
                $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role = 'student'");
                if ($stmt->execute([$user_id])) {
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

// Fetch All Students (No server-side search needed for DataTables client-side)
$sql = "SELECT u.id, u.full_name, s.student_id, s.major, s.gpa, s.year_level 
        FROM users u 
        JOIN students s ON u.id = s.user_id 
        WHERE u.role = 'student'
        ORDER BY s.student_id ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$students = $stmt->fetchAll();
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                รายชื่อนิสิตทั้งหมด
            </h2>
            <p class="text-slate-500 text-sm mt-1">จัดการข้อมูลนิสิต เพิ่ม ลบ แก้ไข รายชื่อนิสิตในระบบ</p>
        </div>

        <div class="flex gap-3 w-full md:w-auto">
            <button onclick="openModal('add')" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors shadow-sm ml-auto">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                เพิ่มข้อมูลนิสิต
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
        <table id="studentsTable" class="w-full text-left border-collapse display responsive nowrap" style="width:100%">
            <thead>
                <tr class="bg-slate-50 text-slate-600 text-sm uppercase tracking-wider">
                    <th class="p-4 font-semibold border-b border-slate-200">แก้ไข | ลบ</th>
                    <th class="p-4 font-semibold border-b border-slate-200">รหัสนิสิต</th>
                    <th class="p-4 font-semibold border-b border-slate-200">ชื่อ-สกุล</th>
                    <th class="p-4 font-semibold border-b border-slate-200">ชั้นปี</th>
                    <th class="p-4 font-semibold border-b border-slate-200">เกรดเฉลี่ย</th>
                    <th class="p-4 font-semibold border-b border-slate-200">สาขา</th>
                </tr>
            </thead>
            <tbody class="text-slate-700 text-sm divide-y divide-slate-100">
                <?php foreach ($students as $row): ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="p-4 flex gap-2">
                            <button onclick='openModal("edit", <?= json_encode($row) ?>)' class="p-1.5 bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200 transition-colors" title="แก้ไข">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button onclick="confirmDelete(<?= $row['id'] ?>, '<?= htmlspecialchars($row['full_name']) ?>')" class="p-1.5 bg-red-100 text-red-700 rounded hover:bg-red-200 transition-colors" title="ลบ">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </td>
                        <td class="p-4 font-medium text-slate-900"><?= htmlspecialchars($row['student_id'] ?? '') ?></td>
                        <td class="p-4"><?= htmlspecialchars($row['full_name'] ?? '') ?></td>
                        <td class="p-4"><?= htmlspecialchars($row['year_level'] ?? '') ?></td>
                        <td class="p-4"><?= htmlspecialchars($row['gpa'] ?? '') ?></td>
                        <td class="p-4"><?= htmlspecialchars($row['major'] ?? '') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="studentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4 overflow-hidden animate-fade-in-up">
        <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex justify-between items-center">
            <h3 id="modalTitle" class="text-lg font-bold text-slate-800">เพิ่มข้อมูลนิสิต</h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form method="POST" class="p-6 space-y-4">
            <input type="hidden" name="action" id="formAction" value="add">
            <input type="hidden" name="user_id" id="userId">

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">รหัสนิสิต</label>
                <input type="text" name="student_id" id="studentId" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">ชื่อ-นามสกุล</label>
                <input type="text" name="full_name" id="fullName" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">ชั้นปี</label>
                    <input type="number" name="year_level" id="yearLevel" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">เกรดเฉลี่ย</label>
                    <input type="number" step="0.01" name="gpa" id="gpa" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">สาขาวิชา</label>
                <select name="major" id="major" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">เลือกสาขา</option>
                    <?php
                    // Fetch majors for dropdown
                    $stmt = $pdo->query("SELECT * FROM majors ORDER BY name ASC");
                    while ($m = $stmt->fetch()) {
                        echo "<option value='" . $m['id'] . "'>" . $m['name'] . "</option>";
                    }
                    ?>
                </select>
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
    <input type="hidden" name="user_id" id="deleteUserId">
</form>

<!-- jQuery and DataTables JS -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<script>
    $(document).ready(function() {
        $('#studentsTable').DataTable({
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
                } // Disable sorting on the Actions column
            ]
        });
    });

    function openModal(mode, data = null) {
        const modal = document.getElementById('studentModal');
        const title = document.getElementById('modalTitle');
        const formAction = document.getElementById('formAction');

        // Reset form
        document.getElementById('userId').value = '';
        document.getElementById('studentId').value = '';
        document.getElementById('fullName').value = '';
        document.getElementById('yearLevel').value = '';
        document.getElementById('gpa').value = '';
        document.getElementById('major').value = '';

        if (mode === 'edit' && data) {
            title.textContent = 'แก้ไขข้อมูลนิสิต';
            formAction.value = 'edit';

            document.getElementById('userId').value = data.id;
            document.getElementById('studentId').value = data.student_id;
            document.getElementById('fullName').value = data.full_name;
            document.getElementById('yearLevel').value = data.year_level;
            document.getElementById('gpa').value = data.gpa;
            document.getElementById('major').value = data.major;
        } else {
            title.textContent = 'เพิ่มข้อมูลนิสิต';
            formAction.value = 'add';
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        const modal = document.getElementById('studentModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function confirmDelete(id, name) {
        if (confirm('คุณต้องการลบข้อมูลของ ' + name + ' ใช่หรือไม่?')) {
            document.getElementById('deleteUserId').value = id;
            document.getElementById('deleteForm').submit();
        }
    }

    // Close modal on outside click
    document.getElementById('studentModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
</script>