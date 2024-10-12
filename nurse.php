<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'nurse') {
    header("Location: login.php");
    exit();
}

// تحميل بيانات المتبرعين من ملف
$donors = [];
if (file_exists('donors.txt')) {
    $donorsData = file_get_contents('donors.txt');
    $donors = json_decode($donorsData, true) ?? [];
}

$searchResults = [];
$updateSuccess = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_donor'])) {
    $name = trim($_POST['name']);
    $surname = trim($_POST['surname']);

    // البحث عن المتبرع بالاسم واللقب
    foreach ($donors as &$donor) {
        if (strcasecmp($donor['name'], $name) === 0 && strcasecmp($donor['surname'], $surname) === 0) {
            $searchResults[] = $donor;
            break; // بمجرد العثور على المتبرع، نخرج من الحلقة
        }
    }

    // تحديث تاريخ آخر تبرع إذا تم العثور على المتبرع
    if (!empty($searchResults) && isset($_POST['last_donation_date'])) {
        $lastDonationDate = $_POST['last_donation_date'];
        $donorToUpdate = &$donors[array_search($searchResults[0], $donors)];
        $donorToUpdate['last_donation_date'] = $lastDonationDate;

        // تحديث البيانات في ملف المتبرعين
        file_put_contents('donors.txt', json_encode($donors));
        $updateSuccess = true; // تأكيد التحديث
    }
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>صفحة الممرض</title>
    <style>
        body { font-family: Arial, sans-serif; direction: rtl; background-color: #f2f2f2; }
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
        <input type="date" name="last_donation_date" placeholder="تاريخ آخر تبرع" required>
        <input type="submit" name="search_donor" value="بحث وتحديث">
    </form>

    <?php if ($updateSuccess): ?>
        <p style="color: green;">تم تحديث تاريخ آخر تبرع بنجاح!</p>
    <?php endif; ?>

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
