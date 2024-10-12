<?php
session_start();

// تحميل بيانات المتبرعين من ملف
if (!isset($_SESSION['donors'])) {
    if (file_exists('donors.txt')) {
        $donorsData = file_get_contents('donors.txt');
        $donors = json_decode($donorsData, true);
        $_SESSION['donors'] = $donors ? $donors : [];
    } else {
        $_SESSION['donors'] = [];
    }
}

// إضافة متبرع جديد
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_donor'])) {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $birthDate = $_POST['birth_date'];
    $bloodType = $_POST['blood_type'];
    $rhFactor = $_POST['rh_factor'];

    $newDonor = [
        'name' => $name,
        'surname' => $surname,
        'birth_date' => $birthDate,
        'blood_type' => $bloodType,
        'rh_factor' => $rhFactor,
        'last_donation_date' => null // أو يمكنك إضافة قيمة افتراضية
    ];

    $_SESSION['donors'][] = $newDonor;

    // حفظ البيانات في ملف
    file_put_contents('donors.txt', json_encode($_SESSION['donors']));

    // إعادة التوجيه لتجنب إعادة إرسال البيانات
    header("Location: admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>صفحة الإدارة</title>
    <style>
        body { font-family: Arial, sans-serif; direction: rtl; background-color: #f2f2f2; }
        h1 { color: #4CAF50; }
        a { text-decoration: none; color: #fff; background-color: #4CAF50; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>مرحبًا بك، <?php echo $_SESSION['username']; ?> (الإداري)</h1>
    <p><a href="logout.php">تسجيل الخروج</a></p>

    <h2>إضافة متبرع</h2>
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
            <option value="">اختر زمرة الريزوس</option>
            <option value="+">موجب</option>
            <option value="-">سالب</option>
        </select>
        <input type="submit" name="add_donor" value="إضافة">
    </form>
</body>
</html>
