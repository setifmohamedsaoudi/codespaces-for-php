<?php
// visitors.php

$donorsFile = 'donors.txt';
$adminEmail = 'admin@example.com'; // قم بتغيير هذا إلى بريد الإدارة الفعلي

// تحميل قائمة المتبرعين
$donors = [];
if (file_exists($donorsFile)) {
    $lines = file($donorsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $donor = json_decode($line, true);
        if ($donor) {
            $donors[] = $donor;
        }
    }
}

// معالجة طلب رقم الهاتف
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_phone'])) {
    $donorEmail = htmlspecialchars(trim($_POST['donor_email']));
    $visitorPhone = htmlspecialchars(trim($_POST['visitor_phone']));

    // التحقق من صحة البيانات
    if (!empty($donorEmail) && !empty($visitorPhone)) {
        // البحث عن المتبرع بواسطة البريد الإلكتروني
        $donor = null;
        foreach ($donors as $d) {
            if ($d['email'] === $donorEmail) {
                $donor = $d;
                break;
            }
        }

        if ($donor) {
            // إرسال إشعار إلى الإدارة
            $subject = "طلب رقم هاتف المتبرع";
            $message = "قام الزائر بطلب رقم هاتف المتبرع:\n\n" .
                       "اسم المتبرع: " . $donor['name'] . "\n" .
                       "بريد المتبرع: " . $donor['email'] . "\n\n" .
                       "رقم هاتف الزائر: " . $visitorPhone . "\n\n" .
                       "يرجى التواصل مع الزائر لتزويده برقم هاتف المتبرع.";
            $headers = "From: no-reply@yourwebsite.com"; // قم بتغيير عنوان البريد حسب الحاجة

            // استخدام دالة mail لإرسال البريد (تأكد من إعداد الخادم للبريد)
            if (mail($adminEmail, $subject, $message, $headers)) {
                $successMessage = "تم إرسال طلبك بنجاح وسيتم التواصل معك قريبًا.";
            } else {
                $errorMessage = "حدث خطأ أثناء إرسال الطلب. يرجى المحاولة مرة أخرى.";
            }
        } else {
            $errorMessage = "لم يتم العثور على المتبرع المحدد.";
        }
    } else {
        $errorMessage = "يرجى ملء جميع الحقول.";
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>زوار الموقع</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef;
            padding: 20px;
        }
        .donor-list {
            max-width: 800px;
            margin: auto;
        }
        .donor {
            background-color: #fff;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 6px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }
        .button {
            background-color: #007bff;
            color: white;
            padding: 7px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }
        .button:hover {
            background-color: #0056b3;
        }
        .request-form {
            display: none;
            margin-top: 10px;
        }
        .request-form input[type="tel"] {
            width: 80%;
            padding: 6px;
            margin-right: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .request-form input[type="submit"] {
            padding: 6px 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .request-form input[type="submit"]:hover {
            background-color: #218838;
        }
        .message {
            text-align: center;
            margin-bottom: 20px;
            color: green;
        }
        .error {
            color: red;
        }
    </style>
    <script>
        function toggleRequestForm(id) {
            var form = document.getElementById('request-form-' + id);
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        }
    </script>
</head>
<body>

<div class="donor-list">
    <h2>قائمة المتبرعين</h2>

    <?php if (isset($successMessage)): ?>
        <p class="message"><?php echo $successMessage; ?></p>
    <?php endif; ?>

    <?php if (isset($errorMessage)): ?>
        <p class="message error"><?php echo $errorMessage; ?></p>
    <?php endif; ?>

    <?php if (count($donors) > 0): ?>
        <?php foreach ($donors as $index => $donor): ?>
            <div class="donor">
                <p><strong>الاسم:</strong> <?php echo htmlspecialchars($donor['name']); ?></p>
                <p><strong>البريد الإلكتروني:</strong> <?php echo htmlspecialchars($donor['email']); ?></p>
                <p><strong>العنوان:</strong> <?php echo htmlspecialchars($donor['address']); ?></p>
             !   <!-- لا نعرض رقم الهاتف إلا عند الطلب -->
                <button class="button" onclick="toggleRequestForm(<?php echo $index; ?>)">طلب رقم الهاتف</button>

                <div class="request-form" id="request-form-<?php echo $index; ?>">
                    <form action="visitors.php" method="POST">
                        <input type="hidden" name="donor_email" value="<?php echo htmlspecialchars($donor['email']); ?>">
                        <input type="tel" name="visitor_phone" placeholder="أدخل رقم هاتفك" required>
                        <input type="submit" name="request_phone" value="إرسال الطلب">
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>لا يوجد متبرعين متاحين حاليًا.</p>
    <?php endif; ?>
</div>

</body>
</html>
