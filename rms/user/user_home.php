<?php
require_once '../db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('Location: ../login.php');
    exit;
}
?>

<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>لوحة المستخدم</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <h2>مرحبًا، <?= htmlspecialchars($_SESSION['username']) ?>!</h2>
        <nav>
            <a href="add_complaint.php">إضافة شكوى جديدة</a> |
            <a href="my_complaints.php">شكاويي</a> |
            <a href="../logout.php">خروج</a>
        </nav>
    </div>
</body>
</html>