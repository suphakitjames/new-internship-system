<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../auth/login.php");
    exit();
}

// Region Mapping
$regions = [
    'North' => ['เชียงใหม่', 'เชียงราย', 'ลำปาง', 'ลำพูน', 'แม่ฮ่องสอน', 'น่าน', 'พะเยา', 'แพร่', 'อุตรดิตถ์'],
    'Northeast' => ['ขอนแก่น', 'นครราชสีมา', 'อุบลราชธานี', 'อุดรธานี', 'มหาสารคาม', 'กาฬสินธุ์', 'ร้อยเอ็ด', 'บุรีรัมย์', 'สุรินทร์', 'ศรีสะเกษ', 'ชัยภูมิ', 'เลย', 'หนองคาย', 'บึงกาฬ', 'นครพนม', 'สกลนคร', 'มุกดาหาร', 'ยโสธร', 'อำนาจเจริญ', 'หนองบัวลำภู'],
    'Central' => ['กรุงเทพมหานคร', 'นนทบุรี', 'ปทุมธานี', 'สมุทรปราการ', 'สมุทรสาคร', 'สมุทรสงคราม', 'นครปฐม', 'พระนครศรีอยุธยา', 'อ่างทอง', 'ลพบุรี', 'สิงห์บุรี', 'ชัยนาท', 'สระบุรี', 'นครนายก', 'สุพรรณบุรี', 'นครสวรรค์', 'พิจิตร', 'พิษณุโลก', 'เพชรบูรณ์', 'กำแพงเพชร', 'สุโขทัย', 'อุทัยธานี'],
    'East' => ['ชลบุรี', 'ระยอง', 'จันทบุรี', 'ตราด', 'ฉะเชิงเทรา', 'ปราจีนบุรี', 'สระแก้ว'],
    'West' => ['ราชบุรี', 'กาญจนบุรี', 'เพชรบุรี', 'ประจวบคีรีขันธ์', 'ตาก'],
    'South' => ['ภูเก็ต', 'กระบี่', 'พังงา', 'ระนอง', 'สุราษฎร์ธานี', 'ชุมพร', 'นครศรีธรรมราช', 'พัทลุง', 'สงขลา', 'สตูล', 'ตรัง', 'ปัตตานี', 'ยะลา', 'นราธิวาส']
];

// Handle Search
$search_keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$search_region = isset($_GET['region']) ? $_GET['region'] : '';

$query = "SELECT * FROM companies WHERE status = 'approved'";
$params = [];

if ($search_keyword) {
    $query .= " AND (name LIKE ? OR description LIKE ?)";
    $params[] = "%$search_keyword%";
    $params[] = "%$search_keyword%";
}

if ($search_region && isset($regions[$search_region])) {
    $provinces = $regions[$search_region];
    $placeholders = implode(',', array_fill(0, count($provinces), '?'));
    $query .= " AND province IN ($placeholders)";
    $params = array_merge($params, $provinces);
}

$query .= " ORDER BY created_at DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$companies = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Internship System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800">

    <!-- Navbar -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold">IS</div>
                    <span class="font-bold text-xl text-slate-800">Internship System</span>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-slate-600">Welcome, Student</span>
                    <a href="../auth/logout.php" class="text-sm font-medium text-red-600 hover:text-red-700 transition-colors">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900 mb-2">Find Your Internship</h1>
            <p class="text-slate-600">Browse approved companies and opportunities.</p>
        </div>

        <!-- Search & Filter -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 mb-8">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <label for="keyword" class="block text-sm font-medium text-slate-700 mb-1">Search</label>
                    <input type="text" name="keyword" id="keyword" value="<?php echo htmlspecialchars($search_keyword); ?>" placeholder="Company name, job title..." class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                </div>
                <div>
                    <label for="region" class="block text-sm font-medium text-slate-700 mb-1">Region</label>
                    <select name="region" id="region" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                        <option value="">All Regions</option>
                        <?php foreach ($regions as $key => $val): ?>
                            <option value="<?php echo $key; ?>" <?php echo $search_region === $key ? 'selected' : ''; ?>>
                                <?php echo $key; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700 transition-colors shadow-sm hover:shadow-md">
                        Search
                    </button>
                </div>
            </form>
        </div>

        <!-- Company Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (count($companies) > 0): ?>
                <?php foreach ($companies as $company): ?>
                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow duration-200 overflow-hidden flex flex-col h-full">
                        <div class="p-6 flex-1">
                            <div class="flex items-start justify-between mb-4">
                                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600 font-bold text-xl">
                                    <?php echo strtoupper(mb_substr($company['name'], 0, 1)); ?>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Approved
                                </span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 mb-1 line-clamp-1"><?php echo htmlspecialchars($company['name']); ?></h3>
                            <p class="text-sm text-slate-500 mb-4 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <?php echo htmlspecialchars($company['province']); ?>
                            </p>
                            <p class="text-slate-600 text-sm line-clamp-3 mb-4">
                                <?php echo htmlspecialchars($company['description']); ?>
                            </p>
                            <div class="text-xs text-slate-500 space-y-1">
                                <p><span class="font-medium">Contact:</span> <?php echo htmlspecialchars($company['contact_person']); ?></p>
                                <p><span class="font-medium">Phone:</span> <?php echo htmlspecialchars($company['phone']); ?></p>
                            </div>
                        </div>
                        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                            <a href="company_detail.php?id=<?php echo $company['id']; ?>" class="block w-full text-center text-blue-600 font-medium text-sm hover:text-blue-700">View Details &rarr;</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full text-center py-12">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <h3 class="text-lg font-medium text-slate-900">No companies found</h3>
                    <p class="text-slate-500 mt-1">Try adjusting your search or filter to find what you're looking for.</p>
                </div>
            <?php endif; ?>
        </div>

    </main>

</body>
</html>
