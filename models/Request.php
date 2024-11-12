<?php
class Request {
    private $conn;
    private $table = 'requests';

    public $id;
    public $patient_name;
    public $blood_type;
    public $units_needed;
    public $status;
    public $request_date;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . "
                SET
                    patient_name = :patient_name,
                    blood_type = :blood_type,
                    units_needed = :units_needed,
                    status = :status,
                    request_date = :request_date";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':patient_name', $this->patient_name);
        $stmt->bindParam(':blood_type', $this->blood_type);
        $stmt->bindParam(':units_needed', $this->units_needed);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':request_date', $this->request_date);

        return $stmt->execute();
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY request_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function updateStatus() {
        $query = "UPDATE " . $this->table . "
                SET status = :status
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }
}