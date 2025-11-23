<?php
// src/Internships/submit_request.php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php?page=internships');
    exit;
}

$user = $_SESSION['user'] ?? null;

// Check if logged in and is student
if (!$user || $user['role'] !== 'student') {
    $_SESSION['error'] = "กรุณาเข้าสู่ระบบในฐานะนิสิตก่อนสมัคร";
    header('Location: index.php?page=auth&action=login');
    exit;
}

$company_id = isset($_POST['company_id']) ? (int)$_POST['company_id'] : 0;

if (!$company_id) {
    $_SESSION['error'] = "ข้อมูลบริษัทไม่ถูกต้อง";
    header('Location: index.php?page=internships');
    exit;
}

// Check for existing pending or approved requests
$req_query = "SELECT * FROM internship_requests WHERE student_id = ? AND status IN ('pending', 'approved')";
$req_stmt = $pdo->prepare($req_query);
$req_stmt->execute([$user['id']]);
if ($req_stmt->fetch()) {
    echo "<script>
        alert('คุณมีคำร้องที่อยู่ระหว่างดำเนินการหรือได้รับการอนุมัติแล้ว ไม่สามารถสมัครเพิ่มได้'); 
        window.location.href='index.php?page=internships';
    </script>";
    exit;
}

// Insert new request
$insert_query = "INSERT INTO internship_requests (student_id, company_id, status) VALUES (?, ?, 'pending')";
$stmt = $pdo->prepare($insert_query);

if ($stmt->execute([$user['id'], $company_id])) {
    echo "<script>
        alert('ส่งคำร้องสมัครฝึกงานเรียบร้อยแล้ว กรุณารอการอนุมัติจากเจ้าหน้าที่'); 
        window.location.href='index.php?page=student&action=dashboard';
    </script>";
} else {
    echo "<script>
        alert('เกิดข้อผิดพลาดในการบันทึกข้อมูล กรุณาลองใหม่อีกครั้ง'); 
        window.location.href='index.php?page=internships&action=detail&id=$company_id';
    </script>";
}
?>
