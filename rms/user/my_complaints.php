<?php
require_once '../db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('Location: ../login.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM complaints WHERE user_id = ? ORDER BY date_added DESC");
$stmt->execute([$_SESSION['user_id']]);
$complaints = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>شكاويي</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <h2>شكاويي المُرسلة</h2>
        <?php if (empty($complaints)): ?>
            <p>ليس لديك أي شكاوي حتى الآن.</p>
        <?php else: ?>
            <table border="1" class="complaints-table">
                <thead>
                    <tr>
                        <th>العنوان</th>
                        <th>الوصف</th>
                        <th>الحالة</th>
                        <th>التاريخ</th>
                        <th>الصورة</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($complaints as $c): ?>
                    <tr>
                        <td><?= htmlspecialchars($c['title']) ?></td>
                        <td><?= htmlspecialchars($c['description']) ?></td>
                        <td><?= htmlspecialchars($c['status']) ?></td>
                        <td><?= date('Y-m-d H:i', strtotime($c['date_added'])) ?></td>
                        <td>
                            <?php if ($c['photo']): ?>
                                <img src="../uploads/<?= htmlspecialchars($c['photo']) ?>" width="80">
                            <?php else: ?>
                                لا يوجد
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        <br>
        <a href="user_home.php">العودة إلى الرئيسية</a>
    </div>
</body>
</html>