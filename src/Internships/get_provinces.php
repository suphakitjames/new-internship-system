<?php
require_once '../../config/database.php';

header('Content-Type: application/json');

$region_id = $_GET['region_id'] ?? '';

try {
    if ($region_id) {
        // Fetch provinces for specific region
        $stmt = $pdo->prepare("SELECT id, name_th FROM provinces WHERE region_id = ? ORDER BY name_th ASC");
        $stmt->execute([$region_id]);
    } else {
        // Fetch all provinces
        $stmt = $pdo->query("SELECT id, name_th FROM provinces ORDER BY name_th ASC");
    }
    
    $provinces = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($provinces);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
