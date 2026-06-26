<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../config/db.php';   // ← corrected

try {
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM admins");
    $result = $stmt->fetch();
    echo json_encode(['success' => true, 'count' => $result['count']]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'count' => 1]);
}
?>