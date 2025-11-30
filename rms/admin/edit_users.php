<?php
require_once '../db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

$user_id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    die("المستخدم غير موجود.");
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];
    $photo_name = $user['photo']; // الاحتفاظ باسم الصورة القديمة

    if (!empty($_FILES['photo']['name'])) {
        // حذف الصورة القديمة إن وُجدت
        if ($user['photo'] && file_exists("../uploads/" . $user['photo'])) {
            unlink("../uploads/" . $user['photo']);
        }
        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $photo_name = uniqid() . '.' . strtolower($ext);
        move_uploaded_file($_FILES['photo']['tmp_name'], "../uploads/$photo_name");
    }

    $sql = "UPDATE users SET username = ?, email = ?, role = ?";
    $params = [$username, $email, $role];

    // تحديث كلمة المرور فقط إذا أُدخلت
    if (!empty($_POST['password'])) {
        $sql .= ", password = ?";
        $params[] = password_hash($_POST['password'], PASSWORD_DEFAULT);
    }

    $sql .= ", photo = ? WHERE user_id = ?";
    $params[] = $photo_name;
    $params[] = $user_id;

    try {
        $pdo->prepare($sql)->execute($params);
        $message = "تم تحديث المستخدم بنجاح!";
        // تحديث البيانات المعروضة
        $user['username'] = $username;
        $user['email'] = $email;
        $user['role'] = $role;
        $user['photo'] = $photo_name;
    } catch (PDOException $e) {
        $message = "خطأ: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تعديل مستخدم</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="form-container">
        <h2>تعديل المستخدم: <?= htmlspecialchars($user['username']) ?></h2>
        <?php if ($message): ?>
            <p class="success"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            <input type="password" name="password" placeholder="اترك فارغًا إذا لم ترد تغيير كلمة المرور">
            <select name="role" required>
                <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>مستخدم عادي</option>
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>مدير</option>
            </select>
            <input type="file" name="photo" accept="image/*">
            <?php if ($user['photo']): ?>
                <div>الصورة الحالية: <img src="../uploads/<?= htmlspecialchars($user['photo']) ?>" width="60"></div>
            <?php endif; ?>
            <button type="submit">تحديث</button>
        </form>
        <a href="view_users.php">العودة</a>
    </div>
</body>
</html>