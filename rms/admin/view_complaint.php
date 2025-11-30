<?php
require_once '../db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

// تحديث حالة شكوى (إذا جرى إرسال طلب تحديث)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complaint_id']) && isset($_POST['status'])) {
    $id = (int)$_POST['complaint_id'];
    $status = $_POST['status'];
    $pdo->prepare("UPDATE complaints SET status = ? WHERE complaint_id = ?")
        ->execute([$status, $id]);
}

$complaints = $pdo->query("
    SELECT c.*, u.username 
    FROM complaints c 
    JOIN users u ON c.user_id = u.user_id 
    ORDER BY c.date_added DESC
")->fetchAll();
?>

<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>عرض جميع الشكاوي</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <h2>جميع الشكاوي</h2>
        <table border="1" class="complaints-table">
            <thead>
                <tr>
                    <th>الرقم</th>
                    <th>المستخدم</th>
                    <th>العنوان</th>
                    <th>الوصف</th>
                    <th>الحالة</th>
                    <th>التاريخ</th>
                    <th>الصورة</th>
                    <th>تحديث الحالة</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($complaints as $c): ?>
                <tr>
                    <td><?= $c['complaint_id'] ?></td>
                    <td><?= htmlspecialchars($c['username']) ?></td>
                    <td><?= htmlspecialchars($c['title']) ?></td>
                    <td><?= htmlspecialchars($c['description']) ?></td>
                    <td><?= htmlspecialchars($c['status']) ?></td>
                    <td><?= date('Y-m-d H:i', strtotime($c['date_added'])) ?></td>
                    <td>
                        <?php if ($c['photo']): ?>
                            <img src="../uploads/<?= htmlspecialchars($c['photo']) ?>" width="80">
                        <?php else: ?>
                            —
                        <?php endif; ?>
                    </td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="complaint_id" value="<?= $c['complaint_id'] ?>">
                            <select name="status" onchange="this.form.submit()">
                                <option value="Pending" <?= $c['status'] === 'Pending' ? 'selected' : '' ?>>قيد الانتظار</option>
                                <option value="Seen" <?= $c['status'] === 'Seen' ? 'selected' : '' ?>>تم الاطلاع</option>
                                <option value="Fixed" <?= $c['status'] === 'Fixed' ? 'selected' : '' ?>>تم الحل</option>
                            </select>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>