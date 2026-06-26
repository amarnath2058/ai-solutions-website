<?php
require_once '../config/db.php';

$stmt = $pdo->query("SELECT * FROM inquiries ORDER BY submitted_at DESC");
$inquiries = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="inquiries_'.date('Y-m-d').'.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['ID', 'Date', 'Name', 'Email', 'Phone', 'Company', 'Country', 'Job Title', 'Message', 'Status']);

foreach ($inquiries as $row) {
    fputcsv($output, [
        $row['id'],
        $row['submitted_at'],
        $row['full_name'],
        $row['email'],
        $row['phone'],
        $row['company'],
        $row['country'],
        $row['job_title'],
        $row['job_details'],
        $row['status'] ?? 'pending'
    ]);
}
fclose($output);
?>