<?php
// تحميل بيانات المتبرعين من ملف
$donors = [];
if (file_exists('donors.txt')) {
    $donorsData = file_get_contents('donors.txt');
    $donors = json_decode($donorsData, true) ?? [];
}

$searchResults = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_donor'])) {
    $bloodType = trim($_POST['blood_type']);
    $rhFactor = trim($_POST['rh_factor']);

    // البحث عن المتبرعين حسب الزمرة الدموية وزمرة الريزوس
    foreach ($donors as $donor) {
        if ($donor['blood_type'] === $bloodType && $donor['rh_factor'] === $rhFactor) {
            $searchResults[] = $donor;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>صفحة الزوار</title>
    <style>
        body { font-family: Arial, sans-serif; direction: rtl; background-color: #f2f2f2; }
        h1 { color: #4CAF50; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
    </style>
</head>
<body>
    <h1>بحث عن المتبرعين</h1>
    <form method="POST">
        <label for="blood_type">الزمرة الدموية:</label>
        <select name="blood_type" id="blood_type" required>
            <option value="A">A</option>
            <option value="B">B</option>
            <option value="AB">AB</option>
            <option value="O">O</option>
        </select>

        <label for="rh_factor">زمرة الريزوس:</label>
        <select name="rh_factor" id="rh_factor" required>
            <option value="+">موجب</option>
            <option value="-">سالب</option>
        </select>

        <input type="submit" name="search_donor" value="بحث">
    </form>

    <?php if (!empty($searchResults)): ?>
        <h2>نتائج البحث:</h2>
        <table>
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>اللقب</th>
                    <th>الزمرة الدموية</th>
                    <th>زمرة الريزوس</th>
                    <th>تاريخ آخر تبرع</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($searchResults as $donor): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($donor['name']); ?></td>
                        <td><?php echo htmlspecialchars($donor['surname']); ?></td>
                        <td><?php echo htmlspecialchars($donor['blood_type']); ?></td>
                        <td><?php echo htmlspecialchars($donor['rh_factor']); ?></td>
                        <td><?php echo htmlspecialchars($donor['last_donation_date'] ?? 'غير محدد'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>لا توجد نتائج مطابقة.</p>
    <?php endif; ?>
</body>
</html>
