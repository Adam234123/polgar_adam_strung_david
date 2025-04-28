<?php
require 'config.php';
require 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$key = 'titkoskulcs123';

$adminMenu = '';
$appointmentMenu = '';
$authMenu = '<li class="nav-item"><a class="nav-link" href="login.php">Bejelentkezés/Regisztráció</a></li>';

if (isset($_COOKIE['token'])) {
    try {
        $decoded = JWT::decode($_COOKIE['token'], new Key($key, 'HS256'));

        $appointmentMenu = '<li class="nav-item"><a class="nav-link" href="appointment.php">Időpontfoglalás</a></li>';

        if ($decoded->role === 'admin') {
            $adminMenu = '<li class="nav-item"><a class="nav-link" href="admin.php">Admin</a></li>';
        }

        $authMenu = '<li class="nav-item"><a class="nav-link" href="logout.php">Kijelentkezés</a></li>';

    } catch (Exception $e) {
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kezdőlap</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="kepek/logo.png" alt="Logo">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="contact.php">Kapcsolat</a></li>
                <li class="nav-item"><a class="nav-link" href="about.php">Rólunk</a></li>
                <?= $appointmentMenu ?>
                <?= $adminMenu ?>
                <?= $authMenu ?>
            </ul>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
const navLinks = document.querySelectorAll('.nav-link');
navLinks.forEach(link => {
    link.addEventListener('click', function() {
        navLinks.forEach(link => link.classList.remove('active'));
        this.classList.add('active');
    });
});
</script>

</body>
</html>
