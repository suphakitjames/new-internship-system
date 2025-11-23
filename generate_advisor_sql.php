<?php
// Majors data
$majors = [
    ['id' => 'CS', 'name' => 'วิทยาการคอมพิวเตอร์', 'faculty' => 'คณะวิทยาศาสตร์'],
    ['id' => 'IT', 'name' => 'เทคโนโลยีสารสนเทศ', 'faculty' => 'คณะวิทยาศาสตร์'],
    ['id' => 'CE', 'name' => 'วิศวกรรมคอมพิวเตอร์', 'faculty' => 'คณะวิศวกรรมศาสตร์'],
    ['id' => 'EE', 'name' => 'วิศวกรรมไฟฟ้า', 'faculty' => 'คณะวิศวกรรมศาสตร์'],
    ['id' => 'ME', 'name' => 'วิศวกรรมเครื่องกล', 'faculty' => 'คณะวิศวกรรมศาสตร์'],
    ['id' => 'BA', 'name' => 'บริหารธุรกิจ', 'faculty' => 'คณะบริหารธุรกิจ'],
    ['id' => 'ACC', 'name' => 'การบัญชี', 'faculty' => 'คณะบริหารธุรกิจ'],
    ['id' => 'MKT', 'name' => 'การตลาด', 'faculty' => 'คณะบริหารธุรกิจ'],
    ['id' => 'ECON', 'name' => 'เศรษฐศาสตร์', 'faculty' => 'คณะเศรษฐศาสตร์'],
    ['id' => 'COMM', 'name' => 'นิเทศศาสตร์', 'faculty' => 'คณะนิเทศศาสตร์']
];

// Common password hash (password)
$password_hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

echo "-- Add Advisors Data SQL\n";
echo "USE internship_system;\n\n";

echo "-- 1. Ensure Majors Table Exists and has Data\n";
echo "CREATE TABLE IF NOT EXISTS majors (\n";
echo "    id VARCHAR(10) PRIMARY KEY,\n";
echo "    name VARCHAR(100) NOT NULL,\n";
echo "    faculty VARCHAR(100),\n";
echo "    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP\n";
echo ");\n\n";

echo "INSERT INTO majors (id, name, faculty) VALUES\n";
$major_values = [];
foreach ($majors as $m) {
    $major_values[] = "('{$m['id']}', '{$m['name']}', '{$m['faculty']}')";
}
echo implode(",\n", $major_values) . "\n";
echo "ON DUPLICATE KEY UPDATE name=VALUES(name), faculty=VALUES(faculty);\n\n";

echo "-- 2. Create Advisors Table\n";
echo "CREATE TABLE IF NOT EXISTS advisors (\n";
echo "    user_id INT PRIMARY KEY,\n";
echo "    major_id VARCHAR(10),\n";
echo "    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,\n";
echo "    FOREIGN KEY (major_id) REFERENCES majors(id) ON DELETE SET NULL\n";
echo ");\n\n";

echo "-- 3. Insert Advisors\n";

$first_names = ['สมชาย', 'สมหญิง', 'วิชัย', 'วิไล', 'ประสงค์', 'ปราณี', 'สุชาติ', 'สุรีรัตน์', 'ณัฐพล', 'ณัฐวดี', 'กิตติ', 'กานดา', 'ชัย', 'ชลธิชา', 'ธนพล', 'ธนพร', 'นพดล', 'นภา', 'ปรีชา', 'ปวีณา'];
$last_names = ['ใจดี', 'รักเรียน', 'มีสุข', 'สุขใจ', 'มั่นคง', 'เจริญ', 'รุ่งเรือง', 'สมบูรณ์', 'ยอดเยี่ยม', 'ดีเลิศ', 'เก่งกล้า', 'สามารถ', 'วิเศษ', 'เลิศล้ำ', 'ประเสริฐ', 'มั่งคั่ง', 'ร่ำรวย', 'ศรีสุข', 'มีชัย', 'ชนะ'];

$count = 1;
foreach ($majors as $major) {
    echo "-- Advisors for {$major['name']} ({$major['id']})\n";
    for ($i = 1; $i <= 10; $i++) {
        // Generate random name
        $fname = $first_names[array_rand($first_names)];
        $lname = $last_names[array_rand($last_names)];
        $fullname = "อ.{$fname} {$lname}";

        // Generate unique username/email
        $username = "advisor_{$major['id']}_{$i}"; // e.g., advisor_CS_1
        $email = strtolower("advisor.{$major['id']}.{$i}@university.ac.th");

        echo "INSERT INTO users (username, password, role, full_name, email) VALUES ('$username', '$password_hash', 'advisor', '$fullname', '$email');\n";
        echo "INSERT INTO advisors (user_id, major_id) VALUES (LAST_INSERT_ID(), '{$major['id']}');\n";
        $count++;
    }
    echo "\n";
}

echo "SELECT 'Advisors data inserted successfully' as status;\n";
