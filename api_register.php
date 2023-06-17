<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$db_servername = "localhost";
$db_username = "root";
$db_name = "t_web";
$db_password = "";
$table_name = "accounts";

// Create connection
$conn = mysqli_connect($db_servername, $db_username, $db_password, $db_name);

// Check connection
if (!$conn)
{
    $error_msg = "Cannot connect to db";
    header("Location: index.php?error_msg=$error_msg", true, 303);
    exit();
}

// Retrieve user input from the HTML form
$firstName = $_POST['first-name'];
$lastName = $_POST['last-name'];
$username = $_POST['register-email'];
$password = $_POST['password'];

function isUsernameUnique($username)
{
    global $conn, $table_name;

    if($username == '')
        die("Invalid name");

    $checkQuery = "SELECT COUNT(*) FROM $table_name WHERE username = ?";
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
    $result = $checkResult->fetch_row()[0];
    $checkStmt->close();

    if ($result > 0) {
        die("Username deja folosit.");
    }

    return true;
}

if(!isUsernameUnique($username)) {
    $message = "username is already used!";
    header("Location: login.php?message=$message", true, 303);
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO $table_name (username, password, first_name, last_name) VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $username, $hashedPassword, $firstName, $lastName);
$stmt->execute();

if ($stmt->affected_rows > 0)
{
    $message = "User registered successfully!";
    header("Location: login.php?message=$message", true, 303);
}
else
{
    $message = "User registration failed!";
    header("Location: login.php?error_message=$message", true, 303);
}

$stmt->close();
$conn->close();

exit;

?>



