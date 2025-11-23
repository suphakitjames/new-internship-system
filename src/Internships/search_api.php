<?php
require_once '../../config/database.php';

$search_keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$search_region_id = isset($_GET['region']) ? $_GET['region'] : '';
$search_province_id = isset($_GET['province']) ? $_GET['province'] : '';

$query = "SELECT c.*, p.name_th as province_name, r.name_th as region_name 
          FROM companies c 
          LEFT JOIN provinces p ON c.province = p.id 
          LEFT JOIN regions r ON p.region_id = r.id 
          WHERE c.status = 'approved'";
$params = [];

if ($search_keyword) {
    $query .= " AND (c.name LIKE ? OR c.description LIKE ?)";
    $params[] = "%$search_keyword%";
    $params[] = "%$search_keyword%";
}

if ($search_region_id) {
    $query .= " AND r.id = ?";
    $params[] = $search_region_id;
}

if ($search_province_id) {
    $query .= " AND c.province = ?";
    $params[] = $search_province_id;
}

$query .= " ORDER BY c.created_at DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$companies = $stmt->fetchAll();

if (count($companies) > 0):
    foreach ($companies as $company):
?>
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow duration-200 overflow-hidden flex flex-col h-full">
        <div class="p-6 flex-1">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600 font-bold text-xl">
                    <?php echo strtoupper(mb_substr($company['name'], 0, 1)); ?>
                </div>
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-1 line-clamp-1"><?php echo htmlspecialchars($company['name']); ?></h3>
            <p class="text-sm text-slate-500 mb-4 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <?php echo htmlspecialchars($company['province_name'] ?? $company['province']); ?>
            </p>
            <p class="text-slate-600 text-sm line-clamp-3 mb-4">
                <?php echo htmlspecialchars($company['description']); ?>
            </p>
            <div class="text-xs text-slate-500 space-y-1">
                <p><span class="font-medium">ติดต่อ:</span> <?php echo htmlspecialchars($company['contact_person']); ?></p>
                <p><span class="font-medium">โทร:</span> <?php echo htmlspecialchars($company['phone']); ?></p>
            </div>
        </div>
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
            <a href="index.php?page=internships&action=detail&id=<?php echo $company['id']; ?>" class="block w-full text-center text-blue-600 font-medium text-sm hover:text-blue-700">ดูรายละเอียด &rarr;</a>
        </div>
    </div>
<?php
    endforeach;
else:
?>
    <div class="col-span-full text-center py-12">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 mb-4">
            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
        <h3 class="text-lg font-medium text-slate-900">ไม่พบข้อมูลบริษัท</h3>
        <p class="text-slate-500 mt-1">ลองปรับคำค้นหาหรือเลือกภูมิภาคอื่น</p>
    </div>
<?php
endif;
?>
