<?php
session_start();
// التحقق مما إذا كان المستخدم قد سجل الدخول كأدمن
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php?role=admin");
    exit();
}

// إعداد مصفوفة للمتبرعين (بدون قاعدة بيانات)
$donors = isset($_SESSION['donors']) ? $_SESSION['donors'] : [];

// إضافة متبرع جديد
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_donor'])) {
    $donor = [
        'name' => $_POST['name'],
        'surname' => $_POST['surname'],
        'birth_date' => $_POST['birth_date'],
        'blood_type' => $_POST['blood_type'],
        'rh_factor' => $_POST['rh_factor'],
        'last_donation' => null
    ];
    $donors[] = $donor;
    $_SESSION['donors'] = $donors; // تحديث الجلسة
    $message = "تم إضافة المتبرع بنجاح.";
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>صفحة الأدمن</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            direction: rtl; 
            background-color: #f2f2f2; 
        }
        h1 { color: #4CAF50; }
        a { text-decoration: none; color: #fff; background-color: #4CAF50; padding: 10px; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
    </style>
</head>
<body>
    <h1>مرحبًا بك، <?php echo $_SESSION['username']; ?> (الأدمن)</h1>
    <p><a href="logout.php">تسجيل الخروج</a></p>
    
    <h2>إضافة متبرع جديد</h2>
    <form method="POST">
        <input type="text" name="name" placeholder="الاسم" required>
        <input type="text" name="surname" placeholder="اللقب" required>
        <input type="date" name="birth_date" placeholder="تاريخ الميلاد" required>
        <select name="blood_type" required>
            <option value="">اختر الزمرة الدموية</option>
            <option value="A">A</option>
            <option value="B">B</option>
            <option value="AB">AB</option>
            <option value="O">O</option>
        </select>
        <select name="rh_factor" required>
            <option value="">اختر زومة الريزوس</option>
            <option value="+">موجب</option>
            <option value="-">سالب</option>
        </select>
        <input type="submit" name="add_donor" value="إضافة المتبرع">
    </form>
    <?php if (isset($message)) echo "<p>$message</p>"; ?>

    <h2>قائمة المتبرعين</h2>
    <table>
        <thead>
            <tr>
                <th>الاسم</th>
                <th>اللقب</th>
                <th>تاريخ الميلاد</th>
                <th>الزمرة الدموية</th>
                <th>زمرة الريزوس</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($donors as $donor): ?>
                <tr>
                    <td><?php echo $donor['name']; ?></td>
                    <td><?php echo $donor['surname']; ?></td>
                    <td><?php echo $donor['birth_date']; ?></td>
                    <td><?php echo $donor['blood_type']; ?></td>
                    <td><?php echo $donor['rh_factor']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
