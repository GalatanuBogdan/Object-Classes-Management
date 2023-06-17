<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'utils.php';

$db_servername = "localhost";
$db_username = "root";
$db_name = "t_web";
$db_password = "";
$table_name = "object_of_class";
$categories_table_name = "categories";
$accounts_table_name = "accounts";
$conn = mysqli_connect($db_servername, $db_username, $db_password, $db_name);

function isObjectClassNameUnique($name, $parentId)
{
     global $conn, $table_name;

     if($name == '')
         die("Invalid name");

    $checkQuery = "SELECT COUNT(*) FROM $table_name WHERE name = ? AND ownerID = ?";
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
        die("Clasa de obiecte deja existenta.");
    }

    return true;
}

function isSelectedCategoryValid($categoryName)
{
    global $conn, $categories_table_name;

    if($categoryName == 'Neselectat')
        die("Invalid category name");

    $checkQuery = "SELECT COUNT(*) FROM $categories_table_name WHERE name = ?";
    $checkStmt = $conn->prepare($checkQuery);
    if (!$checkStmt) {
        $error_msg = "Prepare statement error: " . $conn->error;
        die($error_msg);
    }

    $checkStmt->bind_param("s", $categoryName);
    if (!$checkStmt->execute()) {
        $error_msg = "Execute statement error: " . $checkStmt->error;
        die($error_msg);
    }

    $checkResult = $checkStmt->get_result();
    $categoryResultCount = $checkResult->fetch_row()[0];
    $checkStmt->close();

    if ($categoryResultCount <= 0) {
        die("Categoria selectata nu exista");
    }

    return true;
}

function getCategoryId($categoryName)
{
    global $conn, $categories_table_name;

    $query = "SELECT id FROM $categories_table_name WHERE name = ?";
    $checkStmt = $conn->prepare($query);
    if (!$checkStmt) {
        $error_msg = "Prepare statement error: " . $conn->error;
        die($error_msg);
    }

    $checkStmt->bind_param("s", $categoryName);
    if (!$checkStmt->execute()) {
        $error_msg = "Execute statement error: " . $checkStmt->error;
        die($error_msg);
    }

    $checkResult = $checkStmt->get_result();
    $categoryID = $checkResult->fetch_row()[0];
    $checkStmt->close();

    return $categoryID;
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

function insertObjectOfClass($name, $parentId, $categoryID, $imageFullPath)
{
    global $conn, $table_name;
    $insertQuery = "INSERT INTO $table_name (name, ownerID, categoryID, pathImagine) VALUES (?, ?, ?, ?)";
    $insertStmt = $conn->prepare($insertQuery);
    if (!$insertStmt) {
        $error_msg = "Prepare statement error: " . $conn->error;
        die($error_msg);
    }

    $insertStmt->bind_param('siss', $name, $parentId, $categoryID, $imageFullPath);
    if (!$insertStmt->execute()) {
        $error_msg = "Execute statement error: " . $insertStmt->error;
        die($error_msg);
    }

    $insertStmt->close();

    $successMsg = 'Object was added';
    die($successMsg);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $name = mysqli_real_escape_string($conn, $_POST['name']);

   $selectedCategoryName = mysqli_real_escape_string($conn, $_POST['selectedCategoryName']);
   $username = mysqli_real_escape_string($conn, $_POST['username']);

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
        $usernameID = getUsernameId($username);
        $ok = $ok && isObjectClassNameUnique($name, $usernameID);
        $ok = $ok && isSelectedCategoryValid($selectedCategoryName);

        $categoryID = getCategoryId($selectedCategoryName);

        if($ok)
        {
            insertObjectOfClass($name, $usernameID, $categoryID, $imageFileName);
        }
   }
   else
   {
        $error_msg = "Cannot parse image or save the image";
        die($error_msg);
   }
}


