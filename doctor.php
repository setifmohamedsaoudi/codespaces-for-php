<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'doctor') {
    header("Location: login.php");
    exit();
}

// استرجاع بيانات المتبرعين من الجلسة
$donors = isset($_SESSION['donors']) ? $_SESSION['donors'] : [];
$searchResults = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_donor'])) {
    $bloodType = $_POST['blood_type'];

    // البحث عن المتبرعين بناءً على زمرة الدم
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
    <title>صفحة الطبيب</title>
    <style>
        body { font-family: Arial, sans-serif; direction: rtl; background-color: #f2f2f2; }
        h1 { color: #4CAF50; }
        a { text-decoration: none; color: #fff; background-color: #4CAF50; padding: 10px; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
    </style>
</head>
<body>
    <h1>مرحبًا بك، <?php echo $_SESSION['username']; ?> (الطبيب)</h1>
    <p><a href="logout.php">تسجيل الخروج</a></p>

    <h2>بحث عن متبرع حسب زمرة الدم</h2>
    <form method="POST">
        <select name="blood_type" required>
            <option value="">اختر زمرة الدم</option>
            <option value="A">A</option>
            <option value="B">B</option>
            <option value="AB">AB</option>
            <option value="O">O</option>
        </select>
        <input type="submit" name="search_donor" value="بحث">
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
                    <th>زمرة الريزوس</th>
                    <th>تاريخ آخر تبرع</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($searchResults as $donor): ?>
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
    <?php else: ?>
        <p>لا توجد نتائج للبحث.</p>
    <?php endif; ?>
</body>
</html>
