<?php
require 'config.php';
require 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$key = 'titkoskulcs123';

if (isset($_COOKIE['token'])) {
    try {
        $decoded = JWT::decode($_COOKIE['token'], new Key($key, 'HS256'));

        $appointmentMenu = '<li class="nav-item"><a class="nav-link" href="appointment.php">Időpontfoglalás</a></li>';

        if ($decoded->role === 'admin') {
            $adminMenu = '<li class="nav-item"><a class="nav-link" href="admin.php">Admin</a></li>';
        }

        $authMenu = '<li class="nav-item"><a class="nav-link" href="logout.php">Kijelentkezés</a></li>';

    } catch (Exception $e) {
        setcookie("token", "", time() - 3600, "/");
        header("Location: login.php");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}

$result = $conn->query("SELECT * FROM appointments ORDER BY appointment_date ASC, appointment_time ASC");
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Admin</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .admin-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            margin: 50px auto;
            max-width: 900px;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
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

<div class="container admin-container">
    <h2>Foglalások kezelése</h2>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Név</th>
                <th>Dátum</th>
                <th>Időpont</th>
                <th>Művelet</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['customer_name']) ?></td>
                        <td><?= htmlspecialchars($row['appointment_date']) ?></td>
                        <td><?= htmlspecialchars($row['appointment_time']) ?></td>
                        <td>
                            <form method="post" action="delete_appointment.php" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <button type="submit" class="btn btn-danger">Törlés</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="4">Nincs elérhető foglalás.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
