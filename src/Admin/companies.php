<?php
// src/Admin/companies.php

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php?page=auth&action=login');
    exit;
}

// Fetch Provinces for Dropdown
$stmt = $pdo->query("SELECT * FROM provinces ORDER BY name_th ASC");
$provinces = $stmt->fetchAll();

// Fetch all companies (pending and approved) with linked user info
$stmt = $pdo->query("
    SELECT 
        c.*, 
        u.full_name as created_by_name,
        p.name_th as province_name,
        emp_user.username as employer_username
    FROM companies c
    LEFT JOIN users u ON c.created_by = u.id
    LEFT JOIN provinces p ON c.province = p.id
    LEFT JOIN users emp_user ON c.user_id = emp_user.id
    ORDER BY 
        CASE 
            WHEN c.status = 'pending' THEN 1
            WHEN c.status = 'approved' THEN 2
            ELSE 3
        END,
        c.created_at DESC
");
$companies = $stmt->fetchAll();
?>
<div class="max-w-7xl mx-auto">

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
            <span class="block sm:inline"><?= $_SESSION['success_message'] ?></span>
            <?php unset($_SESSION['success_message']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
            <span class="block sm:inline"><?= $_SESSION['error_message'] ?></span>
            <?php unset($_SESSION['error_message']); ?>
        </div>
    <?php endif; ?>

    <div class="mb-8 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">จัดการสถานที่ฝึกงาน</h1>
        <div class="flex gap-3">
            <button onclick="openAddCompanyModal()" class="bg-green-600 text-white px-6 py-2.5 rounded-xl hover:bg-green-700 transition shadow-lg shadow-green-500/30 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                เพิ่มบริษัทใหม่
            </button>
            <a href="index.php?page=admin&action=dashboard" class="text-blue-600 hover:text-blue-800 px-4 py-2.5 flex items-center">
                กลับหน้า Dashboard
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
            <table id="companiesTable" class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4">ชื่อบริษัท</th>
                        <th class="text-left py-3 px-4">จังหวัด</th>
                        <th class="text-left py-3 px-4">ผู้ติดต่อ</th>
                        <th class="text-left py-3 px-4">บัญชีผู้ใช้</th>
                        <th class="text-left py-3 px-4">สถานะ</th>
                        <th class="text-center py-3 px-4">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($companies as $comp): ?>
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="py-3 px-4">
                                <div class="font-medium text-gray-900"><?= htmlspecialchars($comp['name']) ?></div>
                                <div class="text-xs text-gray-500 mt-1"><?= htmlspecialchars(substr($comp['address'], 0, 50)) ?>...</div>
                            </td>
                            <td class="py-3 px-4 text-gray-600"><?= htmlspecialchars($comp['province_name'] ?? '-') ?></td>
                            <td class="py-3 px-4 text-gray-600">
                                <div><?= htmlspecialchars($comp['contact_person'] ?? '-') ?></div>
                                <div class="text-xs text-gray-400"><?= htmlspecialchars($comp['phone'] ?? '-') ?></div>
                            </td>
                            <td class="py-3 px-4">
                                <?php if (!empty($comp['employer_username'])): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <?= htmlspecialchars($comp['employer_username']) ?>
                                    </span>
                                <?php else: ?>
                                    <form action="index.php?page=admin&action=generate_company_user" method="POST" class="inline" onsubmit="return confirm('ยืนยันการสร้างบัญชีผู้ใช้สำหรับบริษัทนี้?');">
                                        <input type="hidden" name="company_id" value="<?= $comp['id'] ?>">
                                        <button type="submit" class="text-xs bg-indigo-50 text-indigo-600 px-2 py-1 rounded border border-indigo-200 hover:bg-indigo-100 transition">
                                            สร้างบัญชี
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-4">
                                <?php
                                $statusClass = match ($comp['status']) {
                                    'approved' => 'bg-green-100 text-green-800',
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                                $statusText = match ($comp['status']) {
                                    'approved' => 'อนุมัติแล้ว',
                                    'pending' => 'รออนุมัติ',
                                    'rejected' => 'ไม่อนุมัติ',
                                    default => $comp['status']
                                };
                                ?>
                                <span class="px-3 py-1 rounded-full text-xs font-medium <?= $statusClass ?>">
                                    <?= $statusText ?>
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex justify-center gap-2">
                                    <?php if ($comp['status'] === 'pending'): ?>
                                        <form action="index.php?page=admin&action=process_company_approval" method="POST" class="inline">
                                            <input type="hidden" name="company_id" value="<?= $comp['id'] ?>">
                                            <button type="submit" name="status" value="approved" class="bg-green-600 text-white px-3 py-1 rounded-lg hover:bg-green-700 transition text-sm">
                                                อนุมัติ
                                            </button>
                                        </form>
                                        <form action="index.php?page=admin&action=process_company_approval" method="POST" class="inline">
                                            <input type="hidden" name="company_id" value="<?= $comp['id'] ?>">
                                            <button type="submit" name="status" value="rejected" class="bg-red-50 text-red-600 px-3 py-1 rounded-lg hover:bg-red-100 transition text-sm border border-red-200" onclick="return confirm('ยืนยันการไม่อนุมัติ?')">
                                                ไม่อนุมัติ
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <button onclick="viewCompanyDetails(<?= $comp['id'] ?>)" class="text-blue-600 hover:text-blue-800 text-sm">
                                            ดูรายละเอียด
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Company Modal -->
<div id="addCompanyModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-900">เพิ่มบริษัทใหม่</h2>
                <button onclick="closeAddCompanyModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        <form action="index.php?page=admin&action=add_company" method="POST" class="p-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">ชื่อบริษัท *</label>
                    <input type="text" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">ที่อยู่ *</label>
                    <textarea name="address" required rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">จังหวัด *</label>
                        <select name="province" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">เลือกจังหวัด</option>
                            <?php foreach ($provinces as $p): ?>
                                <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['name_th']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">เบอร์โทร</label>
                        <input type="text" name="phone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">ผู้ติดต่อ</label>
                    <input type="text" name="contact_person" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">คำอธิบาย</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                </div>

                <div class="bg-blue-50 p-4 rounded-lg">
                    <p class="text-sm text-blue-800 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        ระบบจะสร้างบัญชีผู้ใช้สำหรับบริษัทให้อัตโนมัติ
                    </p>
                </div>
            </div>
            <div class="mt-6 flex gap-3">
                <button type="submit" class="flex-1 bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition font-medium">
                    บันทึก
                </button>
                <button type="button" onclick="closeAddCompanyModal()" class="flex-1 bg-gray-100 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-200 transition font-medium">
                    ยกเลิก
                </button>
            </div>
        </form>
    </div>
</div>

<!-- DataTables CSS & JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#companiesTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/th.json'
            },
            order: [
                [5, 'asc']
            ], // Sort by status (pending first)
            pageLength: 25
        });
    });

    function openAddCompanyModal() {
        document.getElementById('addCompanyModal').classList.remove('hidden');
    }

    function closeAddCompanyModal() {
        document.getElementById('addCompanyModal').classList.add('hidden');
    }

    function viewCompanyDetails(id) {
        // TODO: Implement view details modal
        alert('ดูรายละเอียดบริษัท ID: ' + id);
    }
</script>