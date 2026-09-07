<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class Complaint
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getAll()
    {
        $query = "SELECT c.*, f.name as facility_name, f.location 
                  FROM complaints c 
                  JOIN facilities f ON c.facility_id = f.id 
                  ORDER BY c.id DESC";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $query = "SELECT c.*, f.name as facility_name, f.location 
                  FROM complaints c 
                  JOIN facilities f ON c.facility_id = f.id 
                  WHERE c.id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($facility_id, $reporter_name, $issue_description)
    {
        $stmt = $this->db->prepare("INSERT INTO complaints (facility_id, reporter_name, issue_description, status) VALUES (?, ?, ?, 'Lapor Masalah')");
        return $stmt->execute([$facility_id, $reporter_name, $issue_description]);
    }

    public function updateStatusAndNote($id, $status, $action_note)
    {
        $stmt = $this->db->prepare("UPDATE complaints SET status = ?, action_note = ? WHERE id = ?");
        return $stmt->execute([$status, $action_note, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM complaints WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
