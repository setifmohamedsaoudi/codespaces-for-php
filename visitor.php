<?php
session_start();

// تحميل بيانات المتبرعين من ملف
$donors = [];
if (file_exists('donors.txt')) {
    $donorsData = file_get_contents('donors.txt');
    $donors = json_decode($donorsData, true) ?? [];
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
    <h1>مرحبًا بك في صفحة الزوار</h1>
    <h2>قائمة المتبرعين</h2>
    
    <?php if (empty($donors)): ?>
        <p>لا توجد متبرعين مسجلين.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>اللقب</th>
                    <th>تاريخ الميلاد</th>
                    <th>الزمرة الدموية</th>
                    <th>زمرة الريزوس</th>
                    <th>تاريخ آخر تبرع</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($donors as $donor): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($donor['name']); ?></td>
                        <td><?php echo htmlspecialchars($donor['surname']); ?></td>
                        <td><?php echo htmlspecialchars($donor['birth_date']); ?></td>
                        <td><?php echo htmlspecialchars($donor['blood_type']); ?></td>
                        <td><?php echo htmlspecialchars($donor['rh_factor']); ?></td>
                        <td><?php echo htmlspecialchars($donor['last_donation_date'] ?? 'غير محدد'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
