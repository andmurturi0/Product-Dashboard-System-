<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kyçja në Sistem</title>
    <link rel="stylesheet" href="loginregister.css">
</head>
<body>
    <video autoplay muted loop id="bg-video">
        <source src="librari/backgroundlr.mp4" type="video/mp4">
        Your browser does not support HTML5 video.
    </video>
    <div class="login-box">
        <h2>Kyçja</h2>
        <form action="login_process.php" method="POST">
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
            <button type="submit" class="btn">Kyçu në sistem</button>
        </form>
        <p class="footer-text">
            S'keni llogari? <a href="register.php">Regjistrohu këtu</a>
        </p>
    </div>
</body>
</html>