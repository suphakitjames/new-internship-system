<?php
require_once 'config/database.php';

try {
    // Create majors table (DDL causes implicit commit, so do it before transaction)
    $pdo->exec("CREATE TABLE IF NOT EXISTS majors (
        id VARCHAR(10) PRIMARY KEY,
        name VARCHAR(100) NOT NULL
    )");

    $pdo->beginTransaction();

    // Insert Majors
    $majors = [
        ['id' => 'GM', 'name' => 'การจัดการ'],
        ['id' => 'ENT', 'name' => 'การจัดการการประกอบการ'],
        ['id' => 'HRM', 'name' => 'การจัดการทรัพยากรมนุษย์'],
        ['id' => 'ECOM', 'name' => 'การจัดการพาณิชย์อิเล็กทรอนิกส์'],
        ['id' => 'MK', 'name' => 'การตลาด'],
        ['id' => 'FM', 'name' => 'การบริหารการเงิน'],
        ['id' => 'AC', 'name' => 'การบัญชี'],
        ['id' => 'AC (EP)', 'name' => 'การบัญชี (หลักสูตรภาษาอังกฤษ)'],
        ['id' => 'BC', 'name' => 'คอมพิวเตอร์ธุรกิจ'],
        ['id' => 'BIT', 'name' => 'เทคโนโลยีสารสนเทศธุรกิจ'],
        ['id' => 'IB', 'name' => 'ธุรกิจระหว่างประเทศ'],
        ['id' => 'BE', 'name' => 'เศรษฐศาสตร์ธุรกิจ']
    ];

    $stmt = $pdo->prepare("INSERT IGNORE INTO majors (id, name) VALUES (?, ?)");
    foreach ($majors as $major) {
        $stmt->execute([$major['id'], $major['name']]);
    }

    $pdo->commit();
    echo "Majors table created and seeded successfully!";

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "Error: " . $e->getMessage();
}
?>
