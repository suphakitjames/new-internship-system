<?php
require_once 'config/database.php';

try {
    $pdo->beginTransaction();

    // 1. Insert Regions
    $regions = [
        1 => 'ภาคเหนือ',
        2 => 'ภาคกลาง',
        3 => 'ภาคตะวันออกเฉียงเหนือ',
        4 => 'ภาคตะวันตก',
        5 => 'ภาคตะวันออก',
        6 => 'ภาคใต้'
    ];

    $stmt = $pdo->prepare("INSERT IGNORE INTO regions (id, name_th) VALUES (?, ?)");
    foreach ($regions as $id => $name) {
        $stmt->execute([$id, $name]);
    }

    // 2. Insert Provinces
    $provinces = [
        // ภาคเหนือ (Region 1)
        ['id' => 1, 'name' => 'เชียงใหม่', 'region_id' => 1],
        ['id' => 2, 'name' => 'เชียงราย', 'region_id' => 1],
        ['id' => 3, 'name' => 'ลำปาง', 'region_id' => 1],
        ['id' => 4, 'name' => 'ลำพูน', 'region_id' => 1],
        ['id' => 5, 'name' => 'แม่ฮ่องสอน', 'region_id' => 1],
        ['id' => 6, 'name' => 'น่าน', 'region_id' => 1],
        ['id' => 7, 'name' => 'พะเยา', 'region_id' => 1],
        ['id' => 8, 'name' => 'แพร่', 'region_id' => 1],
        ['id' => 9, 'name' => 'อุตรดิตถ์', 'region_id' => 1],

        // ภาคกลาง (Region 2)
        ['id' => 10, 'name' => 'กรุงเทพมหานคร', 'region_id' => 2],
        ['id' => 11, 'name' => 'สมุทรปราการ', 'region_id' => 2],
        ['id' => 12, 'name' => 'นนทบุรี', 'region_id' => 2],
        ['id' => 13, 'name' => 'ปทุมธานี', 'region_id' => 2],
        ['id' => 14, 'name' => 'พระนครศรีอยุธยา', 'region_id' => 2],
        ['id' => 15, 'name' => 'อ่างทอง', 'region_id' => 2],
        ['id' => 16, 'name' => 'ลพบุรี', 'region_id' => 2],
        ['id' => 17, 'name' => 'สิงห์บุรี', 'region_id' => 2],
        ['id' => 18, 'name' => 'ชัยนาท', 'region_id' => 2],
        ['id' => 19, 'name' => 'สระบุรี', 'region_id' => 2],
        ['id' => 20, 'name' => 'นครนายก', 'region_id' => 2],
        ['id' => 21, 'name' => 'นครปฐม', 'region_id' => 2],
        ['id' => 22, 'name' => 'สมุทรสาคร', 'region_id' => 2],
        ['id' => 23, 'name' => 'สมุทรสงคราม', 'region_id' => 2],
        ['id' => 24, 'name' => 'สุพรรณบุรี', 'region_id' => 2],
        ['id' => 25, 'name' => 'กำแพงเพชร', 'region_id' => 2],
        ['id' => 26, 'name' => 'พิจิตร', 'region_id' => 2],
        ['id' => 27, 'name' => 'พิษณุโลก', 'region_id' => 2],
        ['id' => 28, 'name' => 'เพชรบูรณ์', 'region_id' => 2],
        ['id' => 29, 'name' => 'นครสวรรค์', 'region_id' => 2],
        ['id' => 30, 'name' => 'อุทัยธานี', 'region_id' => 2],
        ['id' => 31, 'name' => 'สุโขทัย', 'region_id' => 2],

        // ภาคตะวันออกเฉียงเหนือ (Region 3)
        ['id' => 32, 'name' => 'นครราชสีมา', 'region_id' => 3],
        ['id' => 33, 'name' => 'บุรีรัมย์', 'region_id' => 3],
        ['id' => 34, 'name' => 'สุรินทร์', 'region_id' => 3],
        ['id' => 35, 'name' => 'ศรีสะเกษ', 'region_id' => 3],
        ['id' => 36, 'name' => 'อุบลราชธานี', 'region_id' => 3],
        ['id' => 37, 'name' => 'ยโสธร', 'region_id' => 3],
        ['id' => 38, 'name' => 'ชัยภูมิ', 'region_id' => 3],
        ['id' => 39, 'name' => 'อำนาจเจริญ', 'region_id' => 3],
        ['id' => 40, 'name' => 'หนองบัวลำภู', 'region_id' => 3],
        ['id' => 41, 'name' => 'ขอนแก่น', 'region_id' => 3],
        ['id' => 42, 'name' => 'อุดรธานี', 'region_id' => 3],
        ['id' => 43, 'name' => 'เลย', 'region_id' => 3],
        ['id' => 44, 'name' => 'หนองคาย', 'region_id' => 3],
        ['id' => 45, 'name' => 'มหาสารคาม', 'region_id' => 3],
        ['id' => 46, 'name' => 'ร้อยเอ็ด', 'region_id' => 3],
        ['id' => 47, 'name' => 'กาฬสินธุ์', 'region_id' => 3],
        ['id' => 48, 'name' => 'สกลนคร', 'region_id' => 3],
        ['id' => 49, 'name' => 'นครพนม', 'region_id' => 3],
        ['id' => 50, 'name' => 'มุกดาหาร', 'region_id' => 3],
        ['id' => 51, 'name' => 'บึงกาฬ', 'region_id' => 3],

        // ภาคตะวันตก (Region 4)
        ['id' => 52, 'name' => 'ตาก', 'region_id' => 4],
        ['id' => 53, 'name' => 'กาญจนบุรี', 'region_id' => 4],
        ['id' => 54, 'name' => 'ราชบุรี', 'region_id' => 4],
        ['id' => 55, 'name' => 'เพชรบุรี', 'region_id' => 4],
        ['id' => 56, 'name' => 'ประจวบคีรีขันธ์', 'region_id' => 4],

        // ภาคตะวันออก (Region 5)
        ['id' => 57, 'name' => 'ปราจีนบุรี', 'region_id' => 5],
        ['id' => 58, 'name' => 'กบินทร์บุรี', 'region_id' => 5], // Note: Kabin Buri is a district, usually Prachinburi covers it. Let's stick to standard provinces.
        ['id' => 59, 'name' => 'สระแก้ว', 'region_id' => 5],
        ['id' => 60, 'name' => 'ฉะเชิงเทรา', 'region_id' => 5],
        ['id' => 61, 'name' => 'ชลบุรี', 'region_id' => 5],
        ['id' => 62, 'name' => 'ระยอง', 'region_id' => 5],
        ['id' => 63, 'name' => 'จันทบุรี', 'region_id' => 5],
        ['id' => 64, 'name' => 'ตราด', 'region_id' => 5],

        // ภาคใต้ (Region 6)
        ['id' => 65, 'name' => 'ชุมพร', 'region_id' => 6],
        ['id' => 66, 'name' => 'ระนอง', 'region_id' => 6],
        ['id' => 67, 'name' => 'สุราษฎร์ธานี', 'region_id' => 6],
        ['id' => 68, 'name' => 'พังงา', 'region_id' => 6],
        ['id' => 69, 'name' => 'ภูเก็ต', 'region_id' => 6],
        ['id' => 70, 'name' => 'กระบี่', 'region_id' => 6],
        ['id' => 71, 'name' => 'นครศรีธรรมราช', 'region_id' => 6],
        ['id' => 72, 'name' => 'ตรัง', 'region_id' => 6],
        ['id' => 73, 'name' => 'พัทลุง', 'region_id' => 6],
        ['id' => 74, 'name' => 'สตูล', 'region_id' => 6],
        ['id' => 75, 'name' => 'สงขลา', 'region_id' => 6],
        ['id' => 76, 'name' => 'ปัตตานี', 'region_id' => 6],
        ['id' => 77, 'name' => 'ยะลา', 'region_id' => 6],
        ['id' => 78, 'name' => 'นราธิวาส', 'region_id' => 6]
    ];

    $stmt = $pdo->prepare("INSERT IGNORE INTO provinces (id, name_th, region_id) VALUES (?, ?, ?)");
    foreach ($provinces as $p) {
        $stmt->execute([$p['id'], $p['name'], $p['region_id']]);
    }

    $pdo->commit();
    echo "Regions and Provinces seeded successfully!";

} catch (Exception $e) {
    $pdo->rollBack();
    echo "Error: " . $e->getMessage();
}
?>
