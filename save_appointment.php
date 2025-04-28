<?php
require 'config.php';
require 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json');

$key = 'titkoskulcs123';

if (!isset($_COOKIE['token'])) {
    echo json_encode(["status" => "error", "message" => "Nincs jogosultság! Jelentkezz be."]);
    exit();
}

try {
    $decoded = JWT::decode($_COOKIE['token'], new Key($key, 'HS256'));
    $userId = $decoded->id ?? null;

    if (!$userId) {
        throw new Exception("Hiányzó felhasználói azonosító.");
    }

} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Érvénytelen token! Jelentkezz be újra."]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $date = $_POST['date'] ?? '';
    $time = $_POST['time'] ?? '';

    if (empty($name) || empty($date) || empty($time)) {
        echo json_encode(["status" => "error", "message" => "Hiányzó adatok!"]);
        exit();
    }

    $dayOfWeek = date('N', strtotime($date));
    $hour = (int)date('H', strtotime($time));
    $minute = (int)date('i', strtotime($time));

    if ($dayOfWeek > 5 || $hour < 9 || $hour >= 16 || !in_array($minute, [0, 30])) {
        echo json_encode(["status" => "error", "message" => "Csak hétköznap 9:00-16:00 között, félórás bontásban lehet foglalni!"]);
        exit();
    }

    $check_sql = "SELECT * FROM appointments WHERE appointment_date = ? AND appointment_time = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ss", $date, $time);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Ez az időpont már foglalt!"]);
    } else {
        $insert_sql = "INSERT INTO appointments (customer_name, appointment_date, appointment_time, user_id) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("sssi", $name, $date, $time, $userId);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Foglalás sikeres!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Hiba történt a foglalás során!"]);
        }
    }

    $stmt->close();
    $conn->close();
}
?>
