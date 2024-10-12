<?php
session_start();

$users = [
    'admin' => 'admin',
    'nurse' => 'nurse',
    'doctor' => 'doctor'
];

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (isset($users[$username]) && $users[$username] === $password) {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $username; // استخدام اسم المستخدم كدور
        header("Location: {$username}.php"); // توجيه إلى الصفحة المناسبة
        exit();
    } else {
        $message = "اسم المستخدم أو كلمة السر غير صحيحة.";
    }
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            direction: rtl;
            background-color: #f2f2f2;
            text-align: center;
        }
        h1 { color: #4CAF50; }
        input[type="text"], input[type="password"] {
            padding: 10px;
            margin: 10px 0;
            width: 200px;
        }
        input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>تسجيل الدخول</h1>
    <?php if ($message): ?>
        <p style="color:red;"><?php echo $message; ?></p>
    <?php endif; ?>
    <form method="POST">
        <input type="text" name="username" placeholder="اسم المستخدم" required>
        <input type="password" name="password" placeholder="كلمة السر" required>
        <input type="submit" value="تسجيل الدخول">
    </form>
</body>
</html>
