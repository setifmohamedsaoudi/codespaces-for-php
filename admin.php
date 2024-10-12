<?php
// admin.php
session_start();

// التحقق من تسجيل الدخول
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

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

// دالة لكتابة البيانات إلى ملف JSON
function writeUsers($users) {
    $json = json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents(USER_FILE, $json);
}

$success_message = "";
$error_message = "";

// معالجة إدخال المستخدم
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_user'])) {
    // جمع البيانات المدخلة
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $date_of_birth = $_POST['date_of_birth'];
    $state_id = $_POST['state'];

    // التحقق من صحة البيانات
    if (empty($first_name) || empty($last_name) || empty($date_of_birth) || empty($state_id)) {
        $error_message = "يرجى ملء جميع الحقول.";
    } elseif (!array_key_exists($state_id, $states)) {
        $error_message = "الولاية المختارة غير صحيحة.";
    } else {
        // قراءة البيانات الحالية
        $users = readUsers();

        // إضافة المستخدم الجديد
        $users[] = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'date_of_birth' => $date_of_birth,
            'state_id' => $state_id
        ];

        // كتابة البيانات المحدثة
        writeUsers($users);

        $success_message = "تم إضافة المستخدم بنجاح.";
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- وسم viewport -->
    <title>لوحة تحكم الإدمن</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>لوحة تحكم الإدمن</h2>
        <p style="text-align: left;"><a href="logout.php" style="color: #f44336; text-decoration: none;">تسجيل الخروج</a></p>
        <?php if ($success_message): ?>
            <div class="message success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div class="message error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <form method="post" action="">
            <label for="first_name">الاسم:</label>
            <input type="text" id="first_name" name="first_name" required>

            <label for="last_name">اللقب:</label>
            <input type="text" id="last_name" name="last_name" required>

            <label for="date_of_birth">تاريخ الميلاد:</label>
            <input type="date" id="date_of_birth" name="date_of_birth" required>

            <label for="state">الولاية:</label>
            <select id="state" name="state" required>
                <option value="">اختر الولاية</option>
                <?php
                    foreach ($states as $id => $name) {
                        echo "<option value=\"$id\">$name</option>";
                    }
                ?>
            </select>

            <input type="submit" name="submit_user" value="إضافة المستخدم">
        </form>
    </div>
</body>
</html>
