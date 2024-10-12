<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'nurse') {
    header("Location: login.php");
    exit();
}

$donors = isset($_SESSION['donors']) ? $_SESSION['donors'] : [];
$searchResult = null;
$updateSuccess = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['search_donor'])) {
        // البحث عن متبرع
        $searchName = $_POST['name'];
        $searchSurname = $_POST['surname'];

        foreach ($donors as $donor) {
            if ($donor['name'] === $searchName && $donor['surname'] === $searchSurname) {
                $searchResult = $donor;
                break;
            }
        }
    } elseif (isset($_POST['update_donation_date'])) {
        // تحديث تاريخ آخر تبرع
        $donationDate = $_POST['donation_date'];
        foreach ($donors as &$donor) {
            if ($donor['name'] === $_POST['name'] && $donor['surname'] === $_POST['surname']) {
                $donor['last_donation_date'] = $donationDate;
                $updateSuccess = true;
                break;
            }
        }
        $_SESSION['donors'] = $donors; // تحديث الجلسة
        file_put_contents('donors.txt', json_encode($_SESSION['donors'])); // حفظ البيانات في الملف
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
                    <th>تاريخ آخر تبرع</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo htmlspecialchars($searchResult['name']); ?></td>
                    <td><?php echo htmlspecialchars($searchResult['surname']); ?></td>
                    <td><?php echo htmlspecialchars($searchResult['birth_date']); ?></td>
                    <td><?php echo htmlspecialchars($searchResult['blood_type']); ?></td>
                    <td><?php echo htmlspecialchars($searchResult['rh_factor']); ?></td>
                    <td><?php echo htmlspecialchars($searchResult['last_donation_date'] ?? 'غير محدد'); ?></td>
                </tr>
            </tbody>
        </table>
        
        <h3>تحديث تاريخ آخر تبرع</h3>
        <form method="POST">
            <input type="hidden" name="name" value="<?php echo htmlspecialchars($searchResult['name']); ?>">
            <input type="hidden" name="surname" value="<?php echo htmlspecialchars($searchResult['surname']); ?>">
            <label for="donation_date">تاريخ
