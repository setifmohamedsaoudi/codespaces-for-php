<?php
// لا حاجة لجلسة هنا لأن الزوار ليسوا بحاجة لتسجيل الدخول

// تحديد مسارات ملفات المتبرعين
$donorsFileTxt = 'donors.txt';
$donorsFileJson = 'donors.json';

// وظيفة لتحميل بيانات المتبرعين من الملفات
function loadDonors($donorsFileTxt, $donorsFileJson) {
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

    return $uniqueDonors;
}

// تحميل بيانات المتبرعين
$donors = loadDonors($donorsFileTxt, $donorsFileJson);

// متغيرات للنتائج والرسائل
$searchResults = [];
$errorMessage = '';

// معالجة النموذج عند الإرسال
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_donor'])) {
    $bloodType = trim($_POST['blood_type']);
    $rhFactor = trim($_POST['rh_factor']);

    // التحقق من صحة المدخلات
    if ($bloodType === '' || $rhFactor === '') {
        $errorMessage = "يرجى اختيار الزمرة الدموية وزمرة الريزوس.";
    } else {
        // البحث عن المتبرعين حسب الزمرة الدموية وزمرة الريزوس
        foreach ($donors as $donor) {
            if (strcasecmp($donor['blood_type'], $bloodType) === 0 && strcasecmp($donor['rh_factor'], $rhFactor) === 0) {
                $searchResults[] = $donor;
            }
        }

        if (empty($searchResults)) {
            $errorMessage = "لا توجد نتائج مطابقة للبحث.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>البحث عن متبرعين</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            direction: rtl;
            background-color: #f2f2f2;
            padding: 20px;
        }
        h1 {
            color: #4CAF50;
            text-align: center;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: 0 auto 30px;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
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
            color: red;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            margin-bottom: 30px;
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
        a.request-phone {
            text-decoration: none;
            color: #fff;
            background-color: #2196F3;
            padding: 5px 10px;
            border-radius: 3px;
        }
        a.request-phone:hover {
            background-color: #0b7dda;
        }
        footer {
            text-align: center;
            margin-top: 20px;
            color: #555;
        }
    </style>
</head>
<body>
    <h1>البحث عن متبرعين</h1>

    <form method="POST">
        <label for="blood_type">الزمرة الدموية:</label>
        <select name="blood_type" id="blood_type" required>
            <option value="">اختر زمرة الدم</option>
            <option value="A">A</option>
            <option value="B">B</option>
            <option value="AB">AB</option>
            <option value="O">O</option>
        </select>

        <label for="rh_factor">زمرة الريزوس:</label>
        <select name="rh_factor" id="rh_factor" required>
            <option value="">اختر زمرة الريزوس</option>
            <option value="+">موجب</option>
            <option value="-">سالب</option>
        </select>

        <input type="submit" name="search_donor" value="بحث">
    </form>

    <?php if ($errorMessage): ?>
        <p class="message"><?php echo $errorMessage; ?></p>
    <?php endif; ?>

    <?php if (!empty($searchResults)): ?>
        <h3 style="text-align:center;">نتائج البحث:</h3>
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
                    <th>اطلب رقم الهاتف</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($searchResults as $donor): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($donor['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($donor['surname'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($donor['birth_date'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($donor['address'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($donor['phone'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($donor['blood_type'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($donor['rh_factor'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($donor['last_donation_date'] ?? 'غير محدد', ENT_QUOTES, 'UTF-8'); ?></td>
                        <td>
                            <a class="request-phone" href="request_phone.php?name=<?php echo urlencode($donor['name']); ?>&surname=<?php echo urlencode($donor['surname']); ?>">اطلب رقم الهاتف</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <footer>
        <p>جميع الحقوق محفوظة لمؤسسة إستشفائية عمومية عين الكبيرة</p>
    </footer>
</body>
</html>
