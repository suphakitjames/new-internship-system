<?php
// src/Internships/detail.php

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    echo "<div class='text-center text-red-500 py-8'>Invalid Company ID</div>";
    return;
}

// Fetch company details
$query = "SELECT c.*, p.name_th as province_name, r.name_th as region_name 
          FROM companies c 
          LEFT JOIN provinces p ON c.province = p.id 
          LEFT JOIN regions r ON p.region_id = r.id 
          WHERE c.id = ? AND c.status = 'approved'";
$stmt = $pdo->prepare($query);
$stmt->execute([$id]);
$company = $stmt->fetch();

if (!$company) {
    echo "<div class='text-center text-red-500 py-8'>Company not found or not approved.</div>";
    return;
}
?>

<div class="mb-6">
    <a href="index.php?page=internships" class="inline-flex items-center text-slate-500 hover:text-blue-600 transition-colors">
        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        ย้อนกลับ
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
    <!-- Header -->
    <div class="p-8 border-b border-slate-100 bg-slate-50/50">
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
            <div class="flex items-start gap-6">
                <div class="w-20 h-20 bg-blue-600 rounded-2xl flex items-center justify-center text-white font-bold text-3xl shadow-lg shadow-blue-200">
                    <?php echo strtoupper(mb_substr($company['name'], 0, 1)); ?>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 mb-2"><?php echo htmlspecialchars($company['name']); ?></h1>
                    <div class="flex flex-wrap items-center gap-4 text-sm text-slate-600">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <?php echo htmlspecialchars($company['province_name'] ?? $company['province']); ?>
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <?php echo htmlspecialchars($company['phone']); ?>
                        </span>
                    </div>
                </div>
            </div>
            
            <?php
            // Check application status
            $user = $_SESSION['user'] ?? null;
            $can_apply = false;
            $apply_message = '';
            
            if ($user && $user['role'] === 'student') {
                $req_query = "SELECT * FROM internship_requests WHERE student_id = ? AND status IN ('pending', 'approved')";
                $req_stmt = $pdo->prepare($req_query);
                $req_stmt->execute([$user['id']]);
                $existing_request = $req_stmt->fetch();

                if ($existing_request) {
                    $can_apply = false;
                    $apply_message = "คุณมีคำร้องที่อยู่ระหว่างดำเนินการ (" . ucfirst($existing_request['status']) . ")";
                } else {
                    $can_apply = true;
                }
            } elseif (!$user) {
                $apply_message = "เข้าสู่ระบบเพื่อสมัคร";
            } else {
                $apply_message = "สำหรับนิสิตเท่านั้น";
            }
            ?>

            <?php if ($can_apply): ?>
                <form action="index.php?page=internships&action=submit_request" method="POST">
                    <input type="hidden" name="company_id" value="<?php echo $company['id']; ?>">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-xl font-medium hover:bg-blue-700 transition-colors shadow-lg shadow-blue-500/30" onclick="return confirm('ยืนยันการสมัครฝึกงานที่นี่?');">
                        สมัครฝึกงาน
                    </button>
                </form>
            <?php elseif (!$user): ?>
                <!-- Not logged in - show button that triggers login modal -->
                <button type="button" onclick="showLoginModal()" class="bg-blue-600 text-white px-6 py-3 rounded-xl font-medium hover:bg-blue-700 transition-colors shadow-lg shadow-blue-500/30">
                    สมัครฝึกงาน
                </button>
            <?php else: ?>
                <!-- Logged in but can't apply (has pending request or not a student) -->
                <button disabled class="bg-slate-100 text-slate-400 px-6 py-3 rounded-xl font-medium cursor-not-allowed border border-slate-200">
                    <?php echo $apply_message; ?>
                </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Content -->
    <div class="p-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">
            <section>
                <h3 class="text-xl font-bold text-slate-900 mb-4">รายละเอียดบริษัท</h3>
                <div class="prose prose-slate max-w-none text-slate-600">
                    <?php echo nl2br(htmlspecialchars($company['description'])); ?>
                </div>
            </section>

            <section>
                <h3 class="text-xl font-bold text-slate-900 mb-4">ตำแหน่งที่เปิดรับ</h3>
                <div class="bg-slate-50 rounded-xl p-6 border border-slate-100 text-center text-slate-500">
                    ยังไม่มีข้อมูลตำแหน่งงาน (Coming Soon)
                </div>
            </section>
        </div>

        <div class="space-y-6">
            <div class="bg-slate-50 rounded-xl p-6 border border-slate-100">
                <h3 class="font-bold text-slate-900 mb-4">ข้อมูลติดต่อ</h3>
                <div class="space-y-4 text-sm">
                    <div>
                        <label class="block text-slate-500 mb-1">ผู้ติดต่อ</label>
                        <div class="font-medium text-slate-900"><?php echo htmlspecialchars($company['contact_person']); ?></div>
                    </div>
                    <div>
                        <label class="block text-slate-500 mb-1">อีเมล</label>
                        <a href="mailto:<?php echo htmlspecialchars($company['email']); ?>" class="font-medium text-blue-600 hover:underline"><?php echo htmlspecialchars($company['email']); ?></a>
                    </div>
                    <div>
                        <label class="block text-slate-500 mb-1">ที่อยู่</label>
                        <div class="font-medium text-slate-900"><?php echo htmlspecialchars($company['address']); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Login Required Modal -->
<div id="loginModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4" onclick="if(event.target === this) hideLoginModal()">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all scale-95 opacity-0" id="loginModalContent">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-6 rounded-t-2xl">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white">ต้องเข้าสู่ระบบ</h3>
                </div>
                <button onclick="hideLoginModal()" class="text-white/80 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Modal Body -->
        <div class="p-8">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <p class="text-lg text-slate-700 mb-2">กรุณาเข้าสู่ระบบก่อนสมัครฝึกงาน</p>
                <p class="text-sm text-slate-500">คุณต้องเป็นนิสิตที่ลงทะเบียนในระบบเพื่อสมัครฝึกงาน</p>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex gap-3">
                <button onclick="hideLoginModal()" class="flex-1 px-6 py-3 border-2 border-slate-200 text-slate-600 rounded-xl font-medium hover:bg-slate-50 transition-colors">
                    ยกเลิก
                </button>
                <a href="index.php?page=auth&action=login" class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl font-medium hover:from-blue-700 hover:to-blue-800 transition-all shadow-lg shadow-blue-500/30 text-center">
                    เข้าสู่ระบบ
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function showLoginModal() {
    const modal = document.getElementById('loginModal');
    const content = document.getElementById('loginModalContent');
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Trigger animation
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function hideLoginModal() {
    const modal = document.getElementById('loginModal');
    const content = document.getElementById('loginModalContent');
    
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 200);
}

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        hideLoginModal();
    }
});
</script>
