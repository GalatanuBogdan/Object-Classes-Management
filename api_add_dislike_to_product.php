<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'utils.php';

$db_servername = "localhost";
$db_username = "root";
$db_name = "t_web";
$db_password = "";

$table_name_likes = "product_reviews_likes";
$table_name = "product_reviews_dislikes";

$product_of_class_table_name = "product_of_class";


$conn = mysqli_connect($db_servername, $db_username, $db_password, $db_name);

if (!$conn) {
    $error_msg = "Cannot connect to the database";
    die($error_msg);
}

function validateInput($input)
{
    global $conn;
    $filteredInput = trim($input);
    return mysqli_real_escape_string($conn, $filteredInput);
}

function addDislike($productID)
{
    global $conn, $table_name;

    $insertQuery = "INSERT INTO $table_name (ipAddress, productID) VALUES (?, ?)";
    $insertStmt = $conn->prepare($insertQuery);

    if (!$insertStmt) {
        $error_msg = "Prepare statement error: " . $conn->error;
        die($error_msg);
    }

    $ipAddress = getClientIP();

    $insertStmt->bind_param("si", $ipAddress, $productID);
    try
    {
        $insertStmt->execute();
    }
    catch (mysqli_sql_exception $e)
    {
        die('Review deja adaugat');
    }

    $insertStmt->close();

    $successMsg = 'Dislike was added';
    die($successMsg);
}

function removeLike($productID)
{
    global $conn, $table_name_likes;

    $removeQuery = "DELETE FROM $table_name_likes WHERE ipAddress = ? AND productID = ?";
    $removeStmt = $conn->prepare($removeQuery);

    if (!$removeStmt) {
        $error_msg = "Prepare statement error: " . $conn->error;
        die($error_msg);
    }

    $ipAddress = getClientIP();

    $removeStmt->bind_param("si", $ipAddress, $productID);
    try
    {
        $removeStmt->execute();
    }
    catch (mysqli_sql_exception $e)
    {
        die('Eroare la like');
    }

    $removeStmt->close();
}

if (isset($_POST['productID']))
{
    $productID = validateInput($_POST['productID']);

    removeLike($productID);
    addDislike($productID);
}
else
{
    $error_msg = "Invalid input";
    die($error_msg);
}


$conn->close();