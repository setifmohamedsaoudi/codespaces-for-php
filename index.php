<?php
// معالجة النموذج عند الإرسال
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // استلام القيم المدخلة من النموذج
    $initial_amount = floatval($_POST['initial_amount']);
    $amount_after_year = floatval($_POST['amount_after_year']);

    // تعريف الحد الأدنى للنصاب بالسنتيم
    $nisab = 80000000; // 80,000,000 سنتيم

    // التحقق من أن المبلغ الأصلي لم ينقص بعد عام
    if ($amount_after_year >= $initial_amount) {
        // التحقق من أن المبلغ الأصلي يحقق النصاب
        if ($initial_amount >= $nisab) {
            // حساب الزكاة
            $zakat = $amount_after_year / 40;

            $result = "قيمة الزكاة هي: " . number_format($zakat, 2) . " سنتيم";
        } else {
            $result = "المبلغ لا يحقق النصاب (80,000,000 سنتيم).";
        }
    } else {
        $result = "المبلغ انخفض بعد مرور العام، لا تجب الزكاة.";
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>حساب الزكاة</title>
    <style>
        /* تنسيق الصفحة باستخدام Flexbox لمركزتها */
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }

        .container {
            display: flex;
            justify-content: center; /* محاذاة أفقية في المركز */
            align-items: center;    /* محاذاة عمودية في المركز */
            height: 100%;
        }

        .form-box {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 8px;
            text-align: right;
            color: #555;
        }

        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            text-align: right;
        }

        input[type="submit"] {
            background-color: #28a745;
            color: #fff;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }

        .result {
            margin-top: 20px;
            font-size: 18px;
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-box">
            <h2>حاسبة الزكاة</h2>
            <form method="post" action="">
                <label for="initial_amount">المبلغ الأصلي (سنتيم):</label>
                <input type="number" id="initial_amount" name="initial_amount" required>

                <label for="amount_after_year">المبلغ بعد عام (سنتيم):</label>
                <input type="number" id="amount_after_year" name="amount_after_year" required>

                <input type="submit" value="احسب الزكاة">
            </form>

            <?php
            // عرض النتيجة إذا كانت موجودة
            if (isset($result)) {
                echo "<div class='result'><h3>$result</h3></div>";
            }
            ?>
        </div>
    </div>
</body>
</html>
