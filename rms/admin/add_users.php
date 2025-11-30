<?php
require_once '../db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];
    $photo_name = null;

    if (!empty($_FILES['photo']['name'])) {
        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $photo_name = uniqid() . '.' . strtolower($ext);
        move_uploaded_file($_FILES['photo']['tmp_name'], "../uploads/$photo_name");
    }

    try {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role, photo) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$username, $email, $hashed, $role, $photo_name]);
        $message = "تم إضافة المستخدم بنجاح!";
    } catch (PDOException $e) {
        $message = "خطأ: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>إضافة مستخدم</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="form-container">
        <h2>إضافة مستخدم جديد</h2>
        <?php if ($message): ?>
            <p class="success"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="username" placeholder="اسم المستخدم" required>
            <input type="email" name="email" placeholder="البريد الإلكتروني" required>
            <input type="password" name="password" placeholder="كلمة المرور" required>
            <select name="role" required>
                <option value="user">مستخدم عادي</option>
                <option value="admin">مدير</option>
            </select>
            <input type="file" name="photo" accept="image/*">
            <button type="submit">إضافة</button>
        </form>
        <a href="view_users.php">العودة إلى قائمة المستخدمين</a>
    </div>
</body>
</html>