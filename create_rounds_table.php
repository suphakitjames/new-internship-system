<?php
require_once 'config/database.php';

try {
    $sql = "CREATE TABLE IF NOT EXISTS internship_rounds (
        id INT AUTO_INCREMENT PRIMARY KEY,
        round_name VARCHAR(100) NOT NULL,
        start_date DATE NOT NULL,
        end_date DATE NOT NULL,
        status ENUM('active', 'inactive', 'closed') DEFAULT 'active',
        description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    $pdo->exec($sql);
    echo "Table 'internship_rounds' created successfully.";
} catch (PDOException $e) {
    echo "Error creating table: " . $e->getMessage();
}
