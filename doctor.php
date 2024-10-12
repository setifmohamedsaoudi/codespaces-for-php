<?php
session_start();
// التحقق مما إذا كان المستخدم قد سجل الدخول كطبيب
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'doctor') {
    header("Location: login.php?role=doctor");
    exit();
}

// إعداد مصفوفة للمتبرعين (بدون قاعدة بيانات)
$donors = isset($_SESSION['donors']) ? $_SESSION['donors'] : [];

// البحث عن متبرع حسب الزمرة الدموية
$searchResults = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_by_blood_type'])) {
    $bloodType = $_POST['blood_type'];

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
        body { 
            font-family: Arial, sans-serif; 
            direction: rtl; 
            background-color: #f2f2f2; 
        }
        h1 { color: #4CAF50; }
        a { text-decoration: none; color: #fff; background-color: #4CAF50; padding: 10px; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; }
        th
