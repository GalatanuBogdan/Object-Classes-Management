<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$db_servername = "localhost";
$db_username = "root";
$db_name = "t_web";
$db_password = "";

$objects_of_class_table_name = "object_of_class";
$products_table_name = "product_of_class";
$table_name_accounts = "accounts";
$table_name_product_reviews_dislikes = "product_reviews_dislikes";
$table_name_product_reviews_likes = "product_reviews_likes";

$conn = new mysqli($db_servername, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Conexiunea la baza de date a esuat: " . $conn->connect_error);
}

function removeReviews($table_name, $objectClassID)
{
    global $conn, $products_table_name, $objects_of_class_table_name;

    $deleteQuery = "DELETE reviewTable FROM $table_name reviewTable
    JOIN $products_table_name productsTable ON reviewTable.productID = productsTable.id
    JOIN $objects_of_class_table_name objOfClass ON objOfClass.id = productsTable.objectClassID
    WHERE objOfClass.id = ?";

    $checkStmt = $conn->prepare($deleteQuery);
    if (!$checkStmt) {
        $error_msg = "Prepare statement error: " . $conn->error;
        die($error_msg);
    }

    $checkStmt->bind_param("i", $objectClassID);
    if (!$checkStmt->execute()) {
        $error_msg = "Execute statement error: " . $checkStmt->error;
        die($error_msg);
    }
}

function removeProducts($objectClassID)
{
    global $conn, $products_table_name;

    $deleteQuery = "DELETE FROM $products_table_name WHERE objectClassID = ?";
    $checkStmt = $conn->prepare($deleteQuery);
    if (!$checkStmt) {
        $error_msg = "Prepare statement error: " . $conn->error;
        die($error_msg);
    }

    $checkStmt->bind_param("i", $objectClassID);
    if (!$checkStmt->execute()) {
        $error_msg = "Execute statement error: " . $checkStmt->error;
        die($error_msg);
    }
}

function removeObjectOfClass($objectClassID)
{
    global $conn, $objects_of_class_table_name;

    $deleteQuery = "DELETE FROM $objects_of_class_table_name WHERE id = ?";
    $checkStmt = $conn->prepare($deleteQuery);
    if (!$checkStmt) {
        $error_msg = "Prepare statement error: " . $conn->error;
        die($error_msg);
    }

    $checkStmt->bind_param("i", $objectClassID);
    if (!$checkStmt->execute()) {
        $error_msg = "Execute statement error: " . $checkStmt->error;
        die($error_msg);
    }
}

if (isset($_GET['objectClassID'])) {
    $objectClassID = mysqli_real_escape_string($conn, $_GET['objectClassID']);

    removeReviews($table_name_product_reviews_dislikes, $objectClassID);
    removeReviews($table_name_product_reviews_likes, $objectClassID);
    removeProducts($objectClassID);
    removeObjectOfClass($objectClassID);

    die("Stergerea a fost facuta cu succes");
}
else
{
    die("ID produs invalid");
}
