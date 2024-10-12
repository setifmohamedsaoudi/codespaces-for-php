<?php
session_start();

// تعريف بيانات المستخدمين
$users = [
    'admin' => ['password' => 'admin', 'role' => 'admin'],
    'nurse' => ['password' => 'nurse', 'role' => 'nurse'],
    'doctor' => ['password' => 'doctor', 'role' => 'doctor'],
];

// التحقق من أن الدور محدد
$role = isset($_GET['role']) ? $_GET['role'] : '';

// التحقق من الدور الصحيح
if (!in_array($role, ['admin', 'nurse', 'doctor'])) {
    die("دور غير صالح.");
}

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // التحقق من بيانات الاعتماد
    if (isset($users[$username]) && $users[$username]['password'] === $password && $users[$username]['role'] === $role) {
        // تسجيل الدخول الناجح
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $users[$username]['role'];

        // إعادة التوجيه إلى الصفحة المناسبة بناءً على الدور
        switch ($users[$username]['role']) {
            case 'admin':
                header("Location: admin.php");
                break;
            case 'nurse':
                header("Location: nurse.php");
                break;
            case 'doctor':
                header("Location: doctor.php");
                break;
            default:
                header("Location: login.php");
                break;
        }
        exit();
    } else {
        $error_message = "اسم المستخدم أو كلمة المرور غير صحيحة.";
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
        }
        .container { 
            width: 300px; 
            margin: 100px auto; 
            padding: 20px; 
            background-color: #fff; 
            border-radius: 5px; 
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); 
        }
        h2 { text-align: center; }
        .message { color: red; text-align: center; }
        input[type="text"], input[type="password"] { 
            width: 100%; 
            padding: 10px; 
            margin: 10px 0; 
            box-sizing: border-box;
        }
        input[type="submit"] { 
            width: 100%; 
            padding: 10px; 
            background-color: #4CAF50; 
            border: none; 
            color: white; 
            cursor: pointer; 
        }
        input[type="submit"]:hover { 
            background-color: #45a049; 
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>تسجيل الدخول</h2>
        <?php if ($error_message): ?>
            <div class="message"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form method="post">
            <input type="text" name="username" placeholder="اسم المستخدم" required>
            <input type="password" name="password" placeholder="كلمة المرور" required>
            <input type="hidden" name="role" value="<?php echo $role; ?>">
            <input type="submit" value="تسجيل الدخول">
⬤
