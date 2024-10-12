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
            text-decoration: none;
            display: inline-block;
        }
        .button:hover {
            background-color: #0056b3;
        }
        /* تنسيق إضافي للنموذج لجعله أصغر */
        form {
            display: inline-block;
            text-align: left;
            width: 100%;
        }
        form label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        form input[type="text"],
        form input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        form input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-top: 15px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        form input[type="submit"]:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>صفحة الدخول</h1>
    <form action="login.php" method="POST">
        <label for="username">اسم المستخدم:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">كلمة المرور:</label>
        <input type="password" id="password" name="password" required>

        <input type="submit" value="دخول">
    </form>

    <div class="hospital-info">
        <p><strong>معلومات المستشفى:</strong></p>
        <p>اسم المستشفى: <span>مستشفى عين الكبيرة</span></p>
        <p>العنوان: <span>عين الكبيرة</span></p>
        <p>الهاتف: <span>+213 36 89 63 13</span></p>
    </div>

    <a href="visitors.php" class="button">ابحث عن المتبرعين</a>

    <div class="footer">
        <p>&copy; 2024 جميع الحقوق محفوظة.</p>
    </div>
</div>

</body>
</html>
