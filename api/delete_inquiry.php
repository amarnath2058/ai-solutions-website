<?php
header('Content-Type: application/json');
require_once '../config/db.php';

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'] ?? 0;

$stmt = $pdo->prepare("DELETE FROM inquiries WHERE id = ?");
$stmt->execute([$id]);

echo json_encode(['success' => true]);
?>