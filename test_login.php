<?php
require_once 'config/db.php';

$username = 'admin';
$password = 'admin123';

$stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
$stmt->execute([$username]);
$admin = $stmt->fetch();

if ($admin) {
    echo "Admin found: " . $admin['username'] . "\n";
    echo "Stored hash: " . $admin['password'] . "\n";
    if (password_verify($password, $admin['password'])) {
        echo "Password VERIFIED successfully!\n";
    } else {
        echo "Password verification FAILED.\n";
    }
} else {
    echo "Admin not found.\n";
}
?>