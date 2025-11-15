<?php
class EquipmentModel
{
    private $conn;
    private $table = "lab_equipments";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Fetch all equipment
    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get one record
    public function getById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE equipment_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Insert
    public function create($name, $category, $quantity, $status)
    {
        $sql = "INSERT INTO {$this->table} (item_name, category, quantity, status) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$name, $category, $quantity, $status]);
    }

    // Update
    public function update($id, $name, $category, $quantity, $status)
    {
        $sql = "UPDATE {$this->table} 
                SET item_name = ?, category = ?, quantity = ?, status = ?
                WHERE equipment_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$name, $category, $quantity, $status, $id]);
    }

    // Delete
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE equipment_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }
}
