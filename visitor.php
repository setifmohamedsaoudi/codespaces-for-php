<?php
// visitor.php

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

$search_results = [];
$action_message = "";

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['search'])) {
    $blood_type = $_GET['search'];
    
    if ($blood_type) {
        $donors = loadData($donorsFile);
        foreach ($donors as $donor) {
            if ($donor['blood_type'] === $blood_type) {
                $search_results[] = $donor;
            }
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['request_phone'])) {
    $donor_id = $_POST['donor_id'];
    $visitor_name = trim($_POST['visitor_name']);
    $visitor_contact = trim($_POST['visitor_contact⬤
