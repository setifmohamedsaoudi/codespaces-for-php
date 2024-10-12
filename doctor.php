<?php
session_start();

// التحقق من تسجيل دخول الطبيب
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'doctor') {
    header("Location: login.php");
    exit();
}

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

// وظيفة لحفظ بيانات المتبرعين في الملفات
function saveDonors($donors, $donorsFileTxt, $donorsFileJson) {
    $jsonData = json_encode($donors, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents($donorsFileTxt, $jsonData);
    file_put_contents($donorsFileJson, $jsonData);
}

// تحميل بيانات المتبرعين
$donors = loadDonors($donorsFileTxt, $donorsFileJson);

// متغيرات للنتائج والرسائل
$searchResult = null;
$eligibleDonor = null;
$updateSuccess = false;
$errorMessage = '';

// معالجة النموذج عند الإرسال
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // البحث عن المتبرع
    if (isset($_POST['search_donor'])) {
        $searchName = trim($_POST['name']);
        $searchSurname = trim($_POST['surname']);

        // البحث في قائمة المتبرعين
        foreach ($donors as $index => $donor) {
            if (strcasecmp($donor['name'], $searchName) === 0 && strcasecmp($donor['surname'], $searchSurname) === 0) {
                // التحقق من تاريخ آخر تبرع
                if (isset($donor['last_donation_date']) && $donor['last_donation_date'] !== null) {
                    $lastDonationDate = new DateTime($donor['last_donation_date']);
                    $currentDate = new DateTime();
                    $interval = $currentDate->diff($lastDonationDate);

                    // التحقق إذا كانت المدة أكثر من 3 أشهر
                    if ($interval->y > 0 || $interval->m > 3 || ($interval->m == 3 && $interval->d > 0)) {
                        $eligibleDonor = $donor;
                        $donorIndex = $index; // حفظ الفهرس لتحديثه لاحقًا
                    } else {
                        $errorMessage = "المتبرع لم يتجاوز مدة 3 أشهر منذ آخر تبرع.";
                    }
                } else {
                    // إذا لم يقم المتبرع بالتبرع من قبل
                    $eligibleDonor = $donor;
                    $donorIndex = $index; // حفظ الفهرس لتحديثه لاحقًا
                }
                break;
            }
        }

        if (!$eligibleDonor && !$errorMessage) {
            $errorMessage = "لم يتم العثور على متبرع بهذا الاسم واللقب.";
        }
    }

    // تحديث تاريخ آخر تبرع
    if (isset($_POST['update_donation_date']) && isset($donorIndex)) {
        $donationDate = $_POST['donation_date'];

        // تحديث تاريخ آخر تبرع في القائمة
        $donors[$donorIndex]['last_donation_date'] = $donationDate;

        // حفظ التحديثات في الملفات
        saveDonors($donors, $donorsFileTxt, $donorsFileJson);
        $updateSuccess = true;

        // إعادة تحميل بيانات المتبرعين بعد التحديث
        $donors = loadDonors($donorsFileTxt, $donorsFileJson);
        $eligibleDonor = null; // إعادة تعيين المتبرع المؤهل
    }
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>صفحة الطبيب</title>
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
        input[type="text"], input[type="date"] {
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
        .error { 
            text-align: center; 
            color: red; 
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
    <h1>صفحة الطبيب</h1>
    <p style="text-align:center;"><a href="logout.php">تسجيل الخروج</a></p>

    <?php if ($updateSuccess): ?>
        <p class="message">تم تحديث تاريخ آخر تبرع بنجاح!</p>
    <?php endif; ?>

    <?php if ($errorMessage): ?>
        <p class="error"><?php echo $errorMessage; ?></p>
    <?php endif; ?>

    <form method="POST">
        <h2>بحث عن متبرع</h2>
        <input type="text" name="name" placeholder="الاسم" required>
        <input type="text" name="surname" placeholder="اللقب" required>
        <input type="submit" name="search_donor" value="بحث">
    </form>

    <?php if ($eligibleDonor): ?>
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
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo htmlspecialchars($eligibleDonor['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($eligibleDonor['surname'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($eligibleDonor['birth_date'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($eligibleDonor['address'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($eligibleDonor['phone'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($eligibleDonor['blood_type'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($eligibleDonor['rh_factor'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($eligibleDonor['last_donation_date'] ?? 'غير محدد', ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
            </tbody>
        </table>

        <form method="POST">
            <input type="hidden" name="name" value="<?php echo htmlspecialchars($eligibleDonor['name'], ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="surname" value="<?php echo htmlspecialchars($eligibleDonor['surname'], ENT_QUOTES, 'UTF-8'); ?>">
            <h3 style="text-align:center;">تحديث تاريخ آخر تبرع</h3>
            <input type="date" name="donation_date" required>
            <input type="submit" name="update_donation_date" value="تحديث">
        </form>
    <?php endif; ?>

    <h2 style="text-align:center;">قائمة المتبرعين المؤهلين للتبرع</h2>
    <?php
    // تحميل المتبرعين المؤهلين (أكثر من 3 أشهر منذ آخر تبرع)
    $eligibleDonors = [];
    $currentDate = new DateTime();

    foreach ($donors as $donor) {
        if (isset($donor['last_donation_date']) && $donor['last_donation_date'] !== null) {
            $lastDonationDate = new DateTime($donor['last_donation_date']);
            $interval = $currentDate->diff($lastDonationDate);

            if ($interval->y > 0 || $interval->m > 3 || ($interval->m == 3 && $interval->d > 0)) {
                $eligibleDonors[] = $donor;
            }
        } else {
            // المتبرع لم يتبرع من قبل، يعتبر مؤهلاً
            $eligibleDonors[] = $donor;
        }
    }
    ?>

    <?php if (empty($eligibleDonors)): ?>
        <p style="text-align:center;">لا توجد متبرعين مؤهلين للتبرع.</p>
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
                <?php foreach ($eligibleDonors as $donor): ?>
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
