<?php
// doctor.php
session_start();

// التحقق من تسجيل الدخول وصلاحية الدور
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'doctor') {
    header("Location: login.php");
    exit();
}

// مسارات ملفات JSON
$donorsFile = 'donors.json';

// وظيفة لتحميل البيانات من ملف JSON
function loadData($file) {
    if (!file_exists($file)) {
        file_put_contents($file, json_encode([]));
    }
    $json = file_get_contents($file);
    return json_decode($json, true);
}

$search_results = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['search'])) {
        $blood_type = $_POST['blood_type'];
        
        if ($blood_type) {
            $donors = loadData($donorsFile);
            foreach ($donors as $donor) {
                if ($donor['blood_type'] === $blood_type) {
                    $search_results[] = $donor;
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>قسم الطبيب</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            direction: rtl; 
            background-color: #f2f2f2; 
            margin: 0; 
            padding: 0;
        }
        .container { 
            width: 80%; 
            margin: 20px auto; 
            padding: 20px; 
            background-color: #fff; 
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 5px;
        }
        .logout {
            text-align: right;
            margin-bottom: 20px;
        }
        h2 { text-align: center; }
        form { 
            border: 1px solid #ccc; 
            padding: 20px; 
            border-radius: 5px; 
            margin-bottom: 20px;
        }
        label { 
            display: block; 
            margin-top: 10px; 
        }
        select { 
            width: 100%; 
            padding: 8px; 
            margin-top: 5px; 
            box-sizing: border-box;
        }
        input[type="submit"] { 
            width: auto; 
            background-color: #4CAF50; 
            color: white; 
            border: none; 
            cursor: pointer; 
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 15px;
        }
        input[type="submit"]:hover { 
            background-color: #45a049; 
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px;
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 8px; 
            text-align: center; 
        }
        th { 
            background-color: #f2f2f2; 
        }
        @media (max-width: 768px) {
            .container {
                width: 95%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logout">
            <p>مرحبًا، <?php echo htmlspecialchars($_SESSION['username']); ?>! <a href="logout.php">تسجيل الخروج</a></p>
        </div>
        
        <h2>قسم الطبيب</h2>
        
        <!-- نموذج البحث -->
        <form action="doctor.php" method="post">
            <input type="hidden" name="search" value="1">
            
            <label for="blood_type">اختر الزَّمَة الدموية:</label>
            <select id="blood_type" name="blood_type" required>
                <option value="">اختر الزَّمَة</option>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="AB">AB</option>
                <option value="O">O</option>
            </select>
            
            <input type="submit" value="بحث">
        </form>
        
        <?php if (count($search_results) > 0): ?>
            <h3>نتائج البحث</h3>
            <table>
                <tr>
                    <th>الاسم</th>
                    <th>اللقب</th>
                    <th>تاريخ الميلاد</th>
                    <th>زومة الريزوس</th>
                    <th>مدة آخر تبرع</th>
                </tr>
                <?php foreach ($search_results as $donor): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($donor['first_name']); ?></td>
                        <td><?php echo htmlspecialchars($donor['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($donor['date_of_birth']); ?></td>
                        <td><?php echo htmlspecialchars($donor['rh_factor']); ?></td>
                        <td>
                            <?php
                                if ($donor['last_donation_date']) {
                                    $lastDate = new DateTime($donor['last_donation_date']);
                                    $currentDate = new DateTime();
                                    $interval = $lastDate->diff($currentDate);
                                    echo $interval->format('%a يوم مضى');
                                } else {
                                    echo 'لم يتبرع بعد';
                                }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])): ?>
            <p>لا يوجد متبرعين بالزَّمَة المختارة.</p>
        <?php endif; ?>
    </div>
</body>
</html>
