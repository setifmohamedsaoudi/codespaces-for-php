<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $donor_name = $_POST['donor_name'] ?? '';
    $donor_surname = $_POST['donor_surname'] ?? '';
    $visitor_name = $_POST['visitor_name'] ?? '';
    $visitor_phone = $_POST['visitor_phone'] ?? '';

    // هنا يمكنك تعديل هذا الجزء لإرسال إشعار للأدمن عبر البريد الإلكتروني أو تخزينه في ملف
    $request_info = [
        'donor_name' => $donor_name,
        'donor_surname' => $donor_surname,
        'visitor_name' => $visitor_name,
        'visitor_phone' => $visitor_phone,
        'timestamp' => date('Y-m-d H:i:s')
    ];

    // تخزين المعلومات في ملف نصي
    $requests = [];
    if (file_exists('requests.txt')) {
        $requestsData = file_get_contents('requests.txt');
        $requests = json_decode($requestsData, true) ?? [];
    }

    $requests[] = $request_info;
    file_put_contents('requests.txt', json_encode($requests, JSON_PRETTY_PRINT));

    echo "<h3>تم إرسال الطلب بنجاح!</h3>";
    echo "<p>سيقوم الأدمن بالرد عليك قريبًا.</p>";
} else {
    // إعادة التوجيه إذا تم الوصول إلى هذا الملف مباشرةً
    header("Location: visitors.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>طلب رقم الهاتف</title>
    <style>
        body { font-family: Arial, sans-serif; direction: rtl; background-color: #f2f2f2; }
        h1 { color: #4CAF50; }
    </style>
</head>
<body>
    <h1>طلب رقم الهاتف</h1>
    <form method="POST">
        <input type="hidden" name="donor_name" value="<?php echo htmlspecialchars($_GET['donor'] ?? ''); ?>">
        <input type="hidden" name="donor_surname" value="<?php echo htmlspecialchars($_GET['donor⬤
