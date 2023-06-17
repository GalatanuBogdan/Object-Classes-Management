<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'utils.php';

$db_servername = "localhost";
$db_username = "root";
$db_name = "t_web";
$db_password = "";
$object_of_class_table = "object_of_class";
$table_name = "product_of_class";

$categories_table_name = "categories";
$accounts_table_name = "accounts";
$conn = mysqli_connect($db_servername, $db_username, $db_password, $db_name);

function isProductNameUnique($name, $parentId)
{
    global $conn, $table_name;

    if($name == '')
        die("Invalid name");

    $checkQuery = "SELECT COUNT(*) FROM $table_name WHERE name = ? AND objectClassID = ?";
    $checkStmt = $conn->prepare($checkQuery);
    if (!$checkStmt) {
        $error_msg = "Prepare statement error: " . $conn->error;
        die($error_msg);
    }

    $checkStmt->bind_param("ss", $name, $parentId);
    if (!$checkStmt->execute()) {
        $error_msg = "Execute statement error: " . $checkStmt->error;
        die($error_msg);
    }

    $checkResult = $checkStmt->get_result();
    $object_class_count = $checkResult->fetch_row()[0];
    $checkStmt->close();

    if ($object_class_count > 0) {
        die("Produs deja existent.");
    }

    return true;
}

function isParentValid($parentId)
{
    global $conn, $object_of_class_table;

    $checkQuery = "SELECT COUNT(*) FROM $object_of_class_table WHERE id = ?";
    $checkStmt = $conn->prepare($checkQuery);
    if (!$checkStmt) {
        $error_msg = "Prepare statement error: " . $conn->error;
        die($error_msg);
    }

    $checkStmt->bind_param("s", $parentId);
    if (!$checkStmt->execute()) {
        $error_msg = "Execute statement error: " . $checkStmt->error;
        die($error_msg);
    }

    $checkResult = $checkStmt->get_result();
    $categoryResultCount = $checkResult->fetch_row()[0];
    $checkStmt->close();

    if ($categoryResultCount <= 0) {
        die("Clasa de obiecte nu exista");
    }

    return true;
}

function isUsernameValid($username)
{
//     global $conn, $accounts_table_name;
//
//     if($username == null || getUserLoggedInName() != $username)
//         die("Utilizatorul nu este logat");
//
//     $checkQuery = "SELECT COUNT(*) FROM $accounts_table_name WHERE username = ?";
//     $checkStmt = $conn->prepare($checkQuery);
//     if (!$checkStmt) {
//         $error_msg = "Prepare statement error: " . $conn->error;
//         die($error_msg);
//     }
//
//     $checkStmt->bind_param("s", $username);
//     if (!$checkStmt->execute()) {
//         $error_msg = "Execute statement error: " . $checkStmt->error;
//         die($error_msg);
//     }
//
//     $checkResult = $checkStmt->get_result();
//     $ownerIsValid = $checkResult->fetch_row()[0];
//     $checkStmt->close();
//
//     if ($ownerIsValid <= 0) {
//         die("Nu aveti drepturi pentru a crea acest obiect. Reconectati-va");
//     }

    return true;
}

function getUsernameId($username)
{
    global $conn, $accounts_table_name;

    $checkQuery = "SELECT id FROM $accounts_table_name WHERE username = ?";
    $checkStmt = $conn->prepare($checkQuery);
    if (!$checkStmt) {
        $error_msg = "Prepare statement error: " . $conn->error;
        die($error_msg);
    }

    $checkStmt->bind_param("s", $username);
    if (!$checkStmt->execute()) {
        $error_msg = "Execute statement error: " . $checkStmt->error;
        die($error_msg);
    }

    $checkResult = $checkStmt->get_result();
    $usernameID = $checkResult->fetch_row()[0];
    $checkStmt->close();

    return $usernameID;
}

function isCharacteristicValid($characteristic)
{
    if($characteristic == null || $characteristic == '')
        die('Trasatura invalida');

    return true;
}

function insertProduct($name, $utilizationContext, $trasaturaDezirabila, $trasaturaIndezirabila, $price, $objectClassId, $imageFileName)
{
    global $conn, $table_name;
    $insertQuery = "INSERT INTO $table_name (name, utilizationContext, trasaturaIndezirabila, trasaturaDezirabila, price, objectClassID, pathImagine) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $insertStmt = $conn->prepare($insertQuery);
    if (!$insertStmt) {
        $error_msg = "Prepare statement error: " . $conn->error;
        die($error_msg);
    }

    $insertStmt->bind_param('ssssiis', $name, $utilizationContext,$trasaturaIndezirabila, $trasaturaDezirabila, $price, $objectClassId, $imageFileName);
    if (!$insertStmt->execute()) {
        $error_msg = "Execute statement error: " . $insertStmt->error;
        die($error_msg);
    }

    $insertStmt->close();

    $successMsg = 'Product was added';
    die($successMsg);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $utilizationContext = mysqli_real_escape_string($conn, $_POST['utilizationContext']);
    $trasaturaDezirabila = mysqli_real_escape_string($conn, $_POST['trasatura-dezirabila']);
    $trasaturaIndezirabila = mysqli_real_escape_string($conn, $_POST['trasatura-indezirabila']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $objectClassId = mysqli_real_escape_string($conn, $_POST['objectClassID']);
    $uploadedImage = $_FILES['product-image'];
    $destinationDirectory = $_SERVER['DOCUMENT_ROOT'] . '/TehnologiiWeb/images/';
    $imageMovingResult = moveImageToDirectory($uploadedImage, $destinationDirectory);

    $imageFileName = $imageMovingResult[0];
    $imageFullPath = $imageMovingResult[1];

    if ($imageFullPath !== null)
    {
        $imageFullPath = resizeImage($imageFullPath, $imageFullPath, 400, 400);
    }

    if($imageFullPath != null)
    {
        $ok = isUsernameValid($username);
        $ok = $ok && isProductNameUnique($name, $objectClassId);
        $ok = $ok && isParentValid($objectClassId);
        $ok = $ok && isCharacteristicValid($trasaturaDezirabila);
        $ok = $ok && isCharacteristicValid($trasaturaIndezirabila);

        if($ok)
        {
            insertProduct($name, $utilizationContext, $trasaturaDezirabila,  $trasaturaIndezirabila, $price, $objectClassId, $imageFileName);
        }
    }
    else
    {
        $error_msg = "Cannot parse image or save the image";
        die($error_msg);
    }
}


