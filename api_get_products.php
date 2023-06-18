<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'utils.php';

$db_servername = "localhost";
$db_username = "root";
$db_name = "t_web";
$db_password = "";
$table_name = "product_of_class";
$table_name_accounts = "accounts";
$table_name_categories = "categories";

$conn = new mysqli($db_servername, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Conexiunea la baza de date a esuat: " . $conn->connect_error);
}

if(!array_key_exists('objectOfClassId', $_GET))
{
    die("clasa de obiecte invalida");
}

$objectOfClassId = $_GET['objectOfClassId'];

$sql = "SELECT *
        FROM $table_name objs 
        WHERE objectClassId = ?";


$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $objectOfClassId);
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
    echo json_encode(array('error' => 'Nu exista inregistrari disponibile pentru clasa de obiecte cu id-ul ' . $objectOfClassId));
}

$stmt->close();
$conn->close();
