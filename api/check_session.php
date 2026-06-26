<?php
header('Content-Type: application/json');
require_once '../config/db.php';

if (isset($_SESSION['admin_id'])) {
    echo json_encode([
        'success' => true, 
        'logged_in' => true,
        'user' => [
            'id' => $_SESSION['admin_id'],
            'username' => $_SESSION['admin_username'],
            'name' => $_SESSION['admin_name']
        ]
    ]);
} else {
    echo json_encode(['success' => true, 'logged_in' => false]);
}
?>