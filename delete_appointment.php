<?php
require 'config.php';
require 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$key = 'titkoskulcs123';

if (!isset($_COOKIE['token'])) {
    header("Location: login.php");
    exit();
}

try {
    $decoded = JWT::decode($_COOKIE['token'], new Key($key, 'HS256'));

    if ($decoded->role !== 'admin') {
        header("Location: login.php");
        exit();
    }

} catch (Exception $e) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    $stmt = $conn->prepare("DELETE FROM appointments WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

header("Location: admin.php");
exit();
?>
