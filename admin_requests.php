<?php
session_start();

// التأكد من أن المستخدم مسجل الدخول وأن دوره هو "admin"
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// تحديد مسارات ملفات الطلبات
$requestsFileTxt = 'requests.txt';
$requestsFileJson = 'requests.json';

// وظيفة لتحميل بيانات الطلبات من الملفات
function loadRequests($requestsFileTxt, $requestsFileJson) {
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

    // إزالة التكرارات بناءً على donor_name و donor_surname و visitor_phone
    $uniqueRequests = [];
    $existing = [];
    foreach ($requests as $request) {
        $key = strtolower($request['donor_name'] . '|' . $request['donor_surname'] . '|' . $request['visitor_phone']);
        if (!in_array($key, $existing)) {
            $uniqueRequests[] = $request;
            $existing[] = $key;
        }
    }

    return $uniqueRequests;
}

// وظيفة لحفظ بيانات الطلبات في الملفات
function saveRequests($requests, $requestsFileTxt, $requestsFileJson) {
    $jsonData = json_encode($requests, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents($requestsFileTxt, $jsonData);
    file_put_contents($requestsFileJson, $jsonData);
}

// تحميل بيانات الطلبات
$requests = loadRequests($requestsFileTxt, $requestsFileJson);

// معالجة طلب حذف طلب
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_request'])) {
    $indexToDelete = $_POST['request_index'];

    if (isset($requests[$indexToDelete])) {
        // إزالة الطلب من المصفوفة
        array_splice($requests, $indexToDelete, 1);

        // حفظ البيانات المحدثة في الملفات
        saveRequests($requests, $requestsFileTxt, $requestsFileJson);

        // إعادة تحميل الطلبات بعد الحذف
        $requests = loadRequests($requestsFileTxt, $requestsFileJson);

        // رسالة نجاح
        $message = "تم حذف الطلب بنجاح.";
    } else {
        $errorMessage = "الطلب غير موجود.";
    }
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>عرض طلبات رقم الهاتف</title>
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
        .delete-button {
            background-color: #f44336; /* أحمر */
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
        }
        .delete-button:hover {
            background-color: #da190b;
        }
    </style>
</head>
<body>
    <h1>طلبات رقم الهاتف المقدمة من الزوار</h1>
    <p style="text-align:center;"><a href="admin.php">العودة إلى لوحة التحكم</a> | <a href="logout.php">تسجيل الخروج</a></p>

    <?php if (isset($message)): ?>
        <p class="message"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>

    <?php if (isset($errorMessage)): ?>
        <p class="error"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>

    <?php if (empty($requests)): ?>
        <p style="text-align:center;">لا توجد طلبات حالية.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>اسم المتبرع</th>
                    <th>لقب المتبرع</th>
                    <th>اسم الزائر</th>
                    <th>رقم هاتف الزائر</th>
                    <th>تاريخ الطلب</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($requests as $index => $request): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($request['donor_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($request['donor_surname'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($request['visitor_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($request['visitor_phone'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($request['timestamp'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td>
                            <form method="POST" onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف هذا الطلب؟');">
                                <input type="hidden" name="request_index" value="<?php echo $index; ?>">
                                <input type="submit" name="delete_request" value="حذف" class="delete-button">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
