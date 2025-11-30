<?php
require_once '../db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

$user_id = (int)$_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $user_id) {
    // حذف الصورة المرتبطة إن وُجدت
    $stmt = $pdo->prepare("SELECT photo FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $photo = $stmt->fetchColumn();

    if ($photo && file_exists("../uploads/$photo")) {
        unlink("../uploads/$photo");
    }

    // الحذف من قاعدة البيانات (ON DELETE CASCADE سيحذف الشكاوي المرتبطة تلقائيًا)
    $pdo->prepare("DELETE FROM users WHERE user_id = ?")->execute([$user_id]);
}

header('Location: view_users.php');
exit;
?>