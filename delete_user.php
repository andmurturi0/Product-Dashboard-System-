<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Parandalimi i fshirjes së vetvetes (opsionale, por e rekomanduar)
    if ($id == $_SESSION['user_id']) {
        echo "<script>alert('Nuk mund ta fshini llogarinë tuaj ndërsa jeni i kyçur!'); window.location.href='dashboard.php?view=profile';</script>";
        exit();
    }

    $query = "DELETE FROM users WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        header("Location: dashboard.php?view=profile&success=UserDeleted");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>