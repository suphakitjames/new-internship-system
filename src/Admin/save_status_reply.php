<?php
// src/Admin/save_status_reply.php

header('Content-Type: application/json');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_and_reply'])) {
    try {
        $request_id = $_POST['request_id'];
        $faculty_status = $_POST['faculty_status'];
        $company_status = $_POST['company_status'];
        $document_status = $_POST['document_status'];
        $document_comment = $_POST['document_comment'] ?? '';

        // Validate inputs
        if (empty($request_id) || empty($faculty_status) || empty($company_status) || empty($document_status)) {
            throw new Exception('Missing required fields');
        }

        // Update internship_requests table
        $stmt = $pdo->prepare("
            UPDATE internship_requests 
            SET 
                faculty_approval_status = ?,
                faculty_approval_date = NOW(),
                company_response_status = ?,
                company_response_date = NOW(),
                document_response_status = ?,
                document_response_date = NOW(),
                document_comment = ?,
                status = CASE 
                    WHEN ? = 'approved' AND ? = 'accepted' AND ? = 'approved' THEN 'approved'
                    WHEN ? = 'rejected' OR ? = 'rejected' OR ? = 'rejected' THEN 'rejected'
                    ELSE status
                END
            WHERE id = ?
        ");

        $stmt->execute([
            $faculty_status,
            $company_status,
            $document_status,
            $document_comment,
            $faculty_status, $company_status, $document_status, // For CASE approved
            $faculty_status, $company_status, $document_status, // For CASE rejected
            $request_id
        ]);

        echo json_encode(['success' => true]);

    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
