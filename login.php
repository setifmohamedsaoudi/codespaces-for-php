<?php
// login.php
session_start();

// التحقق من إذا كان الإدمن مسجل دخوله بالفعل
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: admin.php");
    exit;
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // التحقق من بيانات الإدمن
    if ($username === 'admin' && $password === 'admin') {
        $_SESSION['loggedin'] = true;
        header("Location: admin.php");
        exit;
    } else {
        $error = "اسم المستخدم أو كلمة المرور غير صحيحة.";
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- أضف هذا السطر -->
    <title>تسجيل دخول الإدمن</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>تسجيل دخول الإدمن</h2>
        <?php if ($error): ?>
            <div class="message error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="post" action="">
            <label for="username">اسم المستخدم:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">كلمة المرور:</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" value="تسجيل الدخول">
        </form>
    </div>
</body>
</html>
