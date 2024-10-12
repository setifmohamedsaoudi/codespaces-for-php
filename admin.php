<?php
// admin.php
session_start();

// التحقق من تسجيل الدخول وصلاحية الدور
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// مسارات ملفات JSON
$donorsFile = 'donors.json';
$requestsFile = 'requests.json';

// ... بقية كود admin.php كما في الإجابة السابقة ...

?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>إدارة المتبرعين بالدم</title>
    <!-- تضمين CSS كما في السابق -->
</head>
<body>
    <div class="container">
        <h2>الرجاء إدخال المعلومات الخاصة بالمترشحين</h2>
        <p>مرحبًا، <?php echo htmlspecialchars($_SESSION['username']); ?>! <a href="logout.php">تسجيل الخروج</a></p>
        <!-- بقية المحتوى كما في الإجابة السابقة -->
    </div>
</body>
</html>
