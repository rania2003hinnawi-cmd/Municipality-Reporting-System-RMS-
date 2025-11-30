<?php
require_once '../db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('Location: ../login.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $desc = trim($_POST['description']);
    $photo_name = null;

    if (!empty($_FILES['photo']['name'])) {
        $upload_dir = '../uploads/';
        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $photo_name = uniqid('comp_') . '.' . strtolower($ext);
        $target = $upload_dir . $photo_name;

        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
            $message = "فشل رفع الصورة.";
        }
    }

    if (empty($message)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO complaints (user_id, title, description, photo) VALUES (?, ?, ?, ?)");
            $stmt->execute([$_SESSION['user_id'], $title, $desc, $photo_name]);
            $message = "تم إرسال الشكوى بنجاح!";
        } catch (Exception $e) {
            $message = "حدث خطأ: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>إضافة شكوى</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="form-container">
        <h2>إضافة شكوى جديدة</h2>
        <?php if ($message): ?>
            <p class="<?= strpos($message, 'فشل') !== false ? 'error' : 'success' ?>"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="عنوان الشكوى" required>
            <textarea name="description" placeholder="وصف المشكلة" required></textarea>
            <input type="file" name="photo" accept="image/*">
            <button type="submit">إرسال الشكوى</button>
        </form>
        <a href="user_home.php">العودة إلى الرئيسية</a>
    </div>
</body>
</html>