<?php
// src/Internships/index.php

// Fetch Regions from Database
$regions_query = "SELECT * FROM regions";
$regions_stmt = $pdo->query($regions_query);
$regions = $regions_stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle Search
$search_keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$search_region_id = isset($_GET['region']) ? $_GET['region'] : '';

// Query to fetch companies with province and region details
// Note: companies.province now stores the Province ID (e.g. '1', '2')
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

$query .= " ORDER BY c.created_at DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$companies = $stmt->fetchAll();
?>

<div class="mb-8 flex flex-col md:flex-row md:items-end md:justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold text-slate-900 mb-2">สถานที่ฝึกงาน (Internships)</h1>
        <p class="text-slate-600">ค้นหาสถานที่ฝึกงานที่น่าสนใจจากบริษัทชั้นนำทั่วประเทศ</p>
    </div>
    <div class="flex gap-3">
        <a href="index.php?page=auth&action=employer_login" class="inline-flex items-center justify-center bg-purple-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-purple-700 transition-colors shadow-sm hover:shadow-md whitespace-nowrap">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
            </svg>
            เข้าสู่ระบบผู้ประกอบการ
        </a>
        <a href="index.php?page=student&action=add_company" class="inline-flex items-center justify-center bg-green-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-green-700 transition-colors shadow-sm hover:shadow-md whitespace-nowrap">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            แจ้งเพิ่มสถานที่ฝึกงาน
        </a>
    </div>
</div>

<!-- Search & Filter -->
<div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 mb-8">
    <form method="GET" action="index.php">
        <input type="hidden" name="page" value="internships">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <label for="keyword" class="block text-sm font-medium text-slate-700 mb-1">ค้นหา (Search)</label>
                <input type="text" name="keyword" id="keyword" value="<?php echo htmlspecialchars($search_keyword); ?>" placeholder="ชื่อบริษัท, ตำแหน่งงาน..." class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm p-2 border">
            </div>
            <div>
                <label for="region" class="block text-sm font-medium text-slate-700 mb-1">ภูมิภาค (Region)</label>
                <select name="region" id="region" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm p-2 border">
                    <option value="">ทุกภูมิภาค</option>
                    <?php foreach ($regions as $region): ?>
                        <option value="<?php echo $region['id']; ?>" <?php echo $search_region_id == $region['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($region['name_th']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="province" class="block text-sm font-medium text-slate-700 mb-1">จังหวัด (Province)</label>
                <select name="province" id="province" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm p-2.5 border">
                    <option value="">ทุกจังหวัด</option>
                    <?php
                    if ($search_region_id) {
                        // Show provinces for selected region
                        $prov_stmt = $pdo->prepare("SELECT * FROM provinces WHERE region_id = ? ORDER BY name_th ASC");
                        $prov_stmt->execute([$search_region_id]);
                    } else {
                        // Show all provinces
                        $prov_stmt = $pdo->query("SELECT * FROM provinces ORDER BY name_th ASC");
                    }
                    $provinces_list = $prov_stmt->fetchAll();
                    foreach ($provinces_list as $prov) {
                        $selected = ($search_province_id == $prov['id']) ? 'selected' : '';
                        echo "<option value=\"{$prov['id']}\" $selected>{$prov['name_th']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700 transition-colors shadow-sm hover:shadow-md">
                    ค้นหา
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Company Grid -->
<div id="company-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php if (count($companies) > 0): ?>
        <?php foreach ($companies as $company): ?>
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow duration-200 overflow-hidden flex flex-col h-full">
                <div class="p-6 flex-1">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600 font-bold text-xl">
                            <?php echo strtoupper(mb_substr($company['name'], 0, 1)); ?>
                        </div>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-1 line-clamp-1"><?php echo htmlspecialchars($company['name']); ?></h3>
                    <p class="text-sm text-slate-500 mb-4 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
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
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-span-full text-center py-12">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 mb-4">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-slate-900">ไม่พบข้อมูลบริษัท</h3>
            <p class="text-slate-500 mt-1">ลองปรับคำค้นหาหรือเลือกภูมิภาคอื่น</p>
        </div>
    <?php endif; ?>
</div>

<script>
    const keywordInput = document.getElementById('keyword');
    const regionSelect = document.getElementById('region');
    const provinceSelect = document.getElementById('province');
    const companyGrid = document.getElementById('company-grid');
    let debounceTimer;

    // Load provinces based on region selection
    function loadProvinces(regionId = '') {
        fetch(`src/Internships/get_provinces.php?region_id=${regionId}`)
            .then(response => response.json())
            .then(provinces => {
                // Keep the first "ทุกจังหวัด" option
                provinceSelect.innerHTML = '<option value="">ทุกจังหวัด</option>';

                provinces.forEach(province => {
                    const option = document.createElement('option');
                    option.value = province.id;
                    option.textContent = province.name_th;
                    provinceSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error loading provinces:', error));
    }

    // Perform search
    function performSearch() {
        const keyword = keywordInput.value;
        const region = regionSelect.value;
        const province = provinceSelect.value;

        const params = new URLSearchParams({
            keyword: keyword,
            region: region,
            province: province
        });

        fetch(`src/Internships/search_api.php?${params}`)
            .then(response => response.text())
            .then(html => {
                companyGrid.innerHTML = html;
            })
            .catch(error => console.error('Error searching:', error));
    }

    // Region change handler
    regionSelect.addEventListener('change', function() {
        const regionId = this.value;
        loadProvinces(regionId); // Load provinces for selected region (or all if empty)
        provinceSelect.value = ''; // Reset province selection
        performSearch(); // Trigger search
    });

    // Province change handler
    provinceSelect.addEventListener('change', function() {
        performSearch();
    });

    // Keyword search with debounce
    keywordInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            performSearch();
        }, 50);
    });

    // Prevent form submission on enter key for keyword input
    keywordInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            performSearch();
        }
    });

    // Initial load of all provinces on page load
    loadProvinces();
</script>