<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صفحة الدخول</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 100px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .hospital-info {
            margin-top: 30px;
            font-size: 18px;
            color: #555;
        }
        .footer {
            margin-top: 50px;
            font-size: 12px;
            color: #aaa;
        }
        .button {
            margin-top: 20px;
            padding: 10px 15px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>مرحبا بك في صفحة الدخول</h1>
    <form action="login.php" method="POST">
        <label for="username">اسم المستخدم:</label><br>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">كلمة المرور:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="دخول">
    </form>

    <div class="hospital-info">
        <p>معلومات المستشفى:</p>
        <p>اسم المستشفى: [اسم المستشفى]</p>
        <p>العنوان: [عنوان المستشفى]</p>
        <p>الهاتف: [رقم هاتف المستشفى]</p>
    </div>

    <a href="visitors.php" class="button">ابحث عن المتبرعين</a>

    <div class="footer">
        <p>&copy; 2024 جميع الحقوق محفوظة لأطباء مستشفى عين الكبيرة.</p>
    </div>
</div>

</body>
</html>
