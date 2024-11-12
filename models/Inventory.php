<?php
class Inventory {
    private $conn;
    private $table = 'inventory';

    public $blood_type;
    public $units;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function updateUnits() {
        $query = "UPDATE " . $this->table . "
                SET units = units + :units
                WHERE blood_type = :blood_type";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':units', $this->units);
        $stmt->bindParam(':blood_type', $this->blood_type);

        return $stmt->execute();
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}