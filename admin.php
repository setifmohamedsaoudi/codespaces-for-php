<?php
// admin.php
session_start();

// التحقق من تسجيل الدخول وصلاحية الدور
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// مسارات ملفات JSON
$donorsFile = 'donors.json';
$requestsFile = 'requests.json';

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

$success_message = "";
$error_message = "";

// معالجة نموذج الإدخال
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_donor'])) {
        $first_name = trim($_POST['first_name']);
        $last_name = trim($_POST['last_name']);
        $date_of_birth = $_POST['date_of_birth'];
        $blood_type = $_POST['blood_type'];
        $rh_factor = $_POST['rh_factor'];
    
        if ($first_name && $last_name && $date_of_birth && $blood_type && $rh_factor) {
            $donors = loadData($donorsFile);
            $newDonor = [
                'id' => uniqid(),
                'first_name' => $first_name,
                'last_name' => $last_name,
                'date_of_birth' => $date_of_birth,
                'blood_type' => $blood_type,
                'rh_factor' => $rh_factor,
                'last_donation_date' => null
            ];
            $donors[] = $newDonor;
            saveData($donorsFile, $donors);
            $success_message = "تمت إضافة المتبرع بنجاح!";
        } else {
            $error_message = "يرجى ملء جميع الحقول.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>إدارة المتبرعين بالدم</title>
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
        .message { 
            padding: 10px; 
            margin-bottom: 20px; 
            border-radius: 5px;
        }
        .success { 
            background-color: #d4edda; 
            color: #155724; 
        }
        .error { 
            background-color: #f8d7da; 
            color: #721c24; 
        }
        form { 
            border: 1px solid #ccc; 
            padding: 20px; 
            border-radius: 5px; 
            margin-bottom: 40px;
        }
        label { 
            display: block; 
            margin-top: 10px; 
        }
        input, select { 
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
        .requests { 
            margin-top: 40px; 
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
        
        <h2>الرجاء إدخال المعلومات الخاصة بالمترشحين</h2>
        
        <?php if ($success_message): ?>
            <div class="message success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if ($error_message): ?>
            <div class="message error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <form action="admin.php" method="post">
            <input type="hidden" name="add_donor" value="1">
            
            <label for="first_name">الاسم:</label>
            <input type="text" id="first_name" name="first_name" required>
            
            <label for="last_name">اللقب:</label>
            <input type="text" id="last_name" name="last_name" required>
            
            <label for="date_of_birth">تاريخ الميلاد:</label>
            <input type="date" id="date_of_birth" name="date_of_birth" required>
            
            <label for="blood_type">الزَّمَة الدموية:</label>
            <select id="blood_type" name="blood_type" required>
                <option value="">اختر الزَّمَة</option>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="AB">AB</option>
                <option value="O">O</option>
            </select>
            
            <label for="rh_factor">زَّمَة الريزوس:</label>
            <select id="rh_factor" name="rh_factor" required>
                <option value="">اختر الريزوس</option>
                <option value="Positive">موجب</option>
                <option value="Negative">سالب</option>
            </select>
            
            <input type="submit" value="إرسال">
        </form>
        
        <div class="requests">
            <h3>طلبات الزوار لطلب رقم الهاتف</h3>
            <?php
                $requests = loadData($requestsFile);
                if (count($requests) > 0):
            ?>
            <table>
                <tr>
                    <th>اسم المتبرع</th>
                    <th>اسم الزائر</th>
                    <th>رقم اتصال الزائر</th>
                    <th>تاريخ الطلب</th>
                    <th>الحالة</th>
                </tr>
                <?php foreach ($requests as $request): ?>
                    <?php
                        // إيجاد المتبرع
                        $donors = loadData($donorsFile);
                        $donor = null;
                        foreach ($donors as $d) {
                            if ($d['id'] == $request['donor_id']) {
                                $donor = $d;
                                break;
                            }
                        }
                    ?>
                    <tr>
                        <td><?php echo $donor ? htmlspecialchars($donor['first_name'] . ' ' . $donor['last_name']) : 'غير معروف'; ?></td>
                        <td><?php echo htmlspecialchars($request['visitor_name']); ?></td>
                        <td><?php echo htmlspecialchars($request['visitor_contact']); ?></td>
                        <td><?php echo htmlspecialchars($request['request_date']); ?></td>
                        <td><?php echo htmlspecialchars($request['status']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <?php else: ?>
                <p>لا توجد طلبات حالياً.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
