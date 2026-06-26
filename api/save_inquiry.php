<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Adjust the path to your db.php:
require_once __DIR__ . '/../config/db.php';
// OR require_once __DIR__ . '/../db.php';

$data = json_decode(file_get_contents('php://input'), true);

$full_name = $data['name'] ?? '';
$email = $data['email'] ?? '';
$phone = $data['phone'] ?? '';
$company = $data['company'] ?? '';
$country = $data['country'] ?? '';
$job_title = $data['jobTitle'] ?? '';
$job_details = $data['message'] ?? '';

if (empty($full_name) || empty($email) || empty($phone)) {
    echo json_encode(['success' => false, 'message' => 'Name, Email and Phone are required']);
    exit;
}

try {
    // No submitted_at column – created_at will be set automatically by MySQL
    $sql = "INSERT INTO inquiries (full_name, email, phone, company, country, job_title, job_details, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$full_name, $email, $phone, $company, $country, $job_title, $job_details]);
    echo json_encode(['success' => true, 'message' => 'Inquiry saved successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>