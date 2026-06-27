<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');


require_once __DIR__ . '/../config/db.php';


try {
    
    $sql = "SELECT 
                id, 
                full_name, 
                email, 
                phone, 
                company, 
                country, 
                job_title, 
                job_details, 
                status, 
                created_at AS submitted_at 
            FROM inquiries 
            ORDER BY created_at DESC";
    $stmt = $pdo->query($sql);
    $inquiries = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'data' => $inquiries]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
