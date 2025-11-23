<?php
// src/Student/daily_log.php

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header('Location: index.php?page=auth&action=login');
    exit;
}

$user_id = $_SESSION['user']['id'];

// Fetch logs
$stmt = $pdo->prepare("SELECT * FROM daily_logs WHERE student_id = ? ORDER BY log_date DESC");
$stmt->execute([$user_id]);
$logs = $stmt->fetchAll();
?>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.tailwind.min.css">

<div class="max-w-7xl mx-auto">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">บันทึกการปฏิบัติงาน (Daily Log)</h1>
            <p class="text-slate-600 mt-1">บันทึกรายละเอียดงานที่ทำในแต่ละวัน</p>
        </div>
        <button onclick="document.getElementById('logModal').classList.remove('hidden')" class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-500/30 flex items-center gap-2 font-medium">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            เพิ่มบันทึกใหม่
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
            <table id="dailyLogTable" class="display w-full">
                <thead>
                    <tr>
                        <th class="text-left">วันที่</th>
                        <th class="text-left">รายละเอียดงาน</th>
                        <th class="text-left">ปัญหา/อุปสรรค</th>
                        <th class="text-center">การจัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td>
                                <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-lg font-medium text-sm">
                                    <?= date('d/m/Y', strtotime($log['log_date'])) ?>
                                </span>
                            </td>
                            <td>
                                <div class="text-slate-700 text-sm max-w-md">
                                    <?= nl2br(htmlspecialchars(substr($log['work_description'], 0, 100))) ?>
                                    <?= strlen($log['work_description']) > 100 ? '...' : '' ?>
                                </div>
                            </td>
                            <td>
                                <?php if (!empty($log['problems'])): ?>
                                    <div class="text-red-600 text-sm max-w-md">
                                        <?= nl2br(htmlspecialchars(substr($log['problems'], 0, 80))) ?>
                                        <?= strlen($log['problems']) > 80 ? '...' : '' ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-slate-400 text-sm">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button 
                                        class="btn-view-log inline-flex items-center gap-1 px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-sm font-medium" 
                                        title="ดูรายละเอียด"
                                        data-id="<?= $log['id'] ?>"
                                        data-date="<?= $log['log_date'] ?>"
                                        data-work="<?= htmlspecialchars($log['work_description']) ?>"
                                        data-problems="<?= htmlspecialchars($log['problems'] ?? '') ?>">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        ดู
                                    </button>
                                    <button 
                                        class="btn-edit-log inline-flex items-center gap-1 px-3 py-1.5 bg-amber-100 text-amber-700 rounded-lg hover:bg-amber-200 transition-colors text-sm font-medium" 
                                        title="แก้ไข"
                                        data-id="<?= $log['id'] ?>"
                                        data-date="<?= $log['log_date'] ?>"
                                        data-work="<?= htmlspecialchars($log['work_description']) ?>"
                                        data-problems="<?= htmlspecialchars($log['problems'] ?? '') ?>">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        แก้ไข
                                    </button>
                                    <button 
                                        class="btn-delete-log inline-flex items-center gap-1 px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-sm font-medium" 
                                        title="ลบ"
                                        data-id="<?= $log['id'] ?>"
                                        data-date="<?= date('d/m/Y', strtotime($log['log_date'])) ?>">
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
</div>

<!-- Add Log Modal -->
<div id="logModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full z-50" onclick="if(event.target === this) this.classList.add('hidden')">
    <div class="relative top-20 mx-auto p-8 border w-full max-w-lg shadow-2xl rounded-2xl bg-white">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-slate-900">เพิ่มบันทึกประจำวัน</h3>
            <button onclick="document.getElementById('logModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form action="index.php?page=student&action=process_add_log" method="POST">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">วันที่</label>
                    <input type="date" name="log_date" required class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="<?= date('Y-m-d') ?>">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">รายละเอียดงาน</label>
                    <textarea name="work_description" rows="5" required class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none" placeholder="อธิบายงานที่ทำในวันนี้..."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">ปัญหา/อุปสรรค (ถ้ามี)</label>
                    <textarea name="problems" rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none" placeholder="ระบุปัญหาหรืออุปสรรคที่พบ (ถ้ามี)"></textarea>
                </div>
            </div>
            <div class="mt-6 flex gap-3">
                <button type="button" onclick="document.getElementById('logModal').classList.add('hidden')" class="flex-1 px-4 py-3 border-2 border-slate-200 text-slate-600 rounded-xl font-medium hover:bg-slate-50 transition-colors">
                    ยกเลิก
                </button>
                <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-3 rounded-xl font-medium hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/30">
                    บันทึก
                </button>
            </div>
        </form>
    </div>
</div>

<!-- View Log Detail Modal -->
<div id="viewModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full z-50" onclick="if(event.target === this) this.classList.add('hidden')">
    <div class="relative top-20 mx-auto p-8 border w-full max-w-2xl shadow-2xl rounded-2xl bg-white">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-slate-900">รายละเอียดบันทึก</h3>
            <button onclick="document.getElementById('viewModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div id="viewModalContent" class="space-y-6">
            <!-- Content will be populated by JavaScript -->
        </div>
    </div>
</div>

<!-- Edit Log Modal -->
<div id="editModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full z-50" onclick="if(event.target === this) this.classList.add('hidden')">
    <div class="relative top-20 mx-auto p-8 border w-full max-w-lg shadow-2xl rounded-2xl bg-white">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-slate-900">แก้ไขบันทึกประจำวัน</h3>
            <button onclick="document.getElementById('editModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form action="index.php?page=student&action=process_edit_log" method="POST">
            <input type="hidden" id="edit_log_id" name="log_id">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">วันที่</label>
                    <input type="date" id="edit_log_date" name="log_date" required class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">รายละเอียดงาน</label>
                    <textarea id="edit_work_description" name="work_description" rows="5" required class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none" placeholder="อธิบายงานที่ทำในวันนี้..."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">ปัญหา/อุปสรรค (ถ้ามี)</label>
                    <textarea id="edit_problems" name="problems" rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none" placeholder="ระบุปัญหาหรืออุปสรรคที่พบ (ถ้ามี)"></textarea>
                </div>
            </div>
            <div class="mt-6 flex gap-3">
                <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="flex-1 px-4 py-3 border-2 border-slate-200 text-slate-600 rounded-xl font-medium hover:bg-slate-50 transition-colors">
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
    $('#dailyLogTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/th.json'
        },
        order: [[0, 'desc']],
        pageLength: 10,
        responsive: true
    });
    
    // Event delegation for view button
    $(document).on('click', '.btn-view-log', function() {
        const btn = $(this);
        const data = {
            id: btn.data('id'),
            log_date: btn.data('date'),
            work_description: btn.data('work'),
            problems: btn.data('problems')
        };
        viewLog(data);
    });
    
    // Event delegation for edit button
    $(document).on('click', '.btn-edit-log', function() {
        const btn = $(this);
        const data = {
            id: btn.data('id'),
            log_date: btn.data('date'),
            work_description: btn.data('work'),
            problems: btn.data('problems')
        };
        editLog(data);
    });
    
    // Event delegation for delete button
    $(document).on('click', '.btn-delete-log', function() {
        const btn = $(this);
        deleteLog(btn.data('id'), btn.data('date'));
    });
});

function viewLog(log) {
    const content = `
        <div class="space-y-4">
            <div class="flex items-center gap-3 pb-4 border-b border-slate-200">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <div class="text-sm text-slate-500">วันที่</div>
                    <div class="text-lg font-bold text-slate-900">${new Date(log.log_date).toLocaleDateString('th-TH', {year: 'numeric', month: 'long', day: 'numeric'})}</div>
                </div>
            </div>
            
            <div>
                <h4 class="text-sm font-bold text-slate-700 mb-2 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    รายละเอียดงานที่ปฏิบัติ
                </h4>
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                    <p class="text-slate-700 leading-relaxed whitespace-pre-line">${log.work_description}</p>
                </div>
            </div>
            
            ${log.problems ? `
                <div>
                    <h4 class="text-sm font-bold text-red-700 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        ปัญหา/อุปสรรค
                    </h4>
                    <div class="bg-red-50 p-4 rounded-xl border border-red-200">
                        <p class="text-red-700 leading-relaxed whitespace-pre-line">${log.problems}</p>
                    </div>
                </div>
            ` : ''}
        </div>
    `;
    
    document.getElementById('viewModalContent').innerHTML = content;
    document.getElementById('viewModal').classList.remove('hidden');
}

function editLog(log) {
    // Populate edit form
    document.getElementById('edit_log_id').value = log.id;
    document.getElementById('edit_log_date').value = log.log_date;
    document.getElementById('edit_work_description').value = log.work_description;
    document.getElementById('edit_problems').value = log.problems || '';
    
    // Show edit modal
    document.getElementById('editModal').classList.remove('hidden');
}

function deleteLog(logId, logDate) {
    if (confirm(`คุณต้องการลบบันทึกวันที่ ${logDate} ใช่หรือไม่?\n\nการลบจะไม่สามารถกู้คืนได้`)) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'index.php?page=student&action=process_delete_log';
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'log_id';
        input.value = logId;
        
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<style>
/* DataTables Custom Styling */
#dailyLogTable_wrapper {
    padding: 0;
}

#dailyLogTable thead th {
    background: linear-gradient(to bottom, #f8fafc, #f1f5f9);
    padding: 1rem;
    font-weight: 600;
    color: #334155;
    border-bottom: 2px solid #e2e8f0;
}

#dailyLogTable tbody td {
    padding: 1rem;
    border-bottom: 1px solid #f1f5f9;
}

#dailyLogTable tbody tr:hover {
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
