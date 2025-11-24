<?php
// src/Models/StudentModel.php

namespace Models;

class StudentModel extends Model
{

    public function getProfile($user_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM students WHERE user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetch();
    }

    public function getInternshipStatus($user_id)
    {
        $stmt = $this->pdo->prepare("
            SELECT ir.*, c.name as company_name 
            FROM internship_requests ir 
            JOIN companies c ON ir.company_id = c.id 
            WHERE ir.student_id = ? 
            ORDER BY ir.request_date DESC LIMIT 1
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetch();
    }

    public function getEvaluationResult($user_id)
    {
        $stmt = $this->pdo->prepare("SELECT result_status FROM evaluations WHERE student_id = ? ORDER BY evaluation_date DESC LIMIT 1");
        $stmt->execute([$user_id]);
        return $stmt->fetch();
    }
}
