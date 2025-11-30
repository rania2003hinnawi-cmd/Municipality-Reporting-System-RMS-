<?php
require_once '../db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}
?>

<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>لوحة التحكم (الأدمن)</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <h2>لوحة تحكم المدير</h2>
        <nav>
            <ul>
                <li><a href="view_complaints.php">عرض جميع الشكاوي</a></li>
                <li><a href="view_users.php">إدارة المستخدمين</a></li>
                <li><a href="../logout.php">خروج</a></li>
            </ul>
        </nav>
    </div>
</body>
</html>