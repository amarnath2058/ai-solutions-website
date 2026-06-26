<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/send_email.php';

$data = json_decode(file_get_contents('php://input'), true);
$fullName = $data['fullName'] ?? '';
$email = $data['email'] ?? '';
$username = $data['username'] ?? '';
$password = $data['password'] ?? '';

if (empty($fullName) || empty($email) || empty($username) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'All fields required']);
    exit;
}

try {
    $check = $pdo->prepare("SELECT id FROM admins WHERE username = ? OR email = ?");
    $check->execute([$username, $email]);
    if ($check->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Username or email already exists']);
        exit;
    }
    
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO admins (username, password, full_name, email) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username, $hashedPassword, $fullName, $email]);
    
    $emailBody = "
    <html>
    <body>
        <h2>Welcome $fullName!</h2>
        <p>Your admin account has been created.</p>
        <p><strong>Login Details:</strong></p>
        <p>Username: $username</p>
        <p>Password: $password</p>
        <p>Login URL: http://localhost/ai_solution/admin.html</p>
        <p>Please change your password after first login.</p>
        <p>Best regards,<br>AI Solutions Team</p>
    </body>
    </html>
    ";
    
    $emailSent = sendEmail($email, "Welcome to AI Solutions - Admin Account", $emailBody);
    
    echo json_encode([
        'success' => true, 
        'message' => 'Admin created successfully. ' . ($emailSent ? 'Email sent.' : 'Email failed - check Gmail settings')
    ]);
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>