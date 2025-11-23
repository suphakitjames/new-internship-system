<?php
// src/Student/process_add_company.php
session_start();
require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../index.php?page=student&action=add_company');
    exit;
}

if (!isset($_SESSION['user'])) {
    $_SESSION['error'] = "กรุณาเข้าสู่ระบบ";
    header('Location: ../../index.php?page=auth&action=login');
    exit;
}

$name = $_POST['name'] ?? '';
$address = $_POST['address'] ?? '';
$province = $_POST['province'] ?? '';
$contact_person = $_POST['contact_person'] ?? '';
$phone = $_POST['phone'] ?? '';
$email = $_POST['email'] ?? '';
$description = $_POST['description'] ?? '';
$created_by = $_SESSION['user']['id'];

if (empty($name) || empty($address) || empty($province) || empty($contact_person) || empty($phone)) {
    echo "<script>alert('กรุณากรอกข้อมูลที่จำเป็นให้ครบถ้วน'); window.history.back();</script>";
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO companies (name, address, province, contact_person, phone, email, description, status, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', ?)");
    $stmt->execute([$name, $address, $province, $contact_person, $phone, $email, $description, $created_by]);

    echo "<script>
        alert('บันทึกข้อมูลเรียบร้อยแล้ว กรุณารอการตรวจสอบและอนุมัติจากเจ้าหน้าที่'); 
        window.location.href='../../index.php?page=internships';
    </script>";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
