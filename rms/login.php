<?php
require_once 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header('Location: admin/admin_dashboard.php');
            } else {
                header('Location: user/user_home.php');
            }
            exit;
        } else {
            $message = "اسم المستخدم أو كلمة المرور غير صحيحة.";
        }
    } catch (PDOException $e) {
        $message = "خطأ في تسجيل الدخول.";
    }
}
?>

<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="form-container">
        <h2>تسجيل الدخول</h2>
        <?php if ($message): ?>
            <p class="error"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="اسم المستخدم" required>
            <input type="password" name="password" placeholder="كلمة المرور" required>
            <button type="submit">دخول</button>
        </form>
        <p>ليس لديك حساب؟ <a href="register.php">أنشئ حسابًا</a></p>
    </div>
</body>
</html>