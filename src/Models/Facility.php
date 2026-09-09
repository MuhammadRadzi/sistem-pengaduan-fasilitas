<?php

namespace App\Models;

use App\Config\Database;
use PDO;    

class Facility
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM facilities ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM facilities WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($name, $location, $description)
    {
        $stmt = $this->db->prepare("INSERT INTO facilities (name, location, description) VALUES (?, ?, ?)");
        return $stmt->execute([$name, $location, $description]);
    }

    public function update($id, $name, $location, $description)
    {
        $stmt = $this->db->prepare("UPDATE facilities SET name = ?, location = ?, description = ? WHERE id = ?");
        return $stmt->execute([$name, $location, $description, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM facilities WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
