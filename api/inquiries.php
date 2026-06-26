<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Inquiries - AI Solutions</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <div class="page-container">
        <h1>Inquiry Management</h1>
        <p>Inquiries page content here...</p>
        <a href="dashboard.html">← Back to Dashboard</a>
    </div>
</body>
</html>