<?php
session_start();
include "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // 1. Validimi: Boshllëku
    if (empty($email) || empty($password)) {
        echo "<script>alert('Ju lutem plotësoni të gjitha fushat!'); window.location.href='login.php';</script>";
        exit();
    }

    // 2. Kontrolli i përdoruesit
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // 3. Verifikimi i fjalëkalimit (përputhja me hash)
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<script>alert('Fjalëkalimi është i gabuar!'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('Përdoruesi nuk u gjet!'); window.location.href='login.php';</script>";
    }
}
?>