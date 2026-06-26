<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../config/db.php';
require_once 'send_email.php';

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'] ?? 0;
$message = $data['message'] ?? '';

try {
    $stmt = $pdo->prepare("SELECT * FROM inquiries WHERE id = ?");
    $stmt->execute([$id]);
    $inquiry = $stmt->fetch();
    
    if (!$inquiry) {
        echo json_encode(['success' => false, 'message' => 'Inquiry not found']);
        exit;
    }
    
    $update = $pdo->prepare("UPDATE inquiries SET status = 'replied' WHERE id = ?");
    $update->execute([$id]);
    
    $emailBody = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; }
            .container { padding: 20px; }
            .header { background: #ff6b35; color: white; padding: 15px; text-align: center; }
            .content { padding: 20px; background: #f9f9f9; }
            .reply { background: white; padding: 15px; border-left: 4px solid #ff6b35; margin: 15px 0; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>AI Solutions</h2>
            </div>
            <div class='content'>
                <h3>Dear {$inquiry['full_name']},</h3>
                <p>Thank you for contacting AI Solutions. Here is our response:</p>
                <div class='reply'>
                    <strong>Our Response:</strong><br>
                    " . nl2br(htmlspecialchars($message)) . "
                </div>
                <p>Best regards,<br><strong>AI Solutions Team</strong></p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    $emailSent = sendEmail($inquiry['email'], "Reply to your inquiry - AI Solutions", $emailBody);
    
    echo json_encode([
        'success' => true,
        'message' => 'Reply sent to ' . $inquiry['email']
    ]);
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>