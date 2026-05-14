<?php
include 'connection.php';

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $query = "SELECT * FROM products WHERE id = $id";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
        echo json_encode(["status" => "success", "product" => $product]);
    } else {
        echo json_encode(["status" => "error", "message" => "Produkti nuk u gjet"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "ID mungon"]);
}
?>