<?php
// admin.php

// مسار ملف المتبرعين
$donorsFile = 'donors.txt';

// التحقق من إرسال النموذج
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // الحصول على البيانات من النموذج مع حماية من هجمات XSS
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $address = htmlspecialchars(trim($_POST['address']));

    // التحقق من صحة البيانات
    if (!empty($name) && !empty($email) && !empty($phone) && !empty($address)) {
        // تنسيق البيانات لتخزينها (يمكن استخدام CSV أو JSON)
        $donorData = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address
        ];

        // تحويل البيانات إلى صيغة JSON
        $donorJson = json_encode($donorData) . PHP_EOL;

        // حفظ البيانات في الملف
        file_put_contents($donorsFile, $donorJson, FILE_APPEND | LOCK_EX);

        $successMessage = "تمت إضافة المتبرع بنجاح!";
    } else {
        $errorMessage = "يرجى ملء جميع الحقول.";
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>لوحة إدارة المتبرعين</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef;
            padding: 20px;
        }
        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            max-width: 500px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input[type="text"], input[type="email"], input[type="tel"] {
            width: 100%;
            padding: 8px;
            margin: 6px 0 12px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .button {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .button:hover {
            background-color: #218838;
        }
        .message {
            text-align: center;
            margin-bottom: 20px;
            color: green;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>إضافة متبرع جديد</h2>

    <?php if (isset($successMessage)): ?>
        <p class="message"><?php echo $successMessage; ?></p>
    <?php endif; ?>

    <?php if (isset($errorMessage)): ?>
        <p class="message error"><?php echo $errorMessage; ?></p>
    <?php endif; ?>

    <form action="admin.php" method="POST">
        <label for="name">اسم المتبرع:</label>
        <input type="text" id="name" name="name" required>

        <label for="email">البريد الإلكتروني:</label>
        <input type="email" id="email" name="email" required>

        <label for="phone">رقم الهاتف:</label>
        <input type="tel" id="phone" name="phone" required>

        <label for="address">العنوان:</label>
        <input type="text" id="address" name="address" required>

        <button type="submit" class="button">إضافة المتبرع</button>
    </form>
</div>

</body>
</html>
