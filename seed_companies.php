<?php
require_once 'config/database.php';

try {
    // Clear existing companies to avoid duplicates/confusion (DDL implicit commit)
    $pdo->exec("DELETE FROM companies");
    // Reset Auto Increment
    $pdo->exec("ALTER TABLE companies AUTO_INCREMENT = 1");

    $pdo->beginTransaction();

    // Helper function to get province ID by name
    function getProvinceId($pdo, $name) {
        $stmt = $pdo->prepare("SELECT id FROM provinces WHERE name_th LIKE ?");
        $stmt->execute(["%$name%"]);
        $result = $stmt->fetch();
        return $result ? $result['id'] : null;
    }

    // Companies Data with Dynamic Province IDs
    $companies = [
        [
            'name' => 'บริษัท ปตท. จำกัด (มหาชน)',
            'address' => '555 ถนนวิภาวดีรังสิต แขวงจตุจักร เขตจตุจักร กรุงเทพฯ 10900',
            'province_name' => 'กรุงเทพมหานคร',
            'contact_person' => 'คุณสมชาย ใจดี',
            'phone' => '02-537-2000',
            'email' => 'hr@pttplc.com',
            'description' => 'บริษัทพลังงานแห่งชาติ ผู้นำด้านธุรกิจน้ำมันและก๊าซธรรมชาติ',
            'status' => 'approved'
        ],
        [
            'name' => 'บริษัท เอสซีจี เคมิคอลส์ จำกัด (มหาชน)',
            'address' => '1 ถนนปูนซิเมนต์ไทย บางซื่อ กรุงเทพฯ 10800',
            'province_name' => 'กรุงเทพมหานคร',
            'contact_person' => 'คุณวิภาดา รักงาน',
            'phone' => '02-586-3333',
            'email' => 'recruitment@scg.com',
            'description' => 'ผู้นำด้านนวัตกรรมเคมีภัณฑ์ครบวงจร',
            'status' => 'approved'
        ],
        [
            'name' => 'บริษัท ไออาร์พีซี จำกัด (มหาชน)',
            'address' => '299 หมู่ 5 ถนนสุขุมวิท อำเภอเมืองระยอง ระยอง 21000',
            'province_name' => 'ระยอง',
            'contact_person' => 'คุณนิภา พัฒนา',
            'phone' => '038-611-333',
            'email' => 'hr@irpc.co.th',
            'description' => 'ผู้ประกอบการอุตสาหกรรมปิโตรเคมีครบวงจรแห่งแรกในเอเชียตะวันออกเฉียงใต้',
            'status' => 'approved'
        ],
        [
            'name' => 'โรงงานน้ำตาลมิตรผล (เลย)',
            'address' => '99 หมู่ 9 ถนนเลย-ด่านซ้าย อำเภอวังสะพุง เลย 42130',
            'province_name' => 'เลย',
            'contact_person' => 'คุณประเสริฐ มั่นคง',
            'phone' => '042-801-111',
            'email' => 'hr_loei@mitrphol.com',
            'description' => 'โรงงานผลิตน้ำตาลทรายดิบและน้ำตาลทรายขาวบริสุทธิ์',
            'status' => 'approved'
        ],
        [
            'name' => 'บริษัท เชียงใหม่โฟรเซ่นฟู้ดส์ จำกัด (มหาชน)',
            'address' => '149/34 ซอยแองโกลพลาซ่า ถนนสุรวงศ์ บางรัก กรุงเทพฯ (สำนักงานใหญ่)',
            'province_name' => 'เชียงใหม่',
            'contact_person' => 'คุณอารียา สดใส',
            'phone' => '053-222-111',
            'email' => 'hr@cmfrozen.com',
            'description' => 'ผลิตและส่งออกผักแช่แข็งคุณภาพสูง',
            'status' => 'approved'
        ],
        [
            'name' => 'บริษัท ชลบุรี คอนกรีต จำกัด',
            'address' => '123 หมู่ 1 ถนนสุขุมวิท อำเภอเมืองชลบุรี ชลบุรี 20000',
            'province_name' => 'ชลบุรี',
            'contact_person' => 'คุณสมศักดิ์ ก่อสร้าง',
            'phone' => '038-123-456',
            'email' => 'contact@chonburiconcrete.com',
            'description' => 'ผลิตคอนกรีตผสมเสร็จและผลิตภัณฑ์คอนกรีต',
            'status' => 'approved'
        ],
        [
            'name' => 'โรงแรมภูเก็ต เกรซแลนด์ รีสอร์ท แอนด์ สปา',
            'address' => '190 ถนนทวีวงศ์ หาดป่าตอง อำเภอกะทู้ ภูเก็ต 83150',
            'province_name' => 'ภูเก็ต',
            'contact_person' => 'HR Manager',
            'phone' => '076-370-500',
            'email' => 'hr@phuketgraceland.com',
            'description' => 'โรงแรมและรีสอร์ทหรูระดับ 5 ดาว ริมหาดป่าตอง',
            'status' => 'approved'
        ],
        [
            'name' => 'ธนาคารแห่งประเทศไทย สำนักงานภาคตะวันออกเฉียงเหนือ',
            'address' => '190 ถนนศรีจันทร์ อำเภอเมืองขอนแก่น ขอนแก่น 40000',
            'province_name' => 'ขอนแก่น',
            'contact_person' => 'ผู้อำนวยการฝ่ายทรัพยากรบุคคล',
            'phone' => '043-333-000',
            'email' => 'hr_ne@bot.or.th',
            'description' => 'ธนาคารกลาง ดูแลเสถียรภาพทางการเงินของประเทศ',
            'status' => 'approved'
        ]
    ];

    $stmt = $pdo->prepare("INSERT INTO companies (name, address, province, contact_person, phone, email, description, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    
    foreach ($companies as $c) {
        $province_id = getProvinceId($pdo, $c['province_name']);
        
        if ($province_id) {
            $stmt->execute([
                $c['name'],
                $c['address'],
                $province_id,
                $c['contact_person'],
                $c['phone'],
                $c['email'],
                $c['description'],
                $c['status']
            ]);
        } else {
            echo "Warning: Province '{$c['province_name']}' not found for company '{$c['name']}'. Skipping.<br>";
        }
    }

    $pdo->commit();
    echo "Companies seeded successfully with correct Province IDs (fetched by name)!";

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "Error: " . $e->getMessage();
}
?>
