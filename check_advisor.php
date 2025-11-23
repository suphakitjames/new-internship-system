<?php
require_once 'config/database.php';

echo "<h1>Checking Advisor Data</h1>";

try {
    $stmt = $pdo->query("SELECT * FROM users WHERE role = 'advisor' LIMIT 5");
    $advisors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($advisors) > 0) {
        echo "<p style='color: green;'>Found " . count($advisors) . " advisors in the database.</p>";
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Username</th><th>Full Name</th><th>Role</th></tr>";
        foreach ($advisors as $advisor) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($advisor['id']) . "</td>";
            echo "<td>" . htmlspecialchars($advisor['username']) . "</td>";
            echo "<td>" . htmlspecialchars($advisor['full_name']) . "</td>";
            echo "<td>" . htmlspecialchars($advisor['role']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";

        // Check specific test user
        $testUser = 'advisor_cs_1';
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$testUser]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            echo "<p style='color: green;'>Test user '$testUser' exists (ID: {$user['id']}).</p>";

            // Check advisors table
            $stmt = $pdo->prepare("
                SELECT a.*, m.name as major_name 
                FROM advisors a 
                LEFT JOIN majors m ON a.major_id = m.id 
                WHERE a.user_id = ?
            ");
            $stmt->execute([$user['id']]);
            $advisorData = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($advisorData) {
                echo "<p style='color: green;'>Linked to 'advisors' table.</p>";
                echo "<p>Major ID: " . htmlspecialchars($advisorData['major_id']) . "</p>";
                if ($advisorData['major_name']) {
                    echo "<p style='color: green;'>Major Name: " . htmlspecialchars($advisorData['major_name']) . "</p>";
                } else {
                    echo "<p style='color: red;'>Major Name NOT FOUND (Check 'majors' table).</p>";
                }
            } else {
                echo "<p style='color: red;'>NOT linked to 'advisors' table. Dashboard will be broken.</p>";
            }

            // Verify password 'password'
            if (password_verify('password', $user['password'])) {
                echo "<p style='color: green;'>Password for '$testUser' is correct (password).</p>";
            } else {
                echo "<p style='color: red;'>Password for '$testUser' is INCORRECT.</p>";
            }
        } else {
            echo "<p style='color: red;'>Test user '$testUser' NOT FOUND.</p>";
        }
    } else {
        echo "<p style='color: red;'>No advisors found in the 'users' table.</p>";
        echo "<p>Please import the <strong>add_advisors_real_majors.sql</strong> file into your database.</p>";
    }
} catch (PDOException $e) {
    echo "<p style='color: red;'>Database Error: " . $e->getMessage() . "</p>";
}
