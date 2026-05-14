<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
    if (isset($_POST['redirect'])) {
        header("Location: login.php");
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Pa autorizim!']);
    }
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $redirect = isset($_POST['redirect']) ? $_POST['redirect'] : null;

    if (empty($name) || empty($email)) {
        if ($redirect) {
            header("Location: $redirect&error=EmptyFields");
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Emri dhe Email nuk mund të jenë bosh!']);
        }
        exit();
    }

    // Përditëso fjalëkalimin nëse është dërguar (për profilin)
    $password_query = "";
    if (isset($_POST['new_password']) && !empty($_POST['new_password'])) {
        $new_password_plain = $_POST['new_password'];
        $hashed_password = password_hash($new_password_plain, PASSWORD_DEFAULT);
        $password_query = ", password='$hashed_password'";
    }

    $query = "UPDATE users SET name='$name', email='$email' $password_query WHERE id=$id";
    
    if (mysqli_query($conn, $query)) {
        // Përditëso sesionin nëse përdoruesi po përditëson veten e tij
        if ($id == $_SESSION['user_id']) {
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
        }

        if ($redirect) {
            header("Location: $redirect&success=ProfileUpdated");
        } else {
            echo json_encode(['status' => 'success']);
        }
    } else {
        if ($redirect) {
            header("Location: $redirect&error=UpdateFailed");
        } else {
            echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
        }
    }
}
?>