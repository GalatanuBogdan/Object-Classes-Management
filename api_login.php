<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    session_start();

    $login_expire_time = 60 * 5;
    $maxAttempts = 5;
    $timeFrame = 60; // 1 minute
    $clientIP = $_SERVER['REMOTE_ADDR'];

    $identifier = md5($clientIP . $_SERVER['PHP_SELF']);

    if (!isset($_SESSION[$identifier]))
    {
        $_SESSION[$identifier] = array(
            'attempts' => 1,
            'timestamp' => time()
        );
    }
    else
    {
        $attempts = $_SESSION[$identifier]['attempts'];
        $timestamp = $_SESSION[$identifier]['timestamp'];

        if($attempts >= $maxAttempts)
        {
            if (time() - $timestamp > $timeFrame)
            {
                // waiting period ended. Reset the attempts and timestamp
                $_SESSION[$identifier]['attempts'] = 1;
                $_SESSION[$identifier]['timestamp'] = time();
            }
            else
            {
                $error_msg = "Too many login or registration attempts. Please try again later.";
                header("Location: login.php?error_msg=$error_msg", true, 303);
                exit();
            }
        }
        else
        {
            $_SESSION[$identifier]['attempts']++;
            $_SESSION[$identifier]['timestamp'] = time();
        }
    }

    include 'utils.php';
    if(isUserLoggedIn())
    {
        $_SESSION['login']['timestamp'] = time();
        header("Location: administrareClasaDeObiecte.php", true, 303);
        exit;
    }

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

    function validate_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        return htmlspecialchars($data);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $username = validate_input($_POST["login-email"]);
        $password = validate_input($_POST["password"]);

        $stmt = mysqli_prepare($conn, "SELECT password FROM $table_name WHERE username=?");
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $hashedPass = $row['password'];

            if (password_verify($password, $hashedPass)) {
                $_SESSION['login'] = array('username' => $username, 'timestamp' => time());
                header("Location: administrareClasaDeObiecte.php", true, 303);
                exit;
            }
        }

        $error_msg = "Invalid username or password";
        header("Location: login.php?error_msg=$error_msg&old_name=$username", true, 303);
        exit;
    }
