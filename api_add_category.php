<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'utils.php';

// Verifică dacă utilizatorul este autentificat
// if(!isUserLoggedIn())
// {
//     header("Location: login.php", true, 303);
//     exit;
// }

$db_servername = "localhost";
$db_username = "root";
$db_name = "t_web";
$db_password = "";
$table_name = "categories";

$conn = mysqli_connect($db_servername, $db_username, $db_password, $db_name);

if (!$conn)
{
    $error_msg = "Cannot connect to the database";
    die($error_msg);
}

function validateInput($input)
{
    global $conn;
    $filteredInput = trim($input);
    return mysqli_real_escape_string($conn, $filteredInput);
}

if(isset($_POST['name'])) {
    $categoryName = validateInput($_POST['name']);

    if($categoryName == '')
        die("Invalid input");

    $checkQuery = "SELECT COUNT(*) FROM $table_name WHERE name = ?";
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
    $categoryCount = $checkResult->fetch_row()[0];
    $checkStmt->close();

    if ($categoryCount > 0) {
        die("Categoria există deja.");
    }

    $insertQuery = "INSERT INTO $table_name (name) VALUES (?)";
    $insertStmt = $conn->prepare($insertQuery);
    if (!$insertStmt) {
        $error_msg = "Prepare statement error: " . $conn->error;
        die($error_msg);
    }

    $insertStmt->bind_param("s", $categoryName);
    if (!$insertStmt->execute()) {
        $error_msg = "Execute statement error: " . $insertStmt->error;
        die($error_msg);
    }

    $insertStmt->close();

    $successMsg = 'Category was added';
    die($successMsg);
}
else
{
    $error_msg = "Invalid input";
    die($error_msg);
}


$conn->close();