<?php
// src/Student/time_sheet.php

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header('Location: index.php?page=auth&action=login');
    exit;
}

$user_id = $_SESSION['user']['id'];

// Fetch time sheets
$stmt = $pdo->prepare("SELECT * FROM time_sheets WHERE student_id = ? ORDER BY date DESC");
$stmt->execute([$user_id]);
$time_sheets = $stmt->fetchAll();
?>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.tailwind.min.css">

<div class="max-w-7xl mx-auto">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">บันทึกเวลาปฏิบัติงาน (Time Sheet)</h1>
            <p class="text-slate-600 mt-1">บันทึกเวลาเข้า-ออก ของการปฏิบัติงาน</p>
        </div>
        <button onclick="document.getElementById('timeModal').classList.remove('hidden')" class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-500/30 flex items-center gap-2 font-medium">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            ลงเวลาใหม่
        </button>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
            <?= htmlspecialchars($_SESSION['success']) ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <!-- DataTable -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6">
            <table id="timeSheetTable" class="display w-full">
                <thead>
                    <tr>
                        <th class="text-left">วันที่</th>
                        <th class="text-center">เวลาเข้า</th>
                        <th class="text-center">เวลาออก</th>
                        <th class="text-center">ชั่วโมงทำงาน</th>
                        <th class="text-left">หมายเหตุ</th>
                        <th class="text-center">การจัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($time_sheets as $ts): 
                        // Calculate work hours
                        $time_in = strtotime($ts['time_in']);
                        $time_out = strtotime($ts['time_out']);
                        $hours = ($time_out - $time_in) / 3600;
                    ?>
                        <tr>
                            <td>
                                <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-lg font-medium text-sm">
                                    <?= date('d/m/Y', strtotime($ts['date'])) ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="inline-flex items-center gap-1 text-green-700 font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                    </svg>
                                    <?= date('H:i', $time_in) ?> น.
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="inline-flex items-center gap-1 text-red-700 font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    <?= date('H:i', $time_out) ?> น.
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="inline-flex items-center px-3 py-1 bg-purple-100 text-purple-800 rounded-lg font-bold text-sm">
                                    <?= number_format($hours, 1) ?> ชม.
                                </span>
                            </td>
                            <td>
                                <?php if (!empty($ts['notes'])): ?>
                                    <div class="text-slate-600 text-sm max-w-xs">
                                        <?= htmlspecialchars(substr($ts['notes'], 0, 50)) ?>
                                        <?= strlen($ts['notes']) > 50 ? '...' : '' ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-slate-400 text-sm">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button 
                                        class="btn-edit-time inline-flex items-center gap-1 px-3 py-1.5 bg-amber-100 text-amber-700 rounded-lg hover:bg-amber-200 transition-colors text-sm font-medium" 
                                        title="แก้ไข"
                                        data-id="<?= $ts['id'] ?>"
                                        data-date="<?= $ts['date'] ?>"
                                        data-time-in="<?= date('H:i', strtotime($ts['time_in'])) ?>"
                                        data-time-out="<?= date('H:i', strtotime($ts['time_out'])) ?>"
                                        data-notes="<?= htmlspecialchars($ts['notes'] ?? '') ?>">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        แก้ไข
                                    </button>
                                    <button 
                                        class="btn-delete-time inline-flex items-center gap-1 px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-sm font-medium" 
                                        title="ลบ"
                                        data-id="<?= $ts['id'] ?>"
                                        data-date="<?= date('d/m/Y', strtotime($ts['date'])) ?>">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        ลบ
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Summary Card -->
    <?php if (!empty($time_sheets)): 
        $total_hours = 0;
        foreach ($time_sheets as $ts) {
            $time_in = strtotime($ts['time_in']);
            $time_out = strtotime($ts['time_out']);
            $total_hours += ($time_out - $time_in) / 3600;
        }
    ?>
    <div class="mt-6 bg-gradient-to-r from-purple-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <div class="text-sm text-purple-100">รวมชั่วโมงทำงานทั้งหมด</div>
                    <div class="text-3xl font-bold"><?= number_format($total_hours, 1) ?> ชั่วโมง</div>
                </div>
            </div>
            <div class="text-right">
                <div class="text-sm text-purple-100">จำนวนวันที่บันทึก</div>
                <div class="text-2xl font-bold"><?= count($time_sheets) ?> วัน</div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Add Time Sheet Modal -->
<div id="timeModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full z-50" onclick="if(event.target === this) this.classList.add('hidden')">
    <div class="relative top-20 mx-auto p-8 border w-full max-w-lg shadow-2xl rounded-2xl bg-white">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-slate-900">ลงเวลาปฏิบัติงาน</h3>
            <button onclick="document.getElementById('timeModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form action="index.php?page=student&action=process_time_sheet" method="POST">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">วันที่</label>
                    <input type="date" name="date" required class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="<?= date('Y-m-d') ?>">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                </svg>
                                เวลาเข้า
                            </span>
                        </label>
                        <input type="time" name="time_in" required class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                เวลาออก
                            </span>
                        </label>
                        <input type="time" name="time_out" required class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">หมายเหตุ (ถ้ามี)</label>
                    <textarea name="notes" rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none" placeholder="ระบุหมายเหตุเพิ่มเติม (ถ้ามี)"></textarea>
                </div>
            </div>
            <div class="mt-6 flex gap-3">
                <button type="button" onclick="document.getElementById('timeModal').classList.add('hidden')" class="flex-1 px-4 py-3 border-2 border-slate-200 text-slate-600 rounded-xl font-medium hover:bg-slate-50 transition-colors">
                    ยกเลิก
                </button>
                <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-3 rounded-xl font-medium hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/30">
                    บันทึก
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Time Sheet Modal -->
<div id="editTimeModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full z-50" onclick="if(event.target === this) this.classList.add('hidden')">
    <div class="relative top-20 mx-auto p-8 border w-full max-w-lg shadow-2xl rounded-2xl bg-white">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-slate-900">แก้ไขเวลาปฏิบัติงาน</h3>
            <button onclick="document.getElementById('editTimeModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form action="index.php?page=student&action=process_edit_time_sheet" method="POST">
            <input type="hidden" id="edit_time_id" name="time_id">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">วันที่</label>
                    <input type="date" id="edit_date" name="date" required class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                </svg>
                                เวลาเข้า
                            </span>
                        </label>
                        <input type="time" id="edit_time_in" name="time_in" required class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                เวลาออก
                            </span>
                        </label>
                        <input type="time" id="edit_time_out" name="time_out" required class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">หมายเหตุ (ถ้ามี)</label>
                    <textarea id="edit_notes" name="notes" rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none" placeholder="ระบุหมายเหตุเพิ่มเติม (ถ้ามี)"></textarea>
                </div>
            </div>
            <div class="mt-6 flex gap-3">
                <button type="button" onclick="document.getElementById('editTimeModal').classList.add('hidden')" class="flex-1 px-4 py-3 border-2 border-slate-200 text-slate-600 rounded-xl font-medium hover:bg-slate-50 transition-colors">
                    ยกเลิก
                </button>
                <button type="submit" class="flex-1 bg-amber-600 text-white px-4 py-3 rounded-xl font-medium hover:bg-amber-700 transition-all shadow-lg shadow-amber-500/30">
                    บันทึกการแก้ไข
                </button>
            </div>
        </form>
    </div>
</div>

<!-- jQuery and DataTables JS -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    $('#timeSheetTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/th.json'
        },
        order: [[0, 'desc']],
        pageLength: 10,
        responsive: true
    });
    
    // Event delegation for edit button
    $(document).on('click', '.btn-edit-time', function() {
        const btn = $(this);
        const data = {
            id: btn.data('id'),
            date: btn.data('date'),
            time_in: btn.data('time-in'),
            time_out: btn.data('time-out'),
            notes: btn.data('notes')
        };
        editTimeSheet(data);
    });
    
    // Event delegation for delete button
    $(document).on('click', '.btn-delete-time', function() {
        const btn = $(this);
        deleteTimeSheet(btn.data('id'), btn.data('date'));
    });
});

function editTimeSheet(ts) {
    // Populate edit form
    document.getElementById('edit_time_id').value = ts.id;
    document.getElementById('edit_date').value = ts.date;
    document.getElementById('edit_time_in').value = ts.time_in;
    document.getElementById('edit_time_out').value = ts.time_out;
    document.getElementById('edit_notes').value = ts.notes || '';
    
    // Show edit modal
    document.getElementById('editTimeModal').classList.remove('hidden');
}

function deleteTimeSheet(timeId, date) {
    if (confirm(`คุณต้องการลบบันทึกเวลาวันที่ ${date} ใช่หรือไม่?\n\nการลบจะไม่สามารถกู้คืนได้`)) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'index.php?page=student&action=process_delete_time_sheet';
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'time_id';
        input.value = timeId;
        
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<style>
/* DataTables Custom Styling */
#timeSheetTable_wrapper {
    padding: 0;
}

#timeSheetTable thead th {
    background: linear-gradient(to bottom, #f8fafc, #f1f5f9);
    padding: 1rem;
    font-weight: 600;
    color: #334155;
    border-bottom: 2px solid #e2e8f0;
}

#timeSheetTable tbody td {
    padding: 1rem;
    border-bottom: 1px solid #f1f5f9;
}

#timeSheetTable tbody tr:hover {
    background-color: #f8fafc;
}

.dataTables_filter input {
    border: 1px solid #e2e8f0;
    border-radius: 0.75rem;
    padding: 0.5rem 1rem;
    margin-left: 0.5rem;
}

.dataTables_length select {
    border: 1px solid #e2e8f0;
    border-radius: 0.75rem;
    padding: 0.5rem 1rem;
    margin: 0 0.5rem;
}
</style>
