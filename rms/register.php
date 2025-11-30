<?php
require_once 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $photo_name = null;

    // رفع الصورة
    if (!empty($_FILES['photo']['name'])) {
        $upload_dir = 'uploads/';
        $file_ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $photo_name = uniqid() . '.' . strtolower($file_ext);
        $target = $upload_dir . $photo_name;

        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
            $message = "فشل رفع الصورة.";
        }
    }

    if (empty($message)) {
        try {
            $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role, photo) VALUES (?, ?, ?, 'user', ?)");
            $stmt->execute([$username, $email, $hashed_pass, $photo_name]);
            $message = "تم التسجيل بنجاح! يمكنك الآن تسجيل الدخول.";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $message = "اسم المستخدم أو البريد الإلكتروني مستخدم مسبقًا.";
            } else {
                $message = "خطأ: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تسجيل جديد</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="form-container">
        <h2>إنشاء حساب جديد</h2>
        <?php if ($message): ?>
            <p class="<?= strpos($message, 'فشل') !== false ? 'error' : 'success' ?>"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="username" placeholder="اسم المستخدم" required>
            <input type="email" name="email" placeholder="البريد الإلكتروني" required>
            <input type="password" name="password" placeholder="كلمة المرور" required>
            <input type="file" name="photo" accept="image/*">
            <button type="submit">تسجيل</button>
        </form>
        <p>لديك حساب؟ <a href="login.php">سجّل الدخول</a></p>
    </div>
</body>
</html>