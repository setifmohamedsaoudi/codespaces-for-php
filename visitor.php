<?php
// visitor.php

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

$search_results = [];
$action_message = "";

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
    
    if (isset($_POST['request_phone'])) {
        $donor_id = $_POST['donor_id'];
        $visitor_name = trim($_POST['visitor_name']);
        $visitor_contact = trim($_POST['visitor_contact']);
        
        if ($donor_id && $visitor_name && $visitor_contact) {
            $requests = loadData($requestsFile);
            $newRequest = [
                'id' => uniqid(),
                'donor_id' => $donor_id,
                'visitor_name' => $visitor_name,
                'visitor_contact' => $visitor_contact,
                'request_date' => date('Y-m-d H:i:s'),
                'status' => 'Pending'
            ];
            $requests[] = $newRequest;
            saveData($requestsFile, $requests);
            $action_message = "تم إرسال طلبك إلى الأدمن. سيتم التواصل معك قريباً.";
        } else {
            $action_message = "يرجى ملء جميع الحقول المطلوبة.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>قسم الزوار</title>
    <style>
        body { font-family: Arial, sans-serif; direction: rtl; }
        .container { width: 60%; margin: auto; }
        .message { padding: 10px; margin-bottom: 20px; }
        .success { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
        form { border: 1px solid #ccc; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        label { display: block; margin-top: 10px; }
        input, select { width: 100%; padding: 8px; margin-top: 5px; }
        input[type="submit"] { width: auto; background-color: #4CAF50; color: white; border: none; cursor: pointer; margin-top: 10px; }
        input[type="submit"]:hover { background-color: #45a049; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
        .request-form { border: 1px solid #ccc; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>قسم الزوار</h2>
        
        <?php if ($action_message): ?>
            <div class="message <?php echo strpos($action_message, 'تم') !== false ? 'success' : 'error'; ?>">
                <?php echo $action_message; ?>
            </div>
        <?php endif; ?>
        
        <!-- نموذج البحث -->
        <form action="visitor.php" method="post">
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
                    <th>طلب رقم الهاتف</th>
                </tr>
                <?php foreach ($search_results as $donor): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($donor['first_name']); ?></td>
                        <td><?php echo htmlspecialchars($donor['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($donor['date_of_birth']); ?></td>
                        <td><?php echo htmlspecialchars($donor['rh_factor']); ?></td>
                        <td>
                            <button onclick="toggleRequestForm('<?php echo $donor['id']; ?>')">اطلب رقم الهاتف</button>
                            
                            <div id="form-<?php echo $donor['id']; ?>" class="request-form" style="display: none;">
                                <form action="visitor.php" method="post">
                                    <input type="hidden" name="request_phone" value="1">
                                    <input type="hidden" name="donor_id" value="<?php echo $donor['id']; ?>">
                                    
                                    <label for="visitor_name_<?php echo $donor['id']; ?>">اسمك:</label>
                                    <input type="text" id="visitor_name_<?php echo $donor['id']; ?>" name="visitor_name" required>
                                    
                                    <label for="visitor_contact_<?php echo $donor['id']; ?>">رقم الاتصال:</label>
                                    <input type="text" id="visitor_contact_<?php echo $donor['id']; ?>" name="visitor_contact" required>
                                    
                                    <input type="submit" value="إرسال الطلب">
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])): ?>
            <p>لا يوجد متبرعين بالزَّمَة المختارة.</p>
        <?php endif; ?>
    </div>
    
    <script>
        function toggleRequestForm(donorId) {
            var form = document.getElementById('form-' + donorId);
            if (form.style.display === 'none') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        }
    </script>
</body>
</html>
