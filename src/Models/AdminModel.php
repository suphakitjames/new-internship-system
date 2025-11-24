<?php
// src/Models/AdminModel.php

namespace Models;

class AdminModel extends Model
{

    public function getDashboardStats()
    {
        $stats = [];

        $stmt = $this->pdo->query("SELECT COUNT(*) FROM students");
        $stats['student_count'] = $stmt->fetchColumn();

        $stmt = $this->pdo->query("SELECT COUNT(*) FROM companies WHERE status = 'approved'");
        $stats['company_count'] = $stmt->fetchColumn();

        $stmt = $this->pdo->query("SELECT COUNT(*) FROM internship_requests WHERE status = 'pending'");
        $stats['pending_requests'] = $stmt->fetchColumn();

        $stmt = $this->pdo->query("SELECT COUNT(*) FROM companies WHERE status = 'pending'");
        $stats['pending_companies'] = $stmt->fetchColumn();

        return $stats;
    }

    public function getProvinceStats()
    {
        $stmt = $this->pdo->query("
            SELECT p.name_th as province, COUNT(c.id) as count 
            FROM companies c
            LEFT JOIN provinces p ON c.province = p.id
            WHERE c.status = 'approved'
            GROUP BY c.province, p.name_th
            ORDER BY count DESC
            LIMIT 15
        ");
        $stats = $stmt->fetchAll();

        $labels = [];
        $counts = [];
        foreach ($stats as $stat) {
            $labels[] = $stat['province'] ?? 'ไม่ระบุ';
            $counts[] = $stat['count'];
        }

        return ['labels' => $labels, 'counts' => $counts];
    }
}
