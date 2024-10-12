<?php
session_start();

// تحميل بيانات المتبرعين من ملف
$donors = [];
if (file_exists('donors.txt')) {
    $donorsData = file_get_contents('donors.txt');
    $donors = json_decode($donorsData, true) ?? [];
}

$searchResults = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    $bloodType = $_POST['blood_type'];
    $rhFactor = $_POST['rh_factor'];

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
    <h1>صفحة الزوار</h1>

    <h2>البحث عن متبرع</h2>
    <form method="POST">
        <label for="blood_type">الزمرة الدموية:</label>
        <select name="blood_type" required>
            <option value="A">A</option>
            <option value="B">B</option>
            <option value="AB">AB</option>
            <option value="O">O</option>
        </select>

        <label for="rh_factor">زمرة الريزوس:</label>
        <select name="rh_factor" required>
            <option value="موجب">موجب</option>
            <option value="سالب">سالب</option>
        </select>

        <input type="submit" name="search" value="بحث">
    </form>

    <?php if (!empty($searchResults)): ?>
        <h3>نتائج البحث:</h3>
        <table>
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>اللقب</th>
                    <th>تاريخ الميلاد</th>
                    <th>الزمرة الدموية</th>
                    <th>زمرة الريزوس</⬤
