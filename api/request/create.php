<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');

include_once '../../config/database.php';
include_once '../../models/Request.php';

$database = new Database();
$db = $database->connect();

$request = new Request($db);

$data = json_decode(file_get_contents("php://input"));

if (
    !empty($data->patient_name) &&
    !empty($data->blood_type) &&
    !empty($data->units_needed)
) {
    $request->patient_name = $data->patient_name;
    $request->blood_type = $data->blood_type;
    $request->units_needed = $data->units_needed;
    $request->status = 'pending';
    $request->request_date = date('Y-m-d H:i:s');

    if($request->create()) {
        http_response_code(201);
        echo json_encode(array("message" => "Blood request created successfully."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create blood request."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create blood request. Data is incomplete."));
}