<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>صفحة البداية</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            direction: rtl;
            background-color: #f2f2f2;
            display: flex;
            justify-content: space-between;
            padding: 20px;
        }
        .login-container {
            width: 300px;
            background-color: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }
        .hospital-info {
            flex-grow: 1;
            margin-left: 20px;
        }
        h1 {
            color: #4CAF50;
            text-align: center;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        footer {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>تسجيل الدخول</h1>
        <form method="POST" action="login.php">
            <label for="username">اسم المستخدم:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">كلمة المرور:</label>
            <input type="password" id="password" name="password" required>
            
            <input type="submit" value="تسجيل الدخول">
        </form>
    </div>
    <div class="hospital-info">
        <h1>المؤسسة الإستشفائية العمومية عين الكبيرة</h1>
        <p>EPH Ain El Kebira</p>
        <p>مؤسسة إستشفائية عمومية</p>
        <p>Etablissement Public Hospitalier EPH</p>
        <p>العنوان: عين الكبيرة - سطيف</p>
        <p>بنك الدم مستشفى عين الكبيرة يرحب بكم جزاكم الله خيرا</p>
    </div>
    <footer>
        <p>جميع الحقوق محفوظة لأطباء مستشفى عين الكبيرة ©</p>
    </footer>
</body>
</html>
