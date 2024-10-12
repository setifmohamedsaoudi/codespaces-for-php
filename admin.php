<?php
session_start();

// تأكد من تسجيل دخول الأدمن
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// تحديد مسارات ملفات المتبرعين
$donorsFileTxt = 'donors.txt';
$donorsFileJson = 'donors.json';

// تحميل بيانات المتبرعين من الملفات
$donors = [];

// تحميل من donors.txt
if (file_exists($donorsFileTxt)) {
    $donorsDataTxt = file_get_contents($donorsFileTxt);
    $donorsTxt = json_decode($donorsDataTxt, true);
    if ($donorsTxt !== null) {
        $donors = array_merge($donors, $donorsTxt);
    }
}

// تحميل من donors.json
if (file_exists($donorsFileJson)) {
    $donorsDataJson = file_get_contents($donorsFileJson);
    $donorsJson = json_decode($donorsDataJson, true);
    if ($donorsJson !== null) {
        $donors = array_merge($donors, $donorsJson);
    }
}

// إزالة التكرارات بناءً على الاسم واللقب
$uniqueDonors = [];
$existing = [];
foreach ($donors as $donor) {
    $key = strtolower($donor['name'] . '|' . $donor['surname']);
    if (!in_array($key, $existing)) {
        $uniqueDonors[] = $donor;
        $existing[] = $key;
    }
}
$donors = $uniqueDonors;

// إضافة متبرع جديد
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_donor'])) {
    // استلام بيانات المتبرع من النموذج
    $name = trim($_POST['name']);
    $surname = trim($_POST['surname']);
    $birthDate = $_POST['birth_date'];
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $bloodType = $_POST['blood_type'];
    $rhFactor = $_POST['rh_factor'];

    // التحقق من صحة البيانات المدخلة
    if ($name === '' || $surname === '' || $birthDate === '' || $address === '' || $phone === '' || $bloodType === '' || $rhFactor === '') {
        $message = "يرجى ملء جميع الحقول.";
    } else {
        // إنشاء مصفوفة للمتبرع الجديد
        $newDonor = [
            'name' => htmlspecialchars($name, ENT_QUOTES, 'UTF-8'),
            'surname' => htmlspecialchars($surname, ENT_QUOTES, 'UTF-8'),
            'birth_date' => $birthDate,
            'address' => htmlspecialchars($address, ENT_QUOTES, 'UTF-8'),
            'phone' => htmlspecialchars($phone, ENT_QUOTES, 'UTF-8'),
            'blood_type' => $bloodType,
            'rh_factor' => $rhFactor,
            'last_donation_date' => null
        ];

        // إضافة المتبرع إلى القائمة
        $donors[] = $newDonor;

        // إزالة التكرارات مرة أخرى بعد الإضافة
        $uniqueDonors = [];
        $existing = [];
        foreach ($donors as $donor) {
            $key = strtolower($donor['name'] . '|' . $donor['surname']);
            if (!in_array($key, $existing)) {
                $uniqueDonors[] = $donor;
                $existing[] = $key;
            }
        }
        $donors = $uniqueDonors;

        // حفظ البيانات في ملف المتبرعين
        $saveTxt = file_put_contents($donorsFileTxt, json_encode($donors, JSON_PRETTY_PRINT));
        $saveJson = file_put_contents($donorsFileJson, json_encode($donors, JSON_PRETTY_PRINT));

        if ($saveTxt !== false && $saveJson !== false) {
            $message = "تم إضافة المتبرع بنجاح.";
            // إعادة التوجيه لتجنب إعادة إرسال البيانات
            header("Location: admin.php");
            exit();
        } else {
            $message = "حدث خطأ أثناء حفظ البيانات.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>صفحة الإدارة</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            direction: rtl; 
            background-color: #f2f2f2; 
            padding: 20px;
        }
        h1 { color: #4CAF50; text-align: center; }
        a { 
            text-decoration: none; 
            color: #fff; 
            background-color: #4CAF50; 
            padding: 10px 15px; 
            border-radius: 5px; 
            display: inline-block;
            margin-bottom: 20px;
        }
        form { 
            background-color: #fff; 
            padding: 20px; 
            border-radius: 5px; 
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); 
            max-width: 500px; 
            margin: 0 auto 30px;
        }
        input[type="text"], input[type="date"], select, input[type="tel"] {
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
            border-radius: 5px;
            font-size: 16px;
        }
        input[type="submit"]:hover { 
            background-color: #45a049; 
        }
        .message { 
            text-align: center; 
            color: green; 
            margin-bottom: 20px;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            background-color: #fff;
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 12px; 
            text-align: center; 
        }
        th { 
            background-color: #4CAF50; 
            color: white; 
        }
        tr:nth-child(even) {background-color: #f9f9f9;}
    </style>
</head>
<body>
    <h1>صفحة الإدارة</h1>
    <p style="text-align:center;"><a href="logout.php">تسجيل الخروج</a></p>

    <?php if ($message): ?>
        <p class="message"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST">
        <h2>إضافة متبرع جديد</h2>
        <input type="text" name="name" placeholder="الاسم" required>
        <input type="text" name="surname" placeholder="اللقب" required>
        <input type="date" name="birth_date" placeholder="تاريخ الميلاد" required>
        <input type="text" name="address" placeholder="العنوان" required>
        <input type="tel" name="phone" placeholder="رقم الهاتف" required>
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
        <input type="submit" name="add_donor" value="إضافة المتبرع">
    </form>

    <h2>قائمة المتبرعين</h2>
    <?php if (empty($donors)): ?>
        <p style="text-align:center;">لا توجد متبرعين مسجلين بعد.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>اللقب</th>
                    <th>تاريخ الميلاد</th>
                    <th>العنوان</th>
                    <th>رقم الهاتف</th>
                    <th>الزمرة الدموية</th>
                    <th>زمرة الريزوس</th>
                    <th>تاريخ آخر تبرع</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($donors as $donor): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($donor['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($donor['surname'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($donor['birth_date'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($donor['address'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($donor['phone'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($donor['blood_type'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($donor['rh_factor'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($donor['last_donation_date'] ?? 'غير محدد', ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
