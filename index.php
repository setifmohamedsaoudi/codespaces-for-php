<?php
session_start();

// تحميل بيانات المتبرعين من ملف
$donors = [];
if (file_exists('donors.txt')) {
    $donorsData = file_get_contents('donors.txt');
    $donors = json_decode($donorsData, true) ?? [];
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>الصفحة الرئيسية</title>
    <style>
        body { font-family: Arial, sans-serif; direction: rtl; background-color: #f2f2f2; }
        h1 { color: #4CAF50; }
        a { text-decoration: none; color: #fff; background-color: #4CAF50; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>المؤسسة الإستشفائية العمومية عين الكبيرة</h1>
    <h2>EPH Ain El Kebira</h2>
    <h3>مؤسسة إستشفائية عمومية</h3>
    <h4>Etablissement Public Hospitalier EPH</h4>
    <p>العنوان: عين الكبيرة - سطيف</p>
    <p>بنك الدم مستشفى عين الكبيرة يرحب بكم جزاكم الله خيرا.</p>

    <h2>تسجيل الدخول</h2>
    <form action="login.php" method="post">
        <input type="text" name="username" placeholder="اسم المستخدم" required>
        <input type="password" name="password" placeholder="كلمة السر" required>
        <input type="submit" value="دخول">
    </form>

    <h2>قائمة المتبرعين</h2>
    <?php if (empty($donors)): ?>
        <p>لا توجد متبرعين مسجلين.</p>
    <?php else: ?>
        <table border="1">
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>اللقب</th>
                    <th>تاريخ الميلاد</th>
                    <th>الزمرة الدموية</th>
                    <th>زمرة الريزوس</th>
                    <th>تاريخ آخر تبرع</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($donors as $donor): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($donor['name']); ?></td>
                        <td><?php echo htmlspecialchars($donor['surname']); ?></td>
                        <td><?php echo htmlspecialchars($donor['birth_date']); ?></td>
                        <td><?php echo htmlspecialchars($donor['blood_type']); ?></td>
                        <td><?php echo htmlspecialchars($donor['rh_factor']); ?></td>
                        <td><?php echo htmlspecialchars($donor['last_donation_date'] ?? 'غير محدد'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <h2>زيارة صفحة الزوار</h2>
    <p><a href="visitors.php">إضغط هنا لزيارة صفحة الزوار</a></p>
</body>
</html>
