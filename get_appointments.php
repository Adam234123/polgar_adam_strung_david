<?php
require 'config.php';
require 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json');

$key = 'titkoskulcs123';

if (!isset($_COOKIE['token'])) {
    echo json_encode(["status" => "error", "message" => "Hozzáférés megtagadva!"]);
    exit();
}

try {
    $decoded = JWT::decode($_COOKIE['token'], new Key($key, 'HS256'));

} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Érvénytelen vagy lejárt token. Jelentkezz be újra!"]);
    exit();
}

$sql = "SELECT appointment_date, appointment_time FROM appointments";
$result = $conn->query($sql);

$appointments = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $appointments[] = $row;
    }
}

echo json_encode($appointments);

$conn->close();
?>
