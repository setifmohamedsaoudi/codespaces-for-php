<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'nurse') {
    header("Location: login.php");
    exit();
}

$donors = isset($_SESSION['donors']) ? $_SESSION['donors'] : [];
$searchResult = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_donor'])) {
    $searchName = $_POST['name'];
    $searchSurname = $_POST['surname'];

    foreach ($donors as $donor) {
        if ($donor['name'] === $searchName && $donor['surname'] === $searchSurname) {
            $searchResult = $donor;
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>صفحة الممرض</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            direction: rtl; 
            background-color: #f2f2f2; 
        }
        h1 { color: #4CAF50; }
        a { text-decoration: none; color: #fff; background-color: #4CAF50; padding: 10px; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
    </style>
</head>
<body>
    <h1>مرحبًا بك، <?php echo $_SESSION['username']; ?> (الممرض)</h1>
    <p><a href="logout.php">تسجيل الخروج</a></p>

    <h2>بحث عن متبرع</h2>
    <form method="POST">
        <input type="text" name="name" placeholder="الاسم" required>
        <input type="text" name="surname" placeholder="اللقب" required>
        <input type="submit" name="search_donor" value="بحث">
    </form>

    <?php if ($searchResult): ?>
        <h3>نتائج البحث:</h3>
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
                <tr>
                    <td><?php echo $searchResult['name']; ?></td>
                    <td><?php echo $searchResult['surname']; ?></td>
                    <td><?php echo $searchResult['birth_date']; ?></td>
                    <td><?php echo $searchResult['blood_type']; ?></td>
                    <td><?php echo $
