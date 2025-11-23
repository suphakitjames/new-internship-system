<?php
// src/Admin/all_companies.php

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
            // Add New Company with Auto-Create User
            $name = trim($_POST['name']);
            $address = trim($_POST['address']);
            $province = trim($_POST['province']);
            $contact_person = trim($_POST['contact_person']);
            $phone = trim($_POST['phone']);
            $description = trim($_POST['description']);

            if (empty($name) || empty($province)) {
                $error = "กรุณากรอกชื่อหน่วยงานและจังหวัด";
            } else {
                try {
                    $pdo->beginTransaction();

                    // 1. Generate User Credentials
                    $username = 'comp_' . strtolower(substr(md5(uniqid()), 0, 5));
                    $raw_password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);
                    $hashed_password = password_hash($raw_password, PASSWORD_DEFAULT);

                    // 2. Create User
                    $stmtUser = $pdo->prepare("INSERT INTO users (username, password, role, full_name) VALUES (?, ?, 'employer', ?)");
                    $stmtUser->execute([$username, $hashed_password, $name]);
                    $user_id = $pdo->lastInsertId();

                    // 3. Create Company
                    $stmt = $pdo->prepare("INSERT INTO companies (name, address, province, contact_person, phone, description, status, created_by, user_id) VALUES (?, ?, ?, ?, ?, ?, 'approved', ?, ?)");
                    $stmt->execute([$name, $address, $province, $contact_person, $phone, $description, $_SESSION['user']['id'], $user_id]);

                    $pdo->commit();
                    $success = "เพิ่มสถานที่ฝึกงานเรียบร้อยแล้ว <br><strong>ชื่อผู้ใช้:</strong> $username <br><strong>รหัสผ่าน:</strong> $raw_password";
                } catch (Exception $e) {
                    $pdo->rollBack();
                    $error = "เกิดข้อผิดพลาด: " . $e->getMessage();
                }
            }
        } elseif ($action === 'edit') {
            // Edit Company
            $id = $_POST['company_id'];
            $name = trim($_POST['name']);
            $address = trim($_POST['address']);
            $province = trim($_POST['province']);
            $contact_person = trim($_POST['contact_person']);
            $phone = trim($_POST['phone']);
            $description = trim($_POST['description']);
            $status = $_POST['status'];

            try {
                $stmt = $pdo->prepare("UPDATE companies SET name = ?, address = ?, province = ?, contact_person = ?, phone = ?, description = ?, status = ? WHERE id = ?");
                $stmt->execute([$name, $address, $province, $contact_person, $phone, $description, $status, $id]);
                $success = "แก้ไขข้อมูลเรียบร้อยแล้ว";
            } catch (Exception $e) {
                $error = "เกิดข้อผิดพลาด: " . $e->getMessage();
            }
        } elseif ($action === 'delete') {
            // Delete Company
            $id = $_POST['company_id'];

            try {
                $stmt = $pdo->prepare("DELETE FROM companies WHERE id = ?");
                if ($stmt->execute([$id])) {
                    $success = "ลบข้อมูลเรียบร้อยแล้ว";
                } else {
                    $error = "เกิดข้อผิดพลาดในการลบข้อมูล";
                }
            } catch (Exception $e) {
                $error = "เกิดข้อผิดพลาด: " . $e->getMessage();
            }
        } elseif ($action === 'generate_user') {
            // Generate User for Existing Company
            $company_id = $_POST['company_id'];

            try {
                $pdo->beginTransaction();

                $stmt = $pdo->prepare("SELECT * FROM companies WHERE id = ?");
                $stmt->execute([$company_id]);
                $company = $stmt->fetch();

                if ($company && empty($company['user_id'])) {
                    $username = 'comp_' . strtolower(substr(md5(uniqid()), 0, 5));
                    $raw_password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);
                    $hashed_password = password_hash($raw_password, PASSWORD_DEFAULT);

                    $stmtUser = $pdo->prepare("INSERT INTO users (username, password, role, full_name) VALUES (?, ?, 'employer', ?)");
                    $stmtUser->execute([$username, $hashed_password, $company['name']]);
                    $user_id = $pdo->lastInsertId();

                    $stmtUpdate = $pdo->prepare("UPDATE companies SET user_id = ? WHERE id = ?");
                    $stmtUpdate->execute([$user_id, $company_id]);

                    $pdo->commit();
                    $success = "สร้างบัญชีผู้ใช้เรียบร้อยแล้ว <br><strong>ชื่อผู้ใช้:</strong> $username <br><strong>รหัสผ่าน:</strong> $raw_password";
                }
            } catch (Exception $e) {
                $pdo->rollBack();
                $error = "เกิดข้อผิดพลาด: " . $e->getMessage();
            }
        } elseif ($action === 'reset_password') {
            // Reset Password for Company User
            $company_id = $_POST['company_id'];

            try {
                $pdo->beginTransaction();

                // Get company and user info
                $stmt = $pdo->prepare("SELECT c.*, u.username FROM companies c JOIN users u ON c.user_id = u.id WHERE c.id = ?");
                $stmt->execute([$company_id]);
                $company = $stmt->fetch();

                if ($company) {
                    // Generate new password
                    $raw_password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);
                    $hashed_password = password_hash($raw_password, PASSWORD_DEFAULT);

                    // Update user password
                    $stmtUpdate = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                    $stmtUpdate->execute([$hashed_password, $company['user_id']]);

                    $pdo->commit();
                    $success = "รีเซ็ตรหัสผ่านเรียบร้อยแล้ว <br><strong>ชื่อผู้ใช้:</strong> {$company['username']} <br><strong>รหัสผ่านใหม่:</strong> $raw_password";
                } else {
                    throw new Exception("ไม่พบข้อมูลบริษัทหรือผู้ใช้");
                }
            } catch (Exception $e) {
                $pdo->rollBack();
                $error = "เกิดข้อผิดพลาด: " . $e->getMessage();
            }
        }
    }
}

// Fetch All Companies
// Join with provinces table if it exists, otherwise use province column directly
$sql = "SELECT c.*, p.name_th as province_name, u.username as employer_username 
        FROM companies c 
        LEFT JOIN provinces p ON c.province = p.id 
        LEFT JOIN users u ON c.user_id = u.id
        ORDER BY c.created_at DESC";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $companies = $stmt->fetchAll();
} catch (PDOException $e) {
    // Fallback if join fails
    $sql = "SELECT * FROM companies ORDER BY created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $companies = $stmt->fetchAll();
}

// Fetch Provinces for Dropdown
try {
    $stmt = $pdo->query("SELECT * FROM provinces ORDER BY name_th ASC");
    $provinces = $stmt->fetchAll();
} catch (PDOException $e) {
    $provinces = [];
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                จัดการสถานที่ฝึกงาน
            </h2>
            <p class="text-slate-500 text-sm mt-1">จัดการข้อมูลสถานประกอบการ เพิ่ม ลบ แก้ไข</p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto items-center">
            <!-- Province Filter -->
            <div class="relative w-full sm:w-64">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <select id="provinceFilter" class="block w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent appearance-none bg-white text-sm">
                    <option value="">ทุกจังหวัด</option>
                    <?php foreach ($provinces as $p): ?>
                        <option value="<?= htmlspecialchars($p['name_th']) ?>"><?= htmlspecialchars($p['name_th']) ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </div>

            <button onclick="openModal('add')" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2 transition-colors shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                เพิ่มสถานที่ฝึกงาน
            </button>
        </div>
    </div>

    <?php if ($success): ?>
        <div class="bg-green-50 text-green-700 p-4 rounded-lg mb-6 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <div><?= $success ?></div>
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
        <table id="companiesTable" class="w-full text-left border-collapse display responsive nowrap" style="width:100%">
            <thead>
                <tr class="bg-slate-50 text-slate-600 text-sm uppercase tracking-wider">
                    <th class="p-4 font-semibold border-b border-slate-200">แก้ไข | ลบ</th>
                    <th class="p-4 font-semibold border-b border-slate-200">ชื่อหน่วยงาน</th>
                    <th class="p-4 font-semibold border-b border-slate-200">ที่อยู่</th>
                    <th class="p-4 font-semibold border-b border-slate-200">จังหวัด</th>
                    <th class="p-4 font-semibold border-b border-slate-200">บัญชีผู้ใช้</th>
                    <th class="p-4 font-semibold border-b border-slate-200">สถานะ</th>
                </tr>
            </thead>
            <tbody class="text-slate-700 text-sm divide-y divide-slate-100">
                <?php foreach ($companies as $row): ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="p-4 flex gap-2">
                            <button onclick='openModal("edit", <?= json_encode($row) ?>)' class="p-1.5 bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200 transition-colors" title="แก้ไข">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button onclick="confirmDelete(<?= $row['id'] ?>, '<?= htmlspecialchars($row['name']) ?>')" class="p-1.5 bg-red-100 text-red-700 rounded hover:bg-red-200 transition-colors" title="ลบ">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                            <?php if (!empty($row['employer_username'])): ?>
                                <button onclick="resetPassword(<?= $row['id'] ?>, '<?= htmlspecialchars($row['name']) ?>')" class="p-1.5 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition-colors" title="รีเซ็ตรหัสผ่าน">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                    </svg>
                                </button>
                            <?php endif; ?>
                        </td>
                        <td class="p-4 font-medium text-slate-900"><?= htmlspecialchars($row['name']) ?></td>
                        <td class="p-4"><?= htmlspecialchars(mb_strimwidth($row['address'], 0, 50, "...")) ?></td>
                        <td class="p-4"><?= htmlspecialchars($row['province_name'] ?? $row['province']) ?></td>
                        <td class="p-4">
                            <?php if (!empty($row['employer_username'])): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <?= htmlspecialchars($row['employer_username']) ?>
                                </span>
                            <?php else: ?>
                                <button onclick="generateUser(<?= $row['id'] ?>, '<?= htmlspecialchars($row['name']) ?>')" class="text-xs bg-indigo-50 text-indigo-600 px-2 py-1 rounded border border-indigo-200 hover:bg-indigo-100 transition">
                                    สร้างบัญชี
                                </button>
                            <?php endif; ?>
                        </td>
                        <td class="p-4">
                            <?php
                            $statusClass = match ($row['status']) {
                                'approved' => 'bg-green-100 text-green-800',
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'rejected' => 'bg-red-100 text-red-800',
                                default => 'bg-gray-100 text-gray-800'
                            };
                            $statusText = match ($row['status']) {
                                'approved' => 'อนุมัติแล้ว',
                                'pending' => 'รออนุมัติ',
                                'rejected' => 'ไม่อนุมัติ',
                                default => $row['status']
                            };
                            ?>
                            <span class="px-2 py-1 rounded-full text-xs font-medium <?= $statusClass ?>">
                                <?= $statusText ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="companyModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl mx-4 overflow-hidden animate-fade-in-up max-h-[90vh] overflow-y-auto">
        <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex justify-between items-center">
            <h3 id="modalTitle" class="text-lg font-bold text-slate-800">เพิ่มสถานที่ฝึกงาน</h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form method="POST" class="p-6 space-y-4">
            <input type="hidden" name="action" id="formAction" value="add">
            <input type="hidden" name="company_id" id="companyId">

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">ชื่อหน่วยงาน/บริษัท</label>
                <input type="text" name="name" id="name" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">ที่อยู่</label>
                <textarea name="address" id="address" rows="3" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">จังหวัด</label>
                    <?php if (!empty($provinces)): ?>
                        <select name="province" id="province" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">เลือกจังหวัด</option>
                            <?php foreach ($provinces as $p): ?>
                                <option value="<?= $p['id'] ?>"><?= $p['name_th'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php else: ?>
                        <input type="text" name="province" id="provinceInput" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="ระบุจังหวัด">
                    <?php endif; ?>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">เบอร์โทรศัพท์</label>
                    <input type="text" name="phone" id="phone" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">ผู้ติดต่อ</label>
                <input type="text" name="contact_person" id="contactPerson" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">รายละเอียดเพิ่มเติม</label>
                <textarea name="description" id="description" rows="3" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
            </div>

            <div id="statusField" class="hidden">
                <label class="block text-sm font-medium text-slate-700 mb-1">สถานะ</label>
                <select name="status" id="status" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="pending">รออนุมัติ</option>
                    <option value="approved">อนุมัติแล้ว</option>
                    <option value="rejected">ไม่อนุมัติ</option>
                </select>
            </div>

            <div id="autoCreateHint" class="bg-blue-50 p-3 rounded-lg text-sm text-blue-700">
                <p class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    ระบบจะสร้างบัญชีผู้ใช้สำหรับบริษัทให้อัตโนมัติ
                </p>
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
    <input type="hidden" name="company_id" id="deleteCompanyId">
</form>

<!-- Generate User Form -->
<form id="generateUserForm" method="POST" class="hidden">
    <input type="hidden" name="action" value="generate_user">
    <input type="hidden" name="company_id" id="generateUserCompanyId">
</form>

<!-- Reset Password Form -->
<form id="resetPasswordForm" method="POST" class="hidden">
    <input type="hidden" name="action" value="reset_password">
    <input type="hidden" name="company_id" id="resetPasswordCompanyId">
</form>

<!-- jQuery and DataTables JS -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<script>
    $(document).ready(function() {
        var table = $('#companiesTable').DataTable({
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

        // Province Filter Logic
        $('#provinceFilter').on('change', function() {
            var province = $(this).val();
            // Column 3 is Province (0-indexed)
            table.column(3).search(province).draw();
        });
    });

    function openModal(mode, data = null) {
        const modal = document.getElementById('companyModal');
        const title = document.getElementById('modalTitle');
        const formAction = document.getElementById('formAction');
        const statusField = document.getElementById('statusField');
        const autoCreateHint = document.getElementById('autoCreateHint');

        // Reset form
        document.getElementById('companyId').value = '';
        document.getElementById('name').value = '';
        document.getElementById('address').value = '';
        document.getElementById('contactPerson').value = '';
        document.getElementById('phone').value = '';
        document.getElementById('description').value = '';

        const provinceSelect = document.getElementById('province');
        if (provinceSelect) provinceSelect.value = '';

        const provinceInput = document.getElementById('provinceInput');
        if (provinceInput) provinceInput.value = '';

        if (mode === 'edit' && data) {
            title.textContent = 'แก้ไขข้อมูลสถานที่ฝึกงาน';
            formAction.value = 'edit';
            statusField.classList.remove('hidden');
            autoCreateHint.classList.add('hidden');

            document.getElementById('companyId').value = data.id;
            document.getElementById('name').value = data.name;
            document.getElementById('address').value = data.address;
            document.getElementById('contactPerson').value = data.contact_person;
            document.getElementById('phone').value = data.phone;
            document.getElementById('description').value = data.description;
            document.getElementById('status').value = data.status;

            if (provinceSelect) provinceSelect.value = data.province;
            if (provinceInput) provinceInput.value = data.province;

        } else {
            title.textContent = 'เพิ่มสถานที่ฝึกงาน';
            formAction.value = 'add';
            statusField.classList.add('hidden');
            autoCreateHint.classList.remove('hidden');
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        const modal = document.getElementById('companyModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function confirmDelete(id, name) {
        if (confirm('คุณต้องการลบข้อมูลของ ' + name + ' ใช่หรือไม่?')) {
            document.getElementById('deleteCompanyId').value = id;
            document.getElementById('deleteForm').submit();
        }
    }

    function generateUser(id, name) {
        if (confirm('ยืนยันการสร้างบัญชีผู้ใช้สำหรับ ' + name + '?')) {
            document.getElementById('generateUserCompanyId').value = id;
            document.getElementById('generateUserForm').submit();
        }
    }

    function resetPassword(id, name) {
        if (confirm('คุณต้องการรีเซ็ตรหัสผ่านสำหรับ ' + name + ' ใช่หรือไม่?\nรหัสผ่านใหม่จะถูกแสดงให้เห็นทันที')) {
            document.getElementById('resetPasswordCompanyId').value = id;
            document.getElementById('resetPasswordForm').submit();
        }
    }

    // Close modal on outside click
    document.getElementById('companyModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
</script>