<?php
include 'connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM products WHERE id = $id";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: dashboard.php?deleted=1");
    } else {
        echo "Gabim gjatë fshirjes: " . mysqli_error($conn);
    }
}
?>