<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'doctor') {
    header("Location: login.php");
    exit();
}

$donors = isset($_SESSION['donors']) ? $_SESSION['donors'] : [];
$searchResult = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_donor'])) {
    $bloodType = $_POST['blood_type'];

    foreach ($donors as $donor) {
        if ($donor['blood_type'] === $bloodType) {
            $searchResult[] = $donor;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>صفحة الطبيب</title
