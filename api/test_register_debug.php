<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "Starting...<br>";

require_once __DIR__ . '/../config/db.php';
echo "DB loaed...<br>";

$data = json_decode(file_get_contents('php://input'), true);
echo "Data received...<br>";

$fullName = $data['fullName'] ?? '';
$email = $data['email'] ?? '';
$username = $data['username'] ?? '';
$password = $data['password'] ?? '';

echo "Processing...<br>";

try {
    $check = $pdo->prepare("SELECT id FROM admins WHERE username = ? OR email = ?");
    $check->execute([$username, $email]);
    
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO admins (username, password, full_name, email) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username, $hashedPassword, $fullName, $email]);
    
    echo "Success!";
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>