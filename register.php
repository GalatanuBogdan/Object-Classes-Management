<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/register.css">
    <link rel="stylesheet" href="css/global.css">
    <title>Register</title>
</head>

<header>
    <div>
        <div>
            <span> <a href="index.php">Home</a></span>
            <span><a href="scholarly.html">Documentation</a></span>
        </div>
        <div>
            <span><a href="administrareClasaDeObiecte.php">Manage account</a></span>
            <span><a href="login.php">Login</a></span>
        </div>
    </div>
</header>

<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include 'utils.php';
    if(isUserLoggedIn())
    {
        header("Location: administrareClasaDeObiecte.php", true, 303);
        exit();
    }
?>

<!--missing searchBar-->

<div class="wrapper" style="min-height: 80vh;">
    <form action="api_register.php" id="Register" method="post">
        <div class="register-container" style="display:flex; flex-direction: column; text-align: center; border:1px solid #ccc; background-color:#f5f5f5;">
                <h3 class="register-title">Registration Form</h3>
                <div class="register-fields" style="display:flex; flex-direction: column; justify-content: center; align-items: center; margin: 20px;">
                    <label for="first-name"><b>First name</b></label>
                    <input id="first-name" name="first-name" placeholder="Enter first name" required type="text">
                    <label for="last-name"><b>Last name</b></label>
                    <input id="last-name"  name="last-name" placeholder="Enter last name" required type="text">
                    <label for="register-email"><b>Email</b></label>
                    <input id="register-email" name="register-email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" placeholder="Enter E-mail" required
                           type="email">
                    <label for="password"><b>Password</b></label>
                    <input id="password" name="password" autocomplete="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                           placeholder="Enter Password"
                           required
                           title="Must contain at least one  number and one uppercase and lowercase letter, and at least 8 or more characters"
                           type="password">
                </div>
                <input type="submit" id="register-button" value="Register" style="display:flex; flex-direction: column; justify-content: center; align-items: center; margin: 0 auto 20px;width: fit-content">
                <h5 style="display:flex; flex-direction: column; justify-content: center; align-items: center; margin: 0 auto 20px;width: fit-content">You already have an account? Login <a href="login.php">here</a></h5>
                <div id="registerFormErrorList">

                </div>
            </div>
    </form>
</div>

<footer>
    <div class="footer-container">
        <section>
            <h3>Subscribe to our newsletter</h3>
            <form>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" style="border-color:black">
                <button type="submit" onclick="event.preventDefault(); sendMail();">Subscribe</button>
            </form>
            <p id="subscribe-newsletter-message-info"></p>
        </section>

        <section style="visibility: hidden;">
            <h3>Follow us</h3>
            <ul>
                <li><a href="https://www.facebook.com/example">Facebook</a></li>
                <li><a href="https://www.twitter.com/example">Twitter</a></li>
                <li><a href="https://www.instagram.com/example">Instagram</a></li>
            </ul>
        </section>

        <section>
            <h3>Menu</h3>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="scholarly.html">Documentation</a></li>
            </ul>
        </section>
    </div>
</footer>

</body>
</html>

