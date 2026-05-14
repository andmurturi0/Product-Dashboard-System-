<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regjistrimi në Sistem</title>
    <link rel="stylesheet" href="loginregister.css">
</head>
<body>
    <video autoplay muted loop id="bg-video">
        <source src="librari/backgroundlr.mp4" type="video/mp4">
        Your browser does not support HTML5 video.
    </video>
    <div class="login-box">
        <h2>Regjistrimi</h2>
        <form action="register_process.php" method="POST">
            <div class="input-group">
                <label>Emri i Plotë:</label>
                <input type="text" name="name" placeholder="Emri dhe mbiemri..." required>
            </div>
            <div class="input-group">
                <label>Email:</label>
                <input type="email" name="email" placeholder="example@email.com" required>
            </div>
            <div class="input-group">
                <label>Fjalëkalimi:</label>
                <input type="password" name="password" placeholder="******" required>
                <p class="password-requirements">
                    Min 6 karaktere, një shkronjë e madhe dhe një numër.
                </p>
            </div>
            <div class="input-group">
                <label>Përsërit Fjalëkalimin:</label>
                <input type="password" name="confirm_password" placeholder="******" required>
            </div>
            <button type="submit" name="register" class="btn">Krijo llogari</button>
        </form>
        <p class="footer-text">
            Keni llogari? <a href="login.php">Kyçuni këtu</a>
        </p>
    </div>
</body>
</html>