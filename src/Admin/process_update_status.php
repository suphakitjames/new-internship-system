<?php
// src/Admin/process_update_status.php

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php?page=auth&action=admin_login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php?page=admin&action=internship_requests');
    exit;
}

$request_id = $_POST['request_id'] ?? '';
$status_type = $_POST['status_type'] ?? '';
$status = $_POST['status'] ?? '';

if (empty($request_id) || empty($status_type) || empty($status)) {
    $_SESSION['error'] = 'ข้อมูลไม่ครบถ้วน';
    header('Location: index.php?page=admin&action=internship_requests');
    exit;
}

try {
    $pdo->beginTransaction();
    
    // อัพเดทสถานะตามประเภท
    switch ($status_type) {
        case 'faculty':
            $stmt = $pdo->prepare("
                UPDATE internship_requests 
                SET faculty_approval_status = ?,
                    faculty_approval_date = NOW()
                WHERE id = ?
            ");
            $stmt->execute([$status, $request_id]);
            $message = 'อัพเดทผลการพิจารณาของคณะเรียบร้อยแล้ว';
            break;
            
        case 'company':
            $stmt = $pdo->prepare("
                UPDATE internship_requests 
                SET company_response_status = ?,
                    company_response_date = NOW()
                WHERE id = ?
            ");
            $stmt->execute([$status, $request_id]);
            $message = 'อัพเดทผลการตอบรับจากหน่วยงานเรียบร้อยแล้ว';
            break;
            
        case 'document':
            $stmt = $pdo->prepare("
                UPDATE internship_requests 
                SET document_response_status = ?,
                    document_response_date = NOW()
                WHERE id = ?
            ");
            $stmt->execute([$status, $request_id]);
            $message = 'อัพเดทผลการตอบกลับเอกสารเรียบร้อยแล้ว';
            break;
            
        default:
            throw new Exception('ประเภทสถานะไม่ถูกต้อง');
    }
    
    $pdo->commit();
    $_SESSION['success'] = $message;
    
} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['error'] = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
}

header('Location: index.php?page=admin&action=internship_requests');
exit;
