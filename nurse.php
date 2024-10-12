<?php
// nurse.php

$donorsFile = 'donors.json';

// وظيفة لتحميل البيانات من ملف JSON
function loadData($file) {
    if (!file_exists($file)) {
        file_put_contents($file, json_encode([]));
    }
    $json = file_get_contents($file);
    return json_decode($json, true);
}

// وظيفة لحفظ البيانات في ملف JSON
function saveData($file, $data) {
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents($file, $json);
}

$search_results = [];
$update_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['search'])) {
        $first_name = trim($_POST['first_name']);
        $last_name = trim($_POST['last_name']);
        
        if ($first_name && $last_name) {
            $donors = loadData($donorsFile);
            foreach ($donors as $donor) {
                if (strcasecmp($donor['first_name'], $first_name) == 0 && strcasecmp($donor['last_name'], $last_name) == 0) {
                    $search_results[] = $donor;
                }
            }
            if (count($search_results) == 0) {
                $update_message = "لم يتم العثور على المتبرع.";
            }
        } else {
            $update_message = "يرجى إدخال الاسم واللقب.";
        }
    }
    
    if (isset($_POST['update'])) {
        $donor_id = $_POST['donor_id'];
        $last_donation_date = $_POST['last_donation_date'];
        
        if ($donor_id && $last_donation_date) {
            $donors = loadData($donorsFile);
            foreach ($donors as &$donor) {
                if ($donor['id'] == $donor_id) {
                    $donor['last_donation_date'] = $last_donation_date;
                    break;
                }
            }
            saveData($donorsFile, $donors);
            $update_message = "تم تحديث تاريخ آخر تبرع بنجاح!";
            // إعادة البحث بعد التحديث
            $search_results = [];
        } else {
            $update_message = "يرجى إدخال تاريخ آخر تبرع.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>قسم الممرض</title>
    <style>
        body { font-family: Arial, sans-serif; direction: rtl; }
        .container { width: 60%; margin: auto; }
        .message { padding: 10px; margin-bottom: 20px; }
        .success { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
        form { border: 1px solid #ccc; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        label { display: block; margin-top: 10px; }
        input, select { width: 100%; padding: 8px; margin-top: 5px; }
        input[type="submit"] { width: auto; background-color: #4CAF50; color: white; border: none; cursor: pointer; }
        input[type="submit"]:hover { background-color: #45a049; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="container">
        <h2>قسم الممرض</h2>
        
        <?php if ($update_message): ?>
            <div class="message <?php echo strpos($update_message, 'نجاح') !== false ? 'success' : 'error'; ?>">
                <?php echo $update_message; ?>
            </div>
        <?php endif; ?>
        
        <!-- نموذج البحث -->
        <form action="nurse.php" method="post">
            <input type="hidden" name="search" value="1">
            
            <label for="first_name">الاسم:</label>
            <input type="text" id="first_name" name="first_name" required>
            
            <label for="last_name">اللقب:</label>
            <input type="text" id="last_name" name="last_name" required>
            
            <br>
            <input type="submit" value="بحث">
        </form>
        
        <?php if (count($search_results) > 0): ?>
            <h3>نتائج البحث</h3>
            <table>
                <tr>
                    <th>الاسم</th>
                    <th>اللقب</th>
                    <th>تاريخ الميلاد</th>
                    <th>الزَّمَة الدموية</th>
                    <th>زومة الريزوس</th>
                    <th>تاريخ آخر تبرع</th>
                    <th>تحديث آخر تبرع</th>
                </tr>
                <?php foreach ($search_results as $donor): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($donor['first_name']); ?></td>
                        <td><?php echo htmlspecialchars($donor['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($donor['date_of_birth']); ?></td>
                        <td><?php echo htmlspecialchars($donor['blood_type']); ?></td>
                        <td><?php echo htmlspecialchars($donor['rh_factor']); ?></td>
                        <td><?php echo $donor['last_donation_date'] ? htmlspecialchars($donor['last_donation_date']) : 'لم يتبرع بعد'; ?></td>
                        <td>
                            <form action="nurse.php" method="post">
                                <input type="hidden" name="update" value="1">
                                <input type="hidden" name="donor_id" value="<?php echo $donor['id']; ?>">
                                <input type="date" name="last_donation_date" required>
                                <input type="submit" value="تحديث">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
