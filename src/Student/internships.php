<?php
// src/Student/internships.php

$search = $_GET['search'] ?? '';
$province = $_GET['province'] ?? '';

// Build Query
$sql = "SELECT * FROM companies WHERE status = 'approved'";
$params = [];

if ($search) {
    $sql .= " AND (name LIKE ? OR description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($province) {
    $sql .= " AND province = ?";
    $params[] = $province;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$companies = $stmt->fetchAll();

// Get unique provinces for filter
$stmt = $pdo->query("SELECT DISTINCT province FROM companies WHERE status = 'approved' ORDER BY province");
$provinces = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">สถานที่ฝึกงาน</h1>
            <p class="text-gray-600">ค้นหาสถานที่ฝึกงานที่ได้รับการรับรองจากมหาวิทยาลัย</p>
        </div>
        <a href="index.php?page=student&action=add_company" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition flex items-center gap-2 shadow-lg shadow-green-500/30">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            แจ้งเพิ่มสถานที่ใหม่
        </a>
    </div>

    <!-- Search Bar -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-8">
        <form action="index.php" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="hidden" name="page" value="student">
            <input type="hidden" name="action" value="internships">
            
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">ค้นหาชื่อบริษัท / รายละเอียด</label>
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="พิมพ์คำค้นหา...">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">จังหวัด</label>
                <select name="province" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <option value="">ทั้งหมด</option>
                    <?php foreach ($provinces as $p): ?>
                        <option value="<?= htmlspecialchars($p) ?>" <?= $province === $p ? 'selected' : '' ?>>
                            <?= htmlspecialchars($p) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2.5 rounded-lg hover:bg-blue-700 transition">
                    ค้นหา
                </button>
            </div>
        </form>
    </div>

    <!-- Results Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (empty($companies)): ?>
            <div class="col-span-full text-center py-12 text-gray-500">
                ไม่พบข้อมูลสถานที่ฝึกงานตามเงื่อนไขที่ระบุ
            </div>
        <?php else: ?>
            <?php foreach ($companies as $company): ?>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition flex flex-col h-full">
                    <div class="h-32 bg-gradient-to-r from-blue-400 to-indigo-500 flex items-center justify-center">
                        <span class="text-white text-4xl font-bold opacity-50"><?= mb_substr($company['name'], 0, 1) ?></span>
                    </div>
                    <div class="p-6 flex-1 flex flex-col">
                        <h3 class="text-xl font-bold text-gray-900 mb-2"><?= htmlspecialchars($company['name']) ?></h3>
                        <div class="flex items-center text-gray-500 text-sm mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <?= htmlspecialchars($company['province']) ?>
                        </div>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3 flex-1">
                            <?= htmlspecialchars($company['description'] ?? 'ไม่มีรายละเอียด') ?>
                        </p>
                        <a href="index.php?page=student&action=company_detail&id=<?= $company['id'] ?>" class="block w-full text-center bg-gray-50 text-blue-600 font-medium py-2 rounded-lg hover:bg-blue-50 transition border border-blue-100">
                            ดูรายละเอียด
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
