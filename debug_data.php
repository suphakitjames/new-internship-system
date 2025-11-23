<?php
require_once 'config/database.php';

echo "Checking Regions:\n";
$stmt = $pdo->query("SELECT * FROM regions");
$regions = $stmt->fetchAll();
foreach ($regions as $r) {
    echo "ID: {$r['id']}, Name: {$r['name_th']}\n";
}

echo "\nChecking Provinces (Sample):\n";
$stmt = $pdo->query("SELECT * FROM provinces LIMIT 10");
$provinces = $stmt->fetchAll();
foreach ($provinces as $p) {
    echo "ID: {$p['id']}, Name: {$p['name_th']}, Region ID: {$p['region_id']}\n";
}

echo "\nChecking if any provinces exist for Region ID 3 (South - assuming):\n";
// Let's check counts for each region
$stmt = $pdo->query("SELECT region_id, COUNT(*) as count FROM provinces GROUP BY region_id");
$counts = $stmt->fetchAll();
foreach ($counts as $c) {
    echo "Region ID: {$c['region_id']}, Count: {$c['count']}\n";
}
?>
