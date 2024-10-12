<?php
session_start();

// تحديد مسارات ملفات المتبرعين وطلبات الهاتف
$donorsFileTxt = 'donors.txt';
$donorsFileJson = 'donors.json';
$requestsFileTxt = 'requests.txt';
$requestsFileJson = 'requests.json';

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

// وظيفة لحفظ بيانات الطلبات في الملفات
function saveRequests($requests, $requestsFileTxt, $requestsFileJson) {
    $jsonData = json_encode($requests, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents($requestsFileTxt, $jsonData);
    file_put_contents($requestsFileJson, $jsonData);
}

// تحميل بيانات المتبرعين
$donors = loadDonors($donorsFileTxt, $donorsFileJson);

// متغيرات للنتائج والرسائل
$message = '';
$errorMessage = '';

// التحقق من أن الطلب يتم عن طريق GET مع اسم ولقب المتبرع
if (!isset($_GET['name']) || !isset($_GET['surname'])) {
    // إعادة التوجيه إلى visitors.php إذا لم يتم توفير الاسم واللقب
    header("Location: visitors.php");
    exit();
}

$donorName = trim($_GET['name']);
$donorSurname = trim($_GET['surname']);

// التحقق من وجود المتبرع في القائمة
$donorFound = false;
foreach ($donors as $donor) {
    if (strcasecmp($donor['name'], $donorName) === 0 && strcasecmp($donor['surname'], $donorSurname) === 0) {
        $donorFound = true;
        break;
    }
}

if (!$donorFound) {
    $errorMessage = "لم يتم العثور على المتبرع المطلوب.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_request'])) {
    // استلام بيانات الزائر من النموذج
    $visitorName = trim($_POST['visitor_name']);
    $visitorPhone = trim($_POST['visitor_phone']);

    // التحقق من صحة البيانات المدخلة
    if ($visitorName === '' || $visitorPhone === '') {
        $errorMessage = "يرجى ملء جميع الحقول.";
    } elseif (!preg_match('/^\d{10}$/', $visitorPhone)) { // التحقق من أن رقم الهاتف مكون من 10 أرقام
        $errorMessage = "يرجى إدخال رقم هاتف صالح مكون من 10 أرقام.";
    } else {
        // إنشاء مصفوفة للطلب الجديد
        $request = [
            'donor_name' => htmlspecialchars($donorName, ENT_QUOTES, 'UTF-8'),
            'donor_surname' => htmlspecialchars($donorSurname, ENT_QUOTES, 'UTF-8'),
            'visitor_name' => htmlspecialchars($visitorName, ENT_QUOTES, 'UTF-8'),
            'visitor_phone' => htmlspecialchars($visitorPhone, ENT_QUOTES, 'UTF-8'),
            'timestamp' => date('Y-m-d H:i:s')
        ];

        // تحميل الطلبات السابقة من الملفات
        $requests = [];

        // تحميل من requests.txt
        if (file_exists($requestsFileTxt)) {
            $requestsDataTxt = file_get_contents($requestsFileTxt);
            $requestsTxt = json_decode($requestsDataTxt, true);
            if ($requestsTxt !== null) {
                $requests = array_merge($requests, $requestsTxt);
            }
        }

        // تحميل من requests.json
        if (file_exists($requestsFileJson)) {
            $requestsDataJson = file_get_contents($requestsFileJson);
            $requestsJson = json_decode($requestsDataJson, true);
            if ($requestsJson !== null) {
                $requests = array_merge($requests, $requestsJson);
            }
        }

        // إضافة الطلب الجديد
        $requests[] = $request;

        // حفظ الطلبات في الملفات
        saveRequests($requests, $requestsFileTxt, $requestsFileJson);

        // عرض رسالة نجاح
        $message = "تم إرسال طلب رقم الهاتف بنجاح! سيقوم الأدمن بالرد عليك قريبًا.";
    }
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>طلب رقم الهاتف</title>
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
        input[type="text"], input[type="tel"] {
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
    </style>
</head>
<body>
    <h1>طلب رقم الهاتف</h1>
    <p style="text-align:center;"><a href="visitors.php">عودة إلى البحث عن متبرعين</a></p>

    <?php if ($message): ?>
        <p class="message"><?php echo $message; ?></p>
    <?php endif; ?>

    <?php if ($errorMessage): ?>
        <p class="error"><?php echo $errorMessage; ?></p>
    <?php endif; ?>

    <?php if ($donorFound): ?>
        <form method="POST">
            <h2>إدخال بياناتك</h2>
            <label for="visitor_name">اسمك:</label>
            <input type="text" id="visitor_name" name="visitor_name" required>

            <label for="visitor_phone">رقم هاتفك:</label>
            <input type="tel" id="visitor_phone" name="visitor_phone" pattern="\d{10}" title="يرجى إدخال رقم هاتف مكون من 10 أرقام" required>

            <input type="submit" name="submit_request" value="إرسال الطلب">
        </form>
    <?php endif; ?>
</body>
</html>
