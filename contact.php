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
    <title>Kapcsolat</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/contact.css">
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

<div class="map-container">
    <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d177130.1356732281!2d18.085770964335182!3d46.07785274384329!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4742b111ea3252e3%3A0x400c4290c1e1200!2zUMOpY3M!5e0!3m2!1sen!2shu!4v1738061521441!5m2!1sen!2shu"
        allowfullscreen=""
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade">
    </iframe>
    <div class="info-box">
        <h2>Barber Shop</h2>
        <p>7622 Pécs<br>+36 30 123 4123</p>
        <h3>NYITVATARTÁS</h3>
        <p>
            Hétfő 09:00-20:00<br>
            Kedd 09:00-20:00<br>
            Szerda 09:00-20:00<br>
            Csütörtök 09:00-20:00<br>
            Péntek 09:00-20:00<br>
            Szombat 09:00-20:00<br>
            Vasárnap 10:00-18:00
        </p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
