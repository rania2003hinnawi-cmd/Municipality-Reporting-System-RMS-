<?php
// db.php – الاتصال بقاعدة البيانات MySQL عبر PDO

// نبدأ السيشن هنا عشان كل الصفحات اللي تعمل require لهذا الملف يكون عندها session جاهز
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// إعدادات الاتصال
$host    = '127.0.0.1';   // أو localhost
$dbname  = 'rms_db';      // اسم قاعدة البيانات (هننشئها بعد شوي في phpMyAdmin)
$user    = 'root';        // المستخدم الافتراضي في XAMPP
$password = '';           // كلمة مرور root في XAMPP تكون فاضية غالبًا
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // يرمي استثناء عند الخطأ
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // يرجّع النتائج كمصفوفة مرتبطة
    PDO::ATTR_EMULATE_PREPARES   => false,                  // يخلّي الـ prepared statements حقيقية
];

try {
    $pdo = new PDO($dsn, $user, $password, $options);
} catch (PDOException $e) {
    // لو صار خطأ في الاتصال نوقف التنفيذ ونطبع رسالة واضحة
    die('فشل الاتصال بقاعدة البيانات: ' . $e->getMessage());
}
