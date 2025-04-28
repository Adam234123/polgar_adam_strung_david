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
    <title>Rólunk</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/about.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="kepek/logo1.png" alt="Logo">
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

<section class="about-section">
    <div class="about-container">
        <div class="text-box">
        <h2>Rólunk</h2>
                <p>
                    Üdvözlünk a Barbershop-ban!<br><br>
                    Mi nem csupán egy borbélyüzlet vagyunk – itt a hagyomány és a modern stílus találkozik, hogy minden vendégünknek egyedi élményt nyújthassunk. Szenvedéllyel végezzük a munkánkat, mert számunkra a borbélykodás több mint szakma: egy életérzés.
                </p>
                <p>
                    Csapatunk tapasztalt és kreatív borbélyokból áll, akik mindig készen állnak, hogy a legjobb formádba hozzanak. Legyen szó klasszikus frizuráról, modern hajvágásról, borotválásról vagy szakállformázásról, nálunk garantáltan a legmagasabb minőséget kapod.
                </p>
                <p>
                    Hangulatos, vintage stílusú üzletünkben minden vendégünk otthon érezheti magát, miközben egy jó beszélgetés vagy akár egy frissítő ital társaságában szépül. Célunk, hogy ne csak jól nézz ki, de nagyszerűen is érezd magad, miután kilépsz tőlünk. Látogass el hozzánk, és tapasztald meg, milyen az igazi borbélyélmény!
                </p>
        </div>
        <div class="image-box">
            <img src="kepek/about.jpg" alt="Barbershop team">
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
