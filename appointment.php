<?php
require 'config.php';
require 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$key = 'titkoskulcs123';

$adminMenu = '';

if (!isset($_COOKIE['token'])) {
    header("Location: login.php");
    exit();
}

try {
    $decoded = JWT::decode($_COOKIE['token'], new Key($key, 'HS256'));

    $appointmentMenu = '<li class="nav-item"><a class="nav-link" href="appointment.php">Időpontfoglalás</a></li>';
    if ($decoded->role === 'admin') {
        $adminMenu = '<li class="nav-item"><a class="nav-link" href="admin.php">Admin</a></li>';
    }
    $authMenu = '<li class="nav-item"><a class="nav-link" href="logout.php">Kijelentkezés</a></li>';

} catch (Exception $e) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Időpontfoglalás</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .booking-container {
            background-color: rgba(255, 255, 255, 0.85);
            border-radius: 10px;
            padding: 30px;
            margin-top: 50px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        h1 { font-size: 28px; color: #f0a500; margin-bottom: 20px; }
        label { font-weight: bold; margin-top: 10px; }
        .btn-primary { background-color: #f0a500; border: none; }
        .btn-primary:hover { background-color: #e69500; }
        #response-message { display: none; }
    </style>
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

<div class="container booking-container">
    <h1>Időpontfoglalás</h1>
    <form id="booking-form">
        <div class="form-group">
            <label for="name">Név:</label>
            <input type="text" id="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="date">Válassz dátumot:</label>
            <input type="date" id="date" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="time">Válassz időpontot:</label>
            <select id="time" class="form-control" required>
                <option value="">Előbb válassz dátumot...</option>
            </select>
        </div>
        <button type="button" class="btn btn-primary mt-3" onclick="submitBooking()">Foglalás</button>
    </form>
    <p id="response-message" class="alert mt-3"></p>
</div>

<script>
    document.getElementById("date").addEventListener("change", updateTimes);

    function updateTimes() {
        const date = document.getElementById("date").value;
        const timeSelect = document.getElementById("time");
        timeSelect.innerHTML = "";

        if (!date) return;

        const day = new Date(date).getDay();
        if (day === 0 || day === 6) {
            const option = document.createElement("option");
            option.textContent = "Csak hétköznapokon lehet foglalni!";
            option.disabled = true;
            timeSelect.appendChild(option);
            return;
        }

        fetch("get_appointments.php")
            .then(response => response.json())
            .then(data => {
                const bookedTimes = data
                    .filter(a => a.appointment_date === date)
                    .map(a => a.appointment_time);

                const startHour = 9, endHour = 16;
                for (let hour = startHour; hour < endHour; hour++) {
                    ["00", "30"].forEach(min => {
                        const time = `${hour.toString().padStart(2, '0')}:${min}`;
                        if (!bookedTimes.includes(time)) {
                            const option = document.createElement("option");
                            option.value = time;
                            option.textContent = time;
                            timeSelect.appendChild(option);
                        }
                    });
                }
                if (timeSelect.options.length === 0) {
                    const option = document.createElement("option");
                    option.textContent = "Nincs elérhető időpont.";
                    option.disabled = true;
                    timeSelect.appendChild(option);
                }
            });
    }

    function submitBooking() {
        const name = document.getElementById("name").value;
        const date = document.getElementById("date").value;
        const time = document.getElementById("time").value;
        const responseMessage = document.getElementById("response-message");

        fetch("save_appointment.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `name=${name}&date=${date}&time=${time}`
        })
        .then(response => response.json())
        .then(data => {
            responseMessage.textContent = data.message;
            responseMessage.className = `alert alert-${data.status === "success" ? "success" : "danger"}`;
            responseMessage.style.display = "block";
            updateTimes();
        });
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
