<?php
session_start();

$donors = isset($_SESSION['donors']) ? $_SESSION['donors'] : [];
$searchResults = [];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['blood_type'])) {
    $bloodType = $_GET['blood_type'];

    foreach ($donors as $donor) {
        if ($donor['blood_type'] === $bloodType) {
            $searchResults[] = $donor;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>نتائج البحث عن المتبرعين</title>
    <style>
        body { font-family: Arial, sans-serif; direction: rtl; background-color: #f2f2f2; }
        h1 { color: #4CAF50; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
    </style>
</head>
<body>
    <h1>نتائج البحث عن المتبرعين</h1>

    <?php if (!empty($searchResults)): ?>
        <table>
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>اللقب</th>
                    <th>تاريخ الميلاد</th>
                    <th>الزمرة الدموية</th>
                    <th>زمرة الريزوس</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($searchResults as $donor): ?>
                    <tr>
                        <td><?php echo $donor['name']; ?></td>
                        <td><?php echo $donor['surname']; ?></td>
                        <td><?php echo $donor['birth_date']; ?></td>
                        <td><?php echo $donor['blood_type']; ?></td>
                        <td><?php echo $donor['rh_factor']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>لا توجد نتائج للبحث.</p>
    <?php endif; ?>
</body>
</html>
