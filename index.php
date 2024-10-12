<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>الصفحة الرئيسية</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            direction: rtl;
            background-color: #f2f2f2;
            text-align: center;
        }
        h1 { color: #4CAF50; }
        h2 { color: #333; }
        .role-button {
            padding: 10px 20px;
            margin: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .role-button:hover { background-color: #45a049; }
        footer { margin-top: 20px; font-size: 12px; color: #777; }
        .info { margin: 20px 0; }
        input[type="text"], select {
            padding: 10px;
            margin: 10px 0;
            width: 200px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>مرحبًا بكم في بنك الدم</h1>
        
        <h2>تسجيل الدخول</h2>
        <a href="login.php?role=admin"><button class="role-button">تسجيل دخول الأدمن</button></a>
        <a href="login.php?role=nurse"><button class="role-button">تسجيل دخول الممرض</button></a>
        <a href="login.php?role=doctor"><button class="role-button">تسجيل دخول الطبيب</button></a>

        <h2>بحث عن متبرع</h2>
        <form action="visitor.php" method="get">
            <input type="text" name="blood_type" placeholder="الزمرة الدموية" required>
            <input type="submit" value="بحث">
        </form>

        <div class="info">
            <h2>معلومات المستشفى وبنك الدم</h2>
            <p><strong>المؤسسة الإستشفائية العمومية عين الكبيرة</strong><br>
            EPH Ain El Kebira<br>
            مؤسسة إستشفائية عمومية<br>
            Etablissement Public Hospitalier EPH</p>
            <p><strong>العنوان:</strong> عين الكبيرة - سطيف<br>
            بنك الدم مستشفى عين الكبيرة يرحب بكم جزاكم الله خيرًا.</p>
        </div>
    </div>

    <footer>
        جميع الحقوق محفوظة لأطباء مستشفى عين الكبيرة
    </footer>
</body>
</html>
