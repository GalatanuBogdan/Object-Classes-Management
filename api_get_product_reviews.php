<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$db_servername = "localhost";
$db_username = "root";
$db_name = "t_web";
$db_password = "";
$table_name_likes = "product_reviews_likes";
$table_name_dislikes = "product_reviews_dislikes";


$conn = new mysqli($db_servername, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Conexiunea la baza de date a eșuat: " . $conn->connect_error);
}

include 'utils.php';

function getLikes($productID)
{
    global $table_name_likes, $conn;

    $query = "SELECT COUNT(*) FROM $table_name_likes WHERE productID = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        return false;
    }

    $stmt->bind_param("i", $productID);

    if (!$stmt->execute()) {
        return false;
    }

    $checkResult = $stmt->get_result();
    $numOfLikes = $checkResult->fetch_row()[0];
    $stmt->close();

    return $numOfLikes;
}

function getDislikes($productID)
{
    global $table_name_dislikes, $conn;

    $query = "SELECT COUNT(*) FROM $table_name_dislikes WHERE productID = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        return false;
    }

    $stmt->bind_param("i", $productID);

    if (!$stmt->execute()) {
        return false;
    }

    $checkResult = $stmt->get_result();
    $numOfLikes = $checkResult->fetch_row()[0];
    $stmt->close();

    return $numOfLikes;
}


function getIfUserAddedLike($productID, $ipAddress)
{
    global $table_name_likes, $conn;

    $query = "SELECT COUNT(*) FROM $table_name_likes WHERE productID = ? AND ipAddress = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        return false;
    }

    $stmt->bind_param("is", $productID, $ipAddress);

    if (!$stmt->execute()) {
        return false;
    }

    $checkResult = $stmt->get_result();
    $numOfLikes = $checkResult->fetch_row()[0];
    $stmt->close();

    return $numOfLikes > 0;
}

function getIfUserAddedDislike($productID, $ipAddress)
{
    global $table_name_dislikes, $conn;

    $query = "SELECT COUNT(*) FROM $table_name_dislikes WHERE productID = ? AND ipAddress = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        return false;
    }

    $stmt->bind_param("is", $productID, $ipAddress);

    if (!$stmt->execute()) {
        return false;
    }

    $checkResult = $stmt->get_result();
    $numOfDislikes = $checkResult->fetch_row()[0];
    $stmt->close();

    return $numOfDislikes > 0;
}

if(!array_key_exists('productID', $_GET))
{
    die("id invalid");
}

$productID = $_GET['productID'];;
$ipAddress = getClientIP();

$likesCount = getLikes($productID);
$dislikesCount = getDislikes($productID);
$ipAddressHasLikeAdded = getIfUserAddedLike($productID, $ipAddress);
$ipAddressHasDislikeAdded = getIfUserAddedDislike($productID, $ipAddress);

echo json_encode(
    [
        'likesCount' => $likesCount,
        'dislikeCount' => $dislikesCount,
        'ipAddressHasLikeAdded' => $ipAddressHasLikeAdded,
        'ipAddressHasDislikeAdded' => $ipAddressHasDislikeAdded,
    ]
);

$conn->close();