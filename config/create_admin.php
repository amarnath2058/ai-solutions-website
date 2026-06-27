<?php
require_once 'db.php';  


$username = 'admin';
$password = 'admin123';
$fullName = 'Admin User';
$email = 'admin@yourdomain.com';



$check = $pdo->prepare("SELECT id FROM admins WHERE username = ? OR email = ?");
$check->execute([$username, $email]);
if ($check->rowCount() > 0) {
    die("❌ Admin already exists. If you want to re-create, delete the existing row first.");
}

// Hash the password
$hash = password_hash($password, PASSWORD_DEFAULT);

// Insert into the admins table
$stmt = $pdo->prepare("INSERT INTO admins (username, password, full_name, email) VALUES (?, ?, ?, ?)");
$stmt->execute([$username, $hash, $fullName, $email]);

echo "✅ Admin created successfully!\n";
echo "Username: $username\n";
echo "Password: $password\n";
echo "⚠️  Delete this file now for security.\n";
?>
