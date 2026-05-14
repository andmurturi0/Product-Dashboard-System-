<?php
include 'connection.php';

// Krijimi i tabelës nëse nuk ekziston
$sql_table = "CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    monthly_price DECIMAL(10, 2) NOT NULL,
    registration_date DATE NOT NULL,
    product_features TEXT,
    is_available BOOLEAN DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
mysqli_query($conn, $sql_table);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $monthly_price = mysqli_real_escape_string($conn, $_POST['monthly_price']);
    $registration_date = mysqli_real_escape_string($conn, $_POST['registration_date']);
    $product_features = mysqli_real_escape_string($conn, $_POST['product_features']);
    $is_available = isset($_POST['is_available']) ? 1 : 0;

    if (isset($_GET['update_id'])) {
        $id = mysqli_real_escape_string($conn, $_GET['update_id']);
        $sql = "UPDATE products SET 
                product_name='$product_name', 
                category='$category', 
                monthly_price='$monthly_price', 
                registration_date='$registration_date', 
                product_features='$product_features', 
                is_available='$is_available' 
                WHERE id=$id";
        $success_msg = "ProduktiUpërditësua";
    } else {
        $sql = "INSERT INTO products (product_name, category, monthly_price, registration_date, product_features, is_available) 
                VALUES ('$product_name', '$category', '$monthly_price', '$registration_date', '$product_features', '$is_available')";
        $success_msg = "Produktregjistruar";
    }

    if (mysqli_query($conn, $sql)) {
        header("Location: dashboard.php?success=$success_msg");
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>