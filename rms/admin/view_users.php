<?php
require_once '../db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

$users = $pdo->query("SELECT * FROM users ORDER BY user_id")->fetchAll();
?>

<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>عرض المستخدمين</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <h2>قائمة المستخدمين</h2>
        <a href="add_user.php">+ إضافة مستخدم جديد</a>
        <table border="1" class="users-table">
            <thead>
                <tr>
                    <th>الرقم</th>
                    <th>اسم المستخدم</th>
                    <th>البريد</th>
                    <th>الدور</th>
                    <th>الصورة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= $u['user_id'] ?></td>
                    <td><?= htmlspecialchars($u['username']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= $u['role'] ?></td>
                    <td>
                        <?php if ($u['photo']): ?>
                            <img src="../uploads/<?= htmlspecialchars($u['photo']) ?>" width="50">
                        <?php else: ?>
                            —
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="edit_user.php?id=<?= $u['user_id'] ?>">تعديل</a> |
                        <a href="delete_user.php?id=<?= $u['user_id'] ?>" onclick="return confirm('هل أنت متأكد؟')">حذف</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>