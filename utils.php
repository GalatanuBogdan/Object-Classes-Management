<?php
$login_expire_time = 60 * 20;
function isUserLoggedIn()
{
    global $login_expire_time;

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_SESSION['login']))
    {
        if (isset($_SESSION['login']['username']) && isset($_SESSION['login']['timestamp']))
        {
            if (time() - $_SESSION['login']['timestamp'] <= $login_expire_time)
            {
                return true;
            }
            else
            {
                //user login time expired
                return false;
            }
        }
        else
        {
            return false;
        }
    }
    else
    {
        return false;
    }
}

function getUserLoggedInName()
{
    if (session_status() === PHP_SESSION_NONE)
    {
        session_start();
    }

    if(isUserLoggedIn())
        return $_SESSION['login']['username'];

    return null;
}


function getHeader()
{
    $header = '<header>
        <div>
            <div>
                <span> <a href="index.php">Home</a></span>
                <span><a href="scholarly.html">Documentation</a></span>
            </div>
            <div>
                <span><a href="administrareClasaDeObiecte.php">Manage account</a></span>';

    if(isUserLoggedIn())
    {
        $header .= '<span><a href="logout.php">Logout</a></span>';
    }
    else
    {
        $header .= '<span><a href="login.php">Login</a></span>';
    }

    $header .= '</div> </div> </header>';

    return $header;
}

function moveImageToDirectory($imageFile, $destinationDirectory) {
    if ($imageFile['error'] === UPLOAD_ERR_OK) {
        $tempPath = $imageFile['tmp_name'];
        $fileName = $imageFile['name'];

        $timestamp = time();
        $fileName = date("Y-m-d H:i:s", $timestamp) . "_" . uniqid() . "_" . $fileName;
        $destinationPath = $destinationDirectory . $fileName;

        if (move_uploaded_file($tempPath, $destinationPath)) {
            return array('images/' . $fileName, $destinationPath);
        } else {
            return array(null, null);
        }
    } else {
        return array(null, null);
    }
}

function resizeImage($sourcePath, $destinationPath, $targetWidth, $targetHeight){
    list($sourceWidth, $sourceHeight, $sourceType) = getimagesize($sourcePath);

    $newImage = imagecreatetruecolor($targetWidth, $targetHeight);

    switch ($sourceType) {
        case IMAGETYPE_JPEG:
            $sourceImage = imagecreatefromjpeg($sourcePath);
            break;
        case IMAGETYPE_PNG:
            $sourceImage = imagecreatefrompng($sourcePath);
            break;
        case IMAGETYPE_GIF:
            $sourceImage = imagecreatefromgif($sourcePath);
            break;
        default:
            return null;
    }

    imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $targetWidth, $targetHeight, $sourceWidth, $sourceHeight);

    switch ($sourceType) {
        case IMAGETYPE_JPEG:
            imagejpeg($newImage, $destinationPath);
            break;
        case IMAGETYPE_PNG:
            imagepng($newImage, $destinationPath);
            break;
        case IMAGETYPE_GIF:
            imagegif($newImage, $destinationPath);
            break;
    }

    imagedestroy($sourceImage);
    imagedestroy($newImage);

    return $destinationPath;
}

function getClientIP() {
    $ipAddress = '';

    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {
        $ipAddress = $_SERVER['HTTP_X_FORWARDED'];
    } elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
        $ipAddress = $_SERVER['HTTP_FORWARDED_FOR'];
    } elseif (isset($_SERVER['HTTP_FORWARDED'])) {
        $ipAddress = $_SERVER['HTTP_FORWARDED'];
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ipAddress = $_SERVER['REMOTE_ADDR'];
    }

    return $ipAddress;
}