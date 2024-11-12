<?php
class Donor {
    private $conn;
    private $table = 'donors';

    public $id;
    public $name;
    public $age;
    public $blood_type;
    public $donation_date;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . "
                SET
                    name = :name,
                    age = :age,
                    blood_type = :blood_type,
                    donation_date = :donation_date";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':age', $this->age);
        $stmt->bindParam(':blood_type', $this->blood_type);
        $stmt->bindParam(':donation_date', $this->donation_date);

        return $stmt->execute();
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY donation_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}