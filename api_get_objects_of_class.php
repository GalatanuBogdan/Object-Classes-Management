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
$table_name_accounts = "accounts";
$table_name_categories = "categories";

$conn = new mysqli($db_servername, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Conexiunea la baza de date a esuat: " . $conn->connect_error);
}

if(!array_key_exists('ownerUsername', $_GET))
{
    die("Utilizator invalid");
}

$ownerUsername = $_GET['ownerUsername'];

//if(getUserLoggedInName() != $ownerUsername)
//{
//    die("Nu ai suficiente permisiuni");
//}

$sql = "SELECT objs.*, cat.name AS categoryName 
        FROM $table_name objs 
        LEFT JOIN $table_name_accounts acc ON objs.ownerID = acc.id 
        JOIN $table_name_categories cat ON cat.id = objs.categoryID 
        WHERE acc.username = ?";


$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $ownerUsername);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $objectsOfClass = array();
    while ($row = $result->fetch_assoc()) {
        $objectsOfClass[] = $row;
    }
    echo json_encode($objectsOfClass);
}
else
{
    echo json_encode(array('error' => 'Nu exista inregistrari disponibile pentru ' . $ownerUsername));
}

$stmt->close();
$conn->close();
