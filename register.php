<?php
require 'config.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (!empty($email) && !empty($password) && !empty($confirm_password)) {
        if ($password === $confirm_password) {

            $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';

            if (!preg_match($pattern, $password)) {
                $error = "A jelszónak legalább 8 karakterből kell állnia, és tartalmaznia kell kis- és nagybetűt, számot és speciális karaktert!";
            } else {

                $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
                $check_stmt->bind_param("s", $email);
                $check_stmt->execute();
                $check_stmt->store_result();

                if ($check_stmt->num_rows == 0) {

                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
                    $stmt->bind_param("ss", $email, $hashed_password);

                    if ($stmt->execute()) {

                        $mail = new PHPMailer(true);

                        try {
                            $mail->isSMTP();
                            $mail->Host = 'smtp.gmail.com';
                            $mail->SMTPAuth = true;
                            $mail->Username = 'barbershopprojekt@gmail.com';
                            $mail->Password = 'hygi wcix pwvf btlc';
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                            $mail->Port = 587;

                            $mail->setFrom('barbershopprojekt@gmail.com', 'Barbershop');
                            $mail->addAddress($email);

                            $mail->isHTML(true);
                            $mail->Subject = 'Sikeres regisztráció';
                            $mail->Body = 'Sikeresen regisztráltál a Barbershop rendszerébe!';

                            $mail->send();

                        } catch (Exception $e) {
                            $error = "Az email küldés sikertelen: " . $mail->ErrorInfo;
                        }

                        header("Location: login.php?success=1");
                        exit();

                    } else {
                        $error = "Hiba történt a regisztráció során!";
                    }

                    $stmt->close();
                } else {
                    $error = "Ez az email cím már létezik!";
                }
                $check_stmt->close();
            }
        } else {
            $error = "A jelszavak nem egyeznek!";
        }
    } else {
        $error = "Minden mezőt ki kell tölteni!";
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regisztráció</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/login.css">
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
                <li class="nav-item"><a class="nav-link" href="login.php">Bejelentkezés</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="login-container">
    <form class="login-box" method="POST" action="register.php">
        <h2>Regisztráljon</h2>

        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>

        <div class="form-group">
            <label for="email">E-mail-cím</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="E-mail-cím" required>
        </div>
        <div class="form-group">
            <label for="password">Jelszó</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Jelszó" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Jelszó megerősítése</label>
            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Jelszó ismét" required>
        </div>
        <button type="submit" class="btn btn-success btn-block">Regisztráció</button>
        <p class="register-link">Már van fiókja? <a href="login.php">Bejelentkezés</a></p>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
