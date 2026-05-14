<?php
include "connection.php";

if (isset($_POST['register'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // 1. Validimi: Fushat nuk duhet të jenë bosh
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        echo "<script>alert('Ju lutem plotësoni të gjitha fushat!'); window.location.href='register.php';</script>";
        exit();
    }

    // 2. Validimi: Email valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Formati i emailit nuk është i saktë!'); window.location.href='register.php';</script>";
        exit();
    }

    // 3. Validimi: Gjatësia e password-it (min 6)
    if (strlen($password) < 6) {
        echo "<script>alert('Fjalëkalimi duhet të ketë të paktën 6 karaktere!'); window.location.href='register.php';</script>";
        exit();
    }

    // 4. Validimi: Përputhja e password-it
    if ($password !== $confirm_password) {
        echo "<script>alert('Fjalëkalimet nuk përputhen!'); window.location.href='register.php';</script>";
        exit();
    }

    // 5. Validimi: Kontrolli nëse emaili ekziston
    $check_email = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $check_email);
    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Ky email është i regjistruar paraprakisht!'); window.location.href='register.php';</script>";
        exit();
    }

    // Nëse të gjitha janë në rregull, e ruajmë në databazë (përdorim password_hash për siguri)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashed_password')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Regjistrimi u krye me sukses!'); window.location.href='login.php';</script>";
    } else {
        // Shfaq gabimin e saktë të MySQL
        echo "Gabim në MySQL: " . mysqli_error($conn);
        echo "<br>Query që dështoi: " . $sql;
    }
}
?>