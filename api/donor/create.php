<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');

include_once '../../config/database.php';
include_once '../../models/Donor.php';
include_once '../../models/Inventory.php';

$database = new Database();
$db = $database->connect();

$donor = new Donor($db);
$inventory = new Inventory($db);

$data = json_decode(file_get_contents("php://input"));

if (
    !empty($data->name) &&
    !empty($data->age) &&
    !empty($data->blood_type)
) {
    $donor->name = $data->name;
    $donor->age = $data->age;
    $donor->blood_type = $data->blood_type;
    $donor->donation_date = date('Y-m-d H:i:s');

    if($donor->create()) {
        // Update inventory
        $inventory->blood_type = $data->blood_type;
        $inventory->units = 1;
        $inventory->updateUnits();

        http_response_code(201);
        echo json_encode(array("message" => "Donor registered successfully."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to register donor."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to register donor. Data is incomplete."));
}