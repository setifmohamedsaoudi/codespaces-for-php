<?php
// query.php

// تعريف مسار ملف JSON
define('USER_FILE', 'users.json');

// قائمة الولايات الجزائرية
$states = [
    "01" => "أدرار",
    "02" => "الشلف",
    "03" => "الأغواط",
    "04" => "أم البواقي",
    "05" => "باتنة",
    "06" => "بجاية",
    "07" => "بسكرة",
    "08" => "بشار",
    "09" => "البليدة",
    "10" => "البويرة",
    "11" => "تمنراست",
    "12" => "تبسة",
    "13" => "تلمسان",
    "14" => "تيارت",
    "15" => "تيزي وزو",
    "16" => "الجزائر العاصمة",
    "17" => "الجلفة",
    "18" => "جيجل",
    "19" => "سطيف",
    "20" => "سعيدة",
    "21" => "سكيكدة",
    "22" => "سيدي بلعباس",
    "23" => "عنابة",
    "24" => "قالمة",
    "25" => "قسنطينة",
    "26" => "المدية",
    "27" => "مستغانم",
    "28" => "المسيلة",
    "29" => "معسكر",
    "30" => "ورقلة",
    "31" => "وهران",
    "32" => "البيض",
    "33" => "إليزي",
    "34" => "برج بوعريريج",
    "35" => "بومرداس",
    "36" => "الطارف",
    "37" => "تندوف",
    "38" => "تيسمسيلت",
    "39" => "الوادي",
    "40" => "خنشلة",
    "41" => "سوق أهراس",
    "42" => "تيبازة",
    "43" => "ميلة",
    "44" => "عين الدفلى",
    "45" => "النعامة",
    "46" => "عين تموشنت",
    "47" => "غرداية",
    "48" => "غليزان",
    "49" => "تيميمون",
    "50" => "برج باجي مختار",
    "51" => "أولاد جلال",
    "52" => "بني عباس",
    "53" => "عين صالح",
    "54" => "عين قزام",
    "55" => "تقرت",
    "56" => "جانت",
    "57" => "المغير",
    "58" => "المنيعة"
];

// دالة لقراءة البيانات من ملف JSON
function readUsers() {
    if (!file_exists(USER_FILE)) {
        return [];
    }
    $json = file_get_contents(USER_FILE);
    $data = json_decode($json, true);
    return $data ? $data : [];
}

$results = [];
$error_message = "";

// معالجة استعلام المستخدمين
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['query_users'])) {
    $query_state_id = $_POST['query_state'];
    $age_min = intval($_POST['age_min']);
    $age_max = intval($_POST['age_max']);

    // التحقق من صحة البيانات
    if (empty($query_state_id) || $age_min < 0 || $age_max < 0 || $age_min > $age_max) {
        $error_message = "يرجى إدخال معايير صحيحة للاستعلام.";
    } elseif (!array_key_exists($query_state_id, $states)) {
        $error_message = "الولاية المختارة غير صحيحة.";
    } else {
        // قراءة البيانات الحالية
        $users = readUsers();

        // حساب نطاق تواريخ الميلاد بناءً على الفئة العمرية
        $today = new DateTime();
        $date_min = $today->sub(new DateInterval("P{$age_max}Y"))->format('Y-m-d');
        $today = new DateTime(); // إعادة تعيين التاريخ الحالي
        $date_max = $today->sub(new DateInterval("P{$age_min}Y"))->format('Y-m-d');

        // فلترة المستخدمين بناءً على الشروط
        foreach ($users as $user) {
            if ($user['state_id'] == $query_state_id &&
                $user['date_of_birth'] <= $date_max &&
                $user['date_of_birth'] >= $date_min) {
                $results[] = $user;
            }
        }

        if (empty($results)) {
            $error_message = "لا توجد نتائج مطابقة.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- وسم viewport -->
    <title>استعلام عن المستخدمين</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>استعلام عن المستخدمين</h2>
        <?php if ($error_message && isset($_POST['query_users'])): ?>
            <div class="message error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <form method="post" action="">
            <label for="query_state">اختر الولاية:</label>
            <select id="query_state" name="query_state" required>
                <option value="">اختر الولاية</option>
                <?php
                    foreach ($states as $id => $name) {
                        echo "<option value=\"$id\">$name</option>";
                    }
                ?>
            </select>

            <label for="age_min">الحد الأدنى للعمر:</label>
            <input type="number" id="age_min" name="age_min" min="0" required>

            <label for="age_max">الحد الأقصى للعمر:</label>
            <input type="number" id="age_max" name="age_max" min="0" required>

            <input type="submit" name="query_users" value="استعلام">
        </form>

        <?php if (!empty($results)): ?>
            <h3>قائمة المستخدمين:</h3>
            <div style="overflow-x:auto;">
                <table>
                    <tr>
                        <th>الاسم</th>
                        <th>اللقب</th>
                        <th>تاريخ الميلاد</th>
                        <th>الولاية</th>
                        <th>العمر</th>
                    </tr>
                    <?php
                        foreach ($results as $user) {
                            // حساب العمر
                            $birthdate = new DateTime($user['date_of_birth']);
                            $today = new DateTime();
                            $age = $today->diff($birthdate)->y;

                            echo "<tr>";
                            echo "<td data-label='الاسم'>" . htmlspecialchars($user['first_name']) . "</td>";
                            echo "<td data-label='اللقب'>" . htmlspecialchars($user['last_name']) . "</td>";
                            echo "<td data-label='تاريخ الميلاد'>" . htmlspecialchars($user['date_of_birth']) . "</td>";
                            echo "<td data-label='الولاية'>" . htmlspecialchars($states[$user['state_id']]) . "</td>";
                            echo "<td data-label='العمر'>" . htmlspecialchars($age) . "</td>";
                            echo "</tr>";
                        }
                    ?>
                </table>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
