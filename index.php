<?php
// index.php
session_start();

// إذا كان المستخدم مسجلاً دخوله بالفعل، يتم توجيهه إلى صفحته الخاصة
if (isset($_SESSION['username'])) {
    switch ($_SESSION['role']) {
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
            // إذا كان الدور غير معروف، يتم تسجيل الخروج
            header("Location: logout.php");
            break;
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>بنك الدم - مستشفى عين الكبيرة</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            direction: rtl; 
            background-color: #f2f2f2; 
            margin: 0; 
            padding: 0;
        }
        .container { 
            width: 80%; 
            margin: 0 auto; 
            padding: 20px; 
            background-color: #fff; 
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .header, .footer {
            text-align: center;
            padding: 10px 0;
        }
        .header h1 {
            margin: 10px 0;
        }
        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .login-buttons, .search-box {
            margin: 20px 0;
        }
        .login-buttons a, .search-box a {
            display: inline-block;
            margin: 5px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .login-buttons a:hover, .search-box a:hover {
            background-color: #45a049;
        }
        .search-box form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .search-box input[type="text"] {
            padding: 8px;
            width: 200px;
            margin-bottom: 10px;
        }
        .search-box input[type="submit"] {
            padding: 8px 16px;
            background-color: #2196F3;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .search-box input[type="submit"]:hover {
            background-color: #0b7dda;
        }
        .info {
            text-align: center;
            margin: 20px 0;
        }
        .info p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- رأس الصفحة -->
        <div class="header">
            <h1>بنك الدم مستشفى عين الكبيرة</h1>
            <p>المؤسسة الإستشفائية العمومية عين الكبيرة</p>
            <p>Etablissement Public Hospitalier EPH Ain El Kebira</p>
            <p>العنوان: عين الكبيرة - سطيف</p>
        </div>
        
        <!-- محتوى الصفحة -->
        <div class="content">
            <!-- أزرار تسجيل الدخول -->
            <div class="login-buttons">
                <a href="login.php">دخول الأدمن</a>
                <a href="login.php">دخول الممرض</a>
                <a href="login.php">دخول الطبيب</a>
            </div>
            
            <!-- خانة البحث للزوار -->
            <div class="search-box">
                <form action="visitor.php" method="get">
                    <input type="text" name="search" placeholder="بحث عن متبرع" required>
                    <input type="submit" value="بحث">
                </form>
            </div>
            
            <!-- معلومات إضافية -->
            <div class="info">
                <p>بنك الدم مستشفى عين الكبيرة يرحب بكم جزاكم الله خيرا</p>
            </div>
        </div>
        
        <!-- ذيل الصفحة -->
        <div class="footer">
            <p>جميع الحقوق محفوظة لأطباء مستشفى عين الكبيرة © 2024</p>
        </div>
    </div>
</body>
</html>
