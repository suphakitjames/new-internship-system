<?php
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php?page=auth&action=admin_login');
    exit;
}

// Handle form submission for updating statuses
if (isset($_POST['confirmA'])) {
    try {
        // Update faculty approval status
        if (isset($_POST['Tstatus'])) {
            foreach ($_POST['Tstatus'] as $tid) {
                $t_id = explode(",", $tid);
                $stmt = $pdo->prepare("UPDATE internship_requests SET faculty_approval_status = ?, faculty_approval_date = NOW() WHERE id = ?");
                $stmt->execute([$t_id[1], $t_id[0]]);
            }
        }

        // Update company response status
        if (isset($_POST['TstatusCompany'])) {
            foreach ($_POST['TstatusCompany'] as $tcid) {
                $tc_id = explode(",", $tcid);
                $stmt = $pdo->prepare("UPDATE internship_requests SET company_response_status = ?, company_response_date = NOW() WHERE id = ?");
                $stmt->execute([$tc_id[1], $tc_id[0]]);
            }
        }

        // Update document response status
        if (isset($_POST['TstatusDocument'])) {
            foreach ($_POST['TstatusDocument'] as $tdid) {
                $td_id = explode(",", $tdid);
                $stmt = $pdo->prepare("UPDATE internship_requests SET document_response_status = ?, document_response_date = NOW() WHERE id = ?");
                $stmt->execute([$td_id[1], $td_id[0]]);
            }
        }

        // ตรวจสอบและอัพเดทสถานะหลัก ถ้าผ่านครบทั้ง 3 ขั้นตอน
        // ดึงรายการทั้งหมดที่มีการอัพเดทมาตรวจสอบ
        $updated_ids = [];
        if (isset($_POST['Tstatus'])) {
            foreach ($_POST['Tstatus'] as $tid) {
                $t_id = explode(",", $tid);
                $updated_ids[] = $t_id[0];
            }
        }
        if (isset($_POST['TstatusCompany'])) {
            foreach ($_POST['TstatusCompany'] as $tcid) {
                $tc_id = explode(",", $tcid);
                if (!in_array($tc_id[0], $updated_ids)) {
                    $updated_ids[] = $tc_id[0];
                }
            }
        }
        if (isset($_POST['TstatusDocument'])) {
            foreach ($_POST['TstatusDocument'] as $tdid) {
                $td_id = explode(",", $tdid);
                if (!in_array($td_id[0], $updated_ids)) {
                    $updated_ids[] = $td_id[0];
                }
            }
        }

        // ตรวจสอบแต่ละรายการว่าผ่านครบทั้ง 3 ขั้นตอนหรือไม่
        foreach ($updated_ids as $request_id) {
            $check_stmt = $pdo->prepare("
                SELECT faculty_approval_status, company_response_status, document_response_status 
                FROM internship_requests 
                WHERE id = ?
            ");
            $check_stmt->execute([$request_id]);
            $request = $check_stmt->fetch(PDO::FETCH_ASSOC);

            if ($request) {
                // ถ้าผ่านครบทั้ง 3 ขั้นตอน ให้เปลี่ยนสถานะหลักเป็น approved
                if ($request['faculty_approval_status'] === 'approved' 
                    && $request['company_response_status'] === 'accepted' 
                    && $request['document_response_status'] === 'approved') {
                    
                    $update_main_status = $pdo->prepare("UPDATE internship_requests SET status = 'approved' WHERE id = ?");
                    $update_main_status->execute([$request_id]);
                }
                // ถ้ามีขั้นตอนใดขั้นตอนหนึ่งถูกปฏิเสธ ให้เปลี่ยนสถานะหลักเป็น rejected
                elseif ($request['faculty_approval_status'] === 'rejected' 
                    || $request['company_response_status'] === 'rejected' 
                    || $request['document_response_status'] === 'rejected') {
                    
                    $update_main_status = $pdo->prepare("UPDATE internship_requests SET status = 'rejected' WHERE id = ?");
                    $update_main_status->execute([$request_id]);
                }
            }
        }

        echo "<script>alert('บันทึกสำเร็จ'); window.location='index.php?page=admin&action=internship_requests';</script>";
        exit;
    } catch (PDOException $e) {
        echo "<script>alert('เกิดข้อผิดพลาด: " . addslashes($e->getMessage()) . "');</script>";
    }
}

// Handle delete request
if (isset($_GET['act']) && $_GET['act'] == 'del' && isset($_GET['tid'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM internship_requests WHERE id = ?");
        $stmt->execute([$_GET['tid']]);
        echo "<script>alert('ลบข้อมูลสำเร็จ'); window.location='index.php?page=admin&action=internship_requests';</script>";
        exit;
    } catch (PDOException $e) {
        echo "<script>alert('เกิดข้อผิดพลาดในการลบข้อมูล');</script>";
    }
}


// Fetch internship rounds for filter (with error handling)
$rounds = [];
try {
    $rounds_stmt = $pdo->query("SELECT * FROM internship_rounds ORDER BY start_date DESC");
    $rounds = $rounds_stmt->fetchAll();
} catch (PDOException $e) {
    // Table doesn't exist yet, continue without rounds
    $rounds = [];
}

// Get filter parameters
$round_filter = $_GET['round'] ?? '';
$province_filter = $_GET['province'] ?? '';
$search = $_GET['search'] ?? '';

// Fetch provinces that have companies with internship requests
$provinces = [];
try {
    $provinces_stmt = $pdo->query("
        SELECT DISTINCT p.name 
        FROM internship_requests ir
        JOIN companies c ON ir.company_id = c.id
        JOIN provinces p ON c.province = p.id
        WHERE p.name IS NOT NULL AND p.name != ''
        ORDER BY p.name ASC
    ");
    $provinces = $provinces_stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    $provinces = [];
}

// Build query
$query = "
    SELECT 
        ir.id,
        ir.student_id,
        ir.company_id,
        ir.round_id,
        ir.status,
        ir.request_date,
        ir.start_date,
        ir.end_date,
        ir.admin_comment,
        ir.faculty_approval_status,
        ir.faculty_approval_date,
        ir.faculty_comment,
        ir.company_response_status,
        ir.company_response_date,
        ir.company_response_comment,
        ir.document_response_status,
        ir.document_response_date,
        COALESCE(u.full_name, 'ไม่ระบุ') as full_name,
        COALESCE(u.email, '') as email,
        COALESCE(u.phone, '') as phone,
        COALESCE(s.student_id, ir.student_id) as student_code,
        COALESCE(s.year_level, '') as year_level,
        COALESCE(s.gpa, 0) as gpa,
        COALESCE(c.name, 'ไม่ระบุ') as company_name,
        COALESCE(c.province, '') as province_name,
        'ไม่ระบุ' as major
    FROM internship_requests ir
    LEFT JOIN students s ON ir.student_id = s.user_id
    LEFT JOIN users u ON s.user_id = u.id
    LEFT JOIN companies c ON ir.company_id = c.id
    WHERE 1=1
";

$params = [];

// ไม่แสดงรายการที่อนุมัติครบแล้ว
$query .= " AND ir.status != 'approved'";

if ($round_filter) {
    $query .= " AND ir.round_id = ?";
    $params[] = $round_filter;
}


if ($province_filter) {
    $query .= " AND c.province = ?";
    $params[] = $province_filter;
}


if ($search) {
    $query .= " AND (s.student_id LIKE ? OR u.full_name LIKE ? OR c.name LIKE ?)";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
}

$query .= " ORDER BY ir.request_date DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$requests = $stmt->fetchAll();

// Count by status
$pending_count = 0;
$approved_count = 0;
$rejected_count = 0;

foreach ($requests as $req) {
    if ($req['status'] === 'pending') $pending_count++;
    elseif ($req['status'] === 'approved') $approved_count++;
    elseif ($req['status'] === 'rejected') $rejected_count++;
}
?>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.tailwind.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

<div class="max-w-full">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">อนุมัติคำขอฝึกงาน</h1>
                <p class="text-slate-600 mt-1">จัดการและอนุมัติคำขอฝึกงานของนิสิต</p>
            </div>
            <a href="index.php?page=admin&action=dashboard" class="inline-flex items-center gap-2 px-4 py-2 text-slate-600 hover:text-blue-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                กลับไป Dashboard
            </a>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
            <?= htmlspecialchars($_SESSION['success']) ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 mb-1">ทั้งหมด</p>
                    <p class="text-3xl font-bold text-slate-900"><?= count($requests) ?></p>
                </div>
                <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 mb-1">รอพิจารณา</p>
                    <p class="text-3xl font-bold text-amber-600"><?= $pending_count ?></p>
                </div>
                <div class="w-14 h-14 bg-amber-100 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 mb-1">อนุมัติแล้ว</p>
                    <p class="text-3xl font-bold text-green-600"><?= $approved_count ?></p>
                </div>
                <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 mb-1">ไม่อนุมัติ</p>
                    <p class="text-3xl font-bold text-red-600"><?= $rejected_count ?></p>
                </div>
                <div class="w-14 h-14 bg-red-100 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
        <form method="GET" action="index.php" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="hidden" name="page" value="admin">
            <input type="hidden" name="action" value="internship_requests">
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">รอบการฝึกงาน</label>
                <select name="round" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">ทั้งหมด</option>
                    <?php foreach ($rounds as $round): ?>
                        <option value="<?= $round['id'] ?>" <?= $round_filter == $round['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($round['round_name']) ?> (<?= $round['year'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">จังหวัด</label>
                <select name="province" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">ทั้งหมด</option>
                    <?php foreach ($provinces as $province): ?>
                        <option value="<?= htmlspecialchars($province) ?>" <?= $province_filter === $province ? 'selected' : '' ?>>
                            <?= htmlspecialchars($province) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">ค้นหา</label>
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="รหัสนิสิต, ชื่อ, บริษัท..." class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-xl font-medium hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/30">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    ค้นหา
                </button>
                <a href="index.php?page=admin&action=internship_requests" class="px-4 py-3 border-2 border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </a>
            </div>
        </form>
    </div>

    <!-- DataTable with Form -->
    <form method="post" action="">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6">
            <table id="requestsTable" class="display w-full" style="font-size:14px">
                <thead>
                    <tr>
                        <th width="11%" style="background-color:#FFE1E1">สถานะ</th>
                        <th width="16%">รหัสนิสิต</th>
                        <th width="25%">ชื่อ-สกุล</th>
                        <th width="13%">เกรดเฉลี่ย</th>
                        <th width="21%">สาขา</th>
                        <th width="14%">พิมพ์ | ลบ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($requests as $req): ?>
                        <tr>
                            <td bgcolor="#FFECEC" style="font-size:12px">
                                <!-- ผลการพิจารณาของคณะฯ -->
                                ผลการพิจารณาของคณะฯ<br>
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-xs btn-default <?= ($req['faculty_approval_status'] === 'pending' || !$req['faculty_approval_status']) ? 'active' : '' ?>">
                                        <input type="radio" name="Tstatus[<?= $req['id'] ?>]" value="<?= $req['id'] ?>,pending" <?= ($req['faculty_approval_status'] === 'pending' || !$req['faculty_approval_status']) ? 'checked' : '' ?>> รออนุมัติ
                                    </label>
                                    <label class="btn btn-xs btn-default <?= $req['faculty_approval_status'] === 'approved' ? 'active' : '' ?>">
                                        <input type="radio" name="Tstatus[<?= $req['id'] ?>]" value="<?= $req['id'] ?>,approved" <?= $req['faculty_approval_status'] === 'approved' ? 'checked' : '' ?>> &nbsp;อนุมัติ&nbsp;
                                    </label>
                                    <label class="btn btn-xs btn-default <?= $req['faculty_approval_status'] === 'rejected' ? 'active' : '' ?>">
                                        <input type="radio" name="Tstatus[<?= $req['id'] ?>]" value="<?= $req['id'] ?>,rejected" <?= $req['faculty_approval_status'] === 'rejected' ? 'checked' : '' ?>> ไม่อนุมัติ
                                    </label>
                                </div>
                                
                                <br><br>
                                ผลการตอบรับของหน่วยงาน<br>
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-xs btn-default <?= $req['company_response_status'] === 'accepted' ? 'active' : '' ?>">
                                        <input type="radio" name="TstatusCompany[<?= $req['id'] ?>]" value="<?= $req['id'] ?>,accepted" <?= $req['company_response_status'] === 'accepted' ? 'checked' : '' ?>> &nbsp; &nbsp; รับ &nbsp; &nbsp;
                                    </label>
                                    <label class="btn btn-xs btn-default <?= $req['company_response_status'] === 'rejected' ? 'active' : '' ?>">
                                        <input type="radio" name="TstatusCompany[<?= $req['id'] ?>]" value="<?= $req['id'] ?>,rejected" <?= $req['company_response_status'] === 'rejected' ? 'checked' : '' ?>> &nbsp; ปฏิเสธ &nbsp;
                                    </label>
                                </div>
                                
                                <br><br>
                                ผลการตรวจเอกสาร<br>
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-xs btn-default <?= $req['document_response_status'] === 'approved' ? 'active' : '' ?>">
                                        <input type="radio" name="TstatusDocument[<?= $req['id'] ?>]" value="<?= $req['id'] ?>,approved" <?= $req['document_response_status'] === 'approved' ? 'checked' : '' ?>> &nbsp; &nbsp; ผ่าน &nbsp; &nbsp;
                                    </label>
                                    <label class="btn btn-xs btn-default <?= $req['document_response_status'] === 'rejected' ? 'active' : '' ?>">
                                        <input type="radio" name="TstatusDocument[<?= $req['id'] ?>]" value="<?= $req['id'] ?>,rejected" <?= $req['document_response_status'] === 'rejected' ? 'checked' : '' ?>> &nbsp; ไม่ผ่าน &nbsp;
                                    </label>
                                </div>
                                <br>
                                <button type="button" onclick="openReplyModal(<?= $req['id'] ?>)" class="btn btn-success btn-sm">
                                    <i class="glyphicon glyphicon-envelope"></i> ตอบกลับเอกสาร
                                </button>
                            </td>
                            <td align="center"><?= htmlspecialchars($req['student_code']) ?></td>
                            <td><?= htmlspecialchars($req['full_name']) ?></td>
                            <td><?= htmlspecialchars($req['gpa'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($req['major']) ?></td>
                            <td align="center">
                                <button type="button" class="btn btn-primary btn-xs" onclick="openPrintModal(<?php echo $req['id']; ?>, <?php echo $req['company_id']; ?>, <?php echo isset($req['round_id']) ? $req['round_id'] : 0; ?>)" title="พิมพ์หนังสือขอความอนุเคราะห์ฯ" style="margin-right: 5px;">
                                    🖨️ พิมพ์
                                </button>
                                <a href="index.php?page=admin&action=internship_requests&act=del&tid=<?= $req['id'] ?>" class="btn btn-danger btn-xs" onclick="return confirm('ยืนยันการลบ?');" title="ลบรายการนี้">
                                    🗑️ ลบ
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <!-- ปุ่มบันทึก -->
            <div class="mt-4">
                <input type="submit" name="confirmA" value=" บันทึก " class="btn btn-danger" style="width: 200px; padding: 10px;">
            </div>
        </div>
    </div>
    </form>
</div>

<!-- View Modal with Action Buttons -->
<div id="viewModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full z-50" onclick="if(event.target === this) closeModal()">
    <div class="relative top-10 mx-auto p-8 border w-full max-w-6xl shadow-2xl rounded-2xl bg-white mb-10">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-slate-900">รายละเอียดคำขอฝึกงาน</h3>
            <div class="flex gap-2">
                <button onclick="printStudentDocument()" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    ปริ้นเอกสาร
                </button>
                <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Content -->
        <div id="modalContent" class="space-y-6">
            <!-- ข้อมูลนิสิต -->
            <div class="bg-gradient-to-br from-slate-50 to-blue-50 p-6 rounded-2xl border border-slate-200">
                <h4 class="font-bold text-slate-900 text-lg mb-4">ข้อมูลนิสิต</h4>
                <div id="studentInfo" class="grid grid-cols-2 gap-4">
                    <!-- Content will be populated by JavaScript -->
                </div>
            </div>

            <!-- ข้อมูลบริษัท -->
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-6 rounded-2xl border border-green-200">
                <h4 class="font-bold text-green-900 mb-4">ข้อมูลสถานที่ฝึกงาน</h4>
                <div id="companyInfo">
                    <!-- Content will be populated by JavaScript -->
                </div>
            </div>

            <!-- การจัดการสถานะ (Admin Only) -->
            <div class="bg-gradient-to-br from-purple-50 to-pink-50 p-6 rounded-2xl border border-purple-200">
                <h4 class="font-bold text-purple-900 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    การจัดการสถานะ
                </h4>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- ผลการพิจารณาของคณะ -->
                    <div class="bg-white p-4 rounded-xl border-2 border-blue-200">
                        <div class="flex items-center mb-3">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <h5 class="font-semibold text-blue-900">ผลการพิจารณาของคณะ</h5>
                        </div>
                        <div id="facultyStatus" class="mb-3">
                            <!-- Status badge -->
                        </div>
                        <div class="flex gap-2">
                            <button onclick="updateFacultyStatus('approved')" class="flex-1 px-3 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-all">
                                อนุมัติ
                            </button>
                            <button onclick="updateFacultyStatus('rejected')" class="flex-1 px-3 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition-all">
                                ไม่อนุมัติ
                            </button>
                        </div>
                    </div>

                    <!-- ผลการตอบรับจากหน่วยงาน -->
                    <div class="bg-white p-4 rounded-xl border-2 border-green-200">
                        <div class="flex items-center mb-3">
                            <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <h5 class="font-semibold text-green-900">ผลการตอบรับจากหน่วยงาน</h5>
                        </div>
                        <div id="companyStatus" class="mb-3">
                            <!-- Status badge -->
                        </div>
                        <div class="flex gap-2">
                            <button onclick="updateCompanyStatus('accepted')" class="flex-1 px-3 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-all">
                                ตอบรับ
                            </button>
                            <button onclick="updateCompanyStatus('rejected')" class="flex-1 px-3 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition-all">
                                ปฏิเสธ
                            </button>
                        </div>
                    </div>

                    <!-- ผลการตอบกลับเอกสาร -->
                    <div class="bg-white p-4 rounded-xl border-2 border-purple-200">
                        <div class="flex items-center mb-3">
                            <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h5 class="font-semibold text-purple-900">ผลการตอบกลับเอกสาร</h5>
                        </div>
                        <div id="documentStatus" class="mb-3">
                            <!-- Status badge -->
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <button onclick="updateDocumentStatus('submitted')" class="px-3 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-all">
                                ส่งแล้ว
                            </button>
                            <button onclick="updateDocumentStatus('approved')" class="px-3 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-all">
                                อนุมัติ
                            </button>
                            <button onclick="updateDocumentStatus('rejected')" class="px-3 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition-all col-span-2">
                                ไม่อนุมัติ
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- สถานะคำขอหลัก -->
            <div class="bg-gradient-to-br from-amber-50 to-orange-50 p-6 rounded-2xl border border-amber-200">
                <h4 class="font-bold text-amber-900 mb-4">สถานะคำขอหลัก</h4>
                <div id="mainStatus" class="text-center">
                    <!-- Main status badge -->
                </div>
                <div id="approvalButtons" class="mt-4 flex gap-3">
                    <!-- Approval buttons will be shown if status is pending -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery and DataTables JS -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

<!-- DataTables Initialization -->
<script>
$(document).ready(function() {
    $('#requestsTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/th.json"
        },
        "order": [[0, "desc"]],
        "pageLength": 25
    });
});
</script>

<!-- Custom JavaScript for admin requests -->
<script src="/test_internship/assets/js/admin_requests.js"></script>

<!-- Test JavaScript -->
<script src="/test_internship/assets/js/test_admin.js"></script>

<style>
/* DataTables Custom Styling */
#requestsTable_wrapper {
    padding: 0;
}

#requestsTable thead th {
    background: linear-gradient(to bottom, #f8fafc, #f1f5f9);
    padding: 1rem;
    font-weight: 600;
    color: #334155;
    border-bottom: 2px solid #e2e8f0;
}

#requestsTable tbody td {
    padding: 1rem;
    border-bottom: 1px solid #f1f5f9;
}

#requestsTable tbody tr:hover {
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

.dt-buttons {
    margin-bottom: 1rem;
}

.dt-button {
    margin-right: 0.5rem !important;
}

/* Tab Styling */
.tab-button {
    transition: all 0.3s ease;
}

.tab-button:hover {
    background-color: #f1f5f9;
}

.tab-button.active {
    font-weight: 600;
}

.tab-content {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Print Styles */
@media print {
    body * {
        visibility: hidden;
    }
    #printArea, #printArea * {
        visibility: visible;
    }
    #printArea {
        position: absolute;
        left: 0;
        top: 0;
    }
}
</style>

<script>
// Function to open reply modal
function openReplyModal(requestId) {
    // Check if statuses are selected
    const facultyStatus = document.querySelector(`input[name="Tstatus[${requestId}]"]:checked`);
    const companyStatus = document.querySelector(`input[name="TstatusCompany[${requestId}]"]:checked`);
    const documentStatus = document.querySelector(`input[name="TstatusDocument[${requestId}]"]:checked`);
    
    if (!facultyStatus || !companyStatus || !documentStatus) {
        alert('กรุณาเลือกสถานะทั้ง 3 ประเภทก่อนตอบกลับ');
        return;
    }

    // Set values to modal
    document.getElementById('replyRequestId').value = requestId;
    document.getElementById('replyComment').value = ''; // Clear previous comment
    
    // Show modal
    document.getElementById('replyModal').classList.remove('hidden');
}

function closeReplyModal() {
    document.getElementById('replyModal').classList.add('hidden');
}

// Function to save all statuses
function confirmSaveAndReply() {
    const requestId = document.getElementById('replyRequestId').value;
    const comment = document.getElementById('replyComment').value;
    
    // Get selected values
    const facultyStatus = document.querySelector(`input[name="Tstatus[${requestId}]"]:checked`);
    const companyStatus = document.querySelector(`input[name="TstatusCompany[${requestId}]"]:checked`);
    const documentStatus = document.querySelector(`input[name="TstatusDocument[${requestId}]"]:checked`);
    
    const facultyValue = facultyStatus.value.split(',')[1];
    const companyValue = companyStatus.value.split(',')[1];
    const documentValue = documentStatus.value.split(',')[1];
    
    // Create form data
    const formData = new FormData();
    formData.append('request_id', requestId);
    formData.append('faculty_status', facultyValue);
    formData.append('company_status', companyValue);
    formData.append('document_status', documentValue);
    formData.append('document_comment', comment);
    formData.append('save_and_reply', '1');
    
    // Send AJAX request
    fetch('index.php?page=admin&action=save_status_reply', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close modal
            closeReplyModal();
            
            // Show success message
            alert('บันทึกข้อมูลการตอบกลับเอกสารเรียบร้อยแล้ว');
            
            // Reload page to show updated statuses (optional, or just update UI)
            // window.location.reload(); 
            
            // Update UI badges immediately without reload (Better UX)
            const row = document.querySelector(`input[name="Tstatus[${requestId}]"]`).closest('tr');
            
            // Update Faculty Status Badge (Visual only)
            // You can add logic here to update the visual radio buttons if needed, 
            // but since they are inputs, they are already updated by user selection.
            
        } else {
            alert('เกิดข้อผิดพลาด: ' + (data.message || 'ไม่สามารถบันทึกข้อมูลได้'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('เกิดข้อผิดพลาดในการบันทึกข้อมูล');
    });
}

function getStatusText(type, value) {
    if (type === 'faculty') {
        return value === 'approved' ? 'อนุมัติ' : value === 'rejected' ? 'ไม่อนุมัติ' : 'รออนุมัติ';
    } else if (type === 'company') {
        return value === 'accepted' ? 'รับ' : 'ปฏิเสธ';
    } else if (type === 'document') {
        return value === 'approved' ? 'ผ่าน' : 'ไม่ผ่าน';
    }
    return value;
}
</script>

<!-- Reply Modal -->
<div id="replyModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-[9999]" style="z-index: 9999;">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 transform transition-all scale-100 relative z-[10000]">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900">ระบุรายละเอียดเพิ่มเติม</h3>
            <p class="text-gray-500 text-sm mt-2">ระบุข้อความถึงนิสิต (เช่น เอกสารไม่ครบ, แก้ไขตรงไหน)</p>
        </div>

        <input type="hidden" id="replyRequestId">
        
        <div class="mb-6">
            <textarea id="replyComment" rows="4" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none text-gray-900" placeholder="พิมพ์ข้อความที่นี่..." autofocus style="min-height: 120px;"></textarea>
        </div>

        <div class="flex gap-3">
            <button onclick="closeReplyModal()" class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-medium transition-colors">
                ยกเลิก
            </button>
            <button onclick="confirmSaveAndReply()" class="flex-1 px-4 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 font-medium transition-colors shadow-lg shadow-blue-500/30">
                ยืนยันการบันทึก
            </button>
        </div>
    </div>
</div>

<script>
// ... existing code ...

// Function to open print modal
function openPrintModal(requestId, companyId, roundId) {
    // Show loading state or clear previous list
    const listContainer = document.getElementById('printStudentList');
    listContainer.innerHTML = '<div class="text-center py-4 text-gray-500">กำลังโหลดรายชื่อ...</div>';
    
    // Show modal
    document.getElementById('printModal').classList.remove('hidden');
    
    // Fetch students for this company and round
    // ใช้ absolute path เพื่อให้แน่ใจว่าเรียก API ได้ถูกต้อง
    const baseUrl = window.location.origin + '/test_internship';
    const url = `${baseUrl}/src/Admin/get_company_students.php?company_id=${companyId}&round_id=${roundId}`;
    console.log('Fetching students from:', url);
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            console.log('Response data:', data);
            
            // ตรวจสอบว่ามี error หรือไม่
            if (data.error) {
                let errorHtml = `<p class="text-center text-red-500">เกิดข้อผิดพลาด: ${data.error}</p>`;
                if (data.debug) {
                    errorHtml += `<pre class="text-xs mt-2 p-2 bg-gray-100 rounded">${JSON.stringify(data.debug, null, 2)}</pre>`;
                }
                listContainer.innerHTML = errorHtml;
                return;
            }
            
            if (data.length > 0) {
                let html = '<div class="space-y-2">';
                // Add "Select All" option
                html += `
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200 mb-2">
                        <input type="checkbox" id="selectAllPrint" class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500" onchange="toggleSelectAllPrint(this)">
                        <label for="selectAllPrint" class="ml-3 text-sm font-medium text-gray-700 cursor-pointer">เลือกทั้งหมด (${data.length} คน)</label>
                    </div>
                `;
                
                data.forEach(student => {
                    // Check if this is the current student to pre-select
                    const isCurrent = student.request_id == requestId ? 'checked' : '';
                    html += `
                        <div class="flex items-center p-3 hover:bg-gray-50 rounded-lg border border-gray-100 transition-colors">
                            <input type="checkbox" name="request_ids[]" value="${student.request_id}" ${isCurrent} class="print-checkbox w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">${student.std_code} - ${student.full_name}</p>
                                <p class="text-xs text-gray-500">${student.major_name || 'ไม่ระบุสาขา'}</p>
                            </div>
                        </div>
                    `;
                });
                html += '</div>';
                listContainer.innerHTML = html;
            } else {
                listContainer.innerHTML = '<p class="text-center text-red-500">ไม่พบข้อมูลนิสิต</p>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            listContainer.innerHTML = `<p class="text-center text-red-500">เกิดข้อผิดพลาดในการโหลดข้อมูล: ${error.message}</p>`;
        });
}

function closePrintModal() {
    document.getElementById('printModal').classList.add('hidden');
}

function toggleSelectAllPrint(source) {
    const checkboxes = document.querySelectorAll('.print-checkbox');
    checkboxes.forEach(cb => cb.checked = source.checked);
}

function submitPrintForm() {
    const form = document.getElementById('printForm');
    const checkboxes = form.querySelectorAll('input[name="request_ids[]"]:checked');
    
    if (checkboxes.length === 0) {
        alert('กรุณาเลือกนิสิตอย่างน้อย 1 คน');
        return;
    }
    
    // Submit form to open in new tab
    form.submit();
    closePrintModal();
}
</script>

<!-- Print Modal -->
<div id="printModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-[9999]" style="z-index: 9999;">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 transform transition-all scale-100 relative z-[10000]">
        <div class="flex justify-between items-center mb-4 border-b pb-4">
            <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                เลือกนิสิตเพื่อพิมพ์หนังสือ
            </h3>
            <button onclick="closePrintModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form id="printForm" action="src/Admin/print_letter.php" method="POST" target="_blank">
            <div class="mb-6 max-h-[60vh] overflow-y-auto custom-scrollbar" id="printStudentList">
                <!-- Student list will be loaded here -->
            </div>

            <div class="flex gap-3 pt-2 border-t">
                <button type="button" onclick="closePrintModal()" class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-medium transition-colors">
                    ยกเลิก
                </button>
                <button type="button" onclick="submitPrintForm()" class="flex-1 px-4 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 font-medium transition-colors shadow-lg shadow-blue-500/30 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    พิมพ์หนังสือ
                </button>
            </div>
        </form>
    </div>
</div>
