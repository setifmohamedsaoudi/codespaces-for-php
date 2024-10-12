<?php
// عرض نموذج لإدخال المبلغ الأصلي والمبلغ بعد عام
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // استلام القيم المدخلة من النموذج
    $initial_amount = floatval($_POST['initial_amount']);
    $amount_after_year = floatval($_POST['amount_after_year']);

    // تعريف الحد الأدنى للحوائج النصاب بالسنتيم
    $nisab = 80000000; // 80,000,000 سنتيم

    // التحقق من أن المبلغ الأصلي لم ينقص بعد عام
    if ($amount_after_year >= $initial_amount) {
        // التحقق من أن المبلغ الأصلي يحقق النصاب
        if ($initial_amount >= $nisab) {
            // حساب الزكاة
            $zakat = $amount_after_year / 40;

            echo "<h3>قيمة الزكاة هي: " . number_format($zakat, 2) . " سنتيم</h3>";
        } else {
            echo "<h3>المبلغ لا يحقق النصاب (80,000,000 سنتيم).</h3>";
        }
    } else {
        echo "<h3>المبلغ انخفض بعد مرور العام، لا تجب الزكاة.</h3>";
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>حساب الزكاة</title>
</head>
<body>
    <h2>حاسبة الزكاة</h2>
    <form method="post" action="">
        <label for="initial_amount">المبلغ الأصلي (سنتيم):</label><br>
        <input type="number" id="initial_amount" name="initial_amount" required><br><br>

        <label for="amount_after_year">المبلغ بعد عام (سنتيم):</label><br>
        <input type="number" id="amount_after_year" name="amount_after_year" required><br><br>

        <input type="submit" value="احسب الزكاة">
    </form>
</body>
</html>
