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

$sql = '';

if(array_key_exists('objectClassID', $_GET))
{
    $objectClassID = mysqli_real_escape_string($conn, $_GET['objectClassID']);

    $sql = "SELECT p.id AS idProdus, p.name AS numeProdus, p.utilizationContext AS contextUtilizare, p.trasaturaIndezirabila, p.trasaturaDezirabila, p.price AS pret, a.username AS creator, o.id AS idClasaDeObiecte, o.name AS numeClasaDeObiecte, c.name AS categorie
        FROM t_web.product_of_class AS p
        JOIN t_web.object_of_class AS o ON p.objectClassID = o.id
        JOIN t_web.accounts AS a ON o.ownerID = a.id
        JOIN t_web.categories AS c ON c.id = o.categoryID WHERE o.id=" . $objectClassID;
}

if(array_key_exists('username', $_GET))
{
    $username = mysqli_real_escape_string($conn, $_GET['username']);

    $sql = "SELECT p.id AS idProdus, p.name AS numeProdus, p.utilizationContext AS contextUtilizare, p.trasaturaIndezirabila, p.trasaturaDezirabila, p.price AS pret, a.username AS creator, o.id AS idClasaDeObiecte, o.name AS numeClasaDeObiecte, c.name AS categorie
        FROM t_web.product_of_class AS p
        JOIN t_web.object_of_class AS o ON p.objectClassID = o.id
        JOIN t_web.accounts AS a ON o.ownerID = a.id
        JOIN t_web.categories AS c ON c.id = o.categoryID WHERE a.username='" . $username . "'";
}

if(!array_key_exists('objectClassID', $_GET) && !array_key_exists('username', $_GET))
{
    die("Invalid input");
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $products = array();
    while ($row = $result->fetch_assoc()) {
        $product = array(
            'idProdus' => $row['idProdus'],
            'numeProdus' => $row['numeProdus'],
            'contextUtilizare' => $row['contextUtilizare'],
            'trasaturaIndezirabila' => $row['trasaturaIndezirabila'],
            'trasaturaDezirabila' => $row['trasaturaDezirabila'],
            'pret' => $row['pret'],
            'creator' => $row['creator'],
            'idClasaDeObiecte' => $row['idClasaDeObiecte'],
            'numeClasaDeObiecte' => $row['numeClasaDeObiecte'],
            'categorie' => $row['categorie']
        );
        $products[] = $product;
    }

    header('Content-Type: application/json');
    header('Content-Disposition: attachment; filename=export_produse.json');

    echo json_encode($products, JSON_PRETTY_PRINT);
}
else {
    echo json_encode(array());
}

$conn->close();