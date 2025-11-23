<?php
require_once 'config/database.php';

try {
    // 1. Find advisor_gm_1
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = 'advisor_gm_1'");
    $stmt->execute();
    $advisor = $stmt->fetch();

    if (!$advisor) {
        die("Error: advisor_gm_1 not found. Please check the username from check_advisor.php");
    }

    // 2. Find a student (any student)
    $stmt = $pdo->query("SELECT user_id FROM students LIMIT 1");
    $student = $stmt->fetch();

    if (!$student) {
        // Create a dummy student if none exists
        $pdo->exec("INSERT INTO users (username, password, role, full_name, email) VALUES ('student_test', 'password', 'student', 'Test Student', 'student@test.com')");
        $studentId = $pdo->lastInsertId();
        $pdo->exec("INSERT INTO students (user_id, student_id, major) VALUES ($studentId, '63000000', 'GM')");
        $student['user_id'] = $studentId;
    }

    // 3. Insert a message
    $stmt = $pdo->prepare("
        INSERT INTO advisor_messages (student_id, advisor_id, message, is_read, created_at) 
        VALUES (?, ?, 'สวัสดีครับอาจารย์ ผมมีเรื่องปรึกษาเกี่ยวกับการเลือกสถานที่ฝึกงานครับ', 0, NOW())
    ");
    $stmt->execute([$student['user_id'], $advisor['id']]);

    echo "<h1 style='color: green;'>Success!</h1>";
    echo "<p>Created a test message for <strong>advisor_gm_1</strong>.</p>";
    echo "<p>Please go back to the <a href='index.php?page=advisor&action=dashboard'>Advisor Dashboard</a> to see it.</p>";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
