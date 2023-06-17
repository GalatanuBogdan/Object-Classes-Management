<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$db_servername = "localhost";
$db_username = "root";
$db_name = "t_web";
$db_password = "";
$products_table_name = "product_of_class";
$table_name_accounts = "accounts";
$table_name_product_reviews_dislikes = "product_reviews_dislikes";
$table_name_product_reviews_likes = "product_reviews_likes";

$conn = new mysqli($db_servername, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Conexiunea la baza de date a esuat: " . $conn->connect_error);
}

function removeReviews($table_name, $productID)
{
    global $conn;

    $deleteQuery = "DELETE FROM $table_name WHERE productID = ?";
    $checkStmt = $conn->prepare($deleteQuery);
    if (!$checkStmt) {
        $error_msg = "Prepare statement error: " . $conn->error;
        die($error_msg);
    }

    $checkStmt->bind_param("i", $productID);
    if (!$checkStmt->execute()) {
        $error_msg = "Execute statement error: " . $checkStmt->error;
        die($error_msg);
    }
}

function removeProduct($productID)
{
    global $conn, $products_table_name;

    $deleteQuery = "DELETE FROM $products_table_name WHERE id = ?";
    $checkStmt = $conn->prepare($deleteQuery);
    if (!$checkStmt) {
        $error_msg = "Prepare statement error: " . $conn->error;
        die($error_msg);
    }

    $checkStmt->bind_param("i", $productID);
    if (!$checkStmt->execute()) {
        $error_msg = "Execute statement error: " . $checkStmt->error;
        die($error_msg);
    }
}

if (isset($_GET['productID'])) {
    $product_id = mysqli_real_escape_string($conn, $_GET['productID']);

    removeReviews($table_name_product_reviews_dislikes, $product_id);
    removeReviews($table_name_product_reviews_likes, $product_id);
    removeProduct($product_id);

    die("Stergerea a fost facuta cu succes");
}
else
{
    die("ID produs invalid");
}
