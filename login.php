<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/global.css">
    <title>Login</title>
</head>
<body>

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
    include 'utils.php';
//    echo getHeader();

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    if(isUserLoggedIn())
    {
        header("Location: administrareClasaDeObiecte.php", true, 303);
        exit();
    }
?>

<!--missing searchBar-->

<div class="wrapper" style="min-height: 80vh;">
    <form action="api_login.php" id="Login" method="post">
        <div class="login-container"
             style="display:flex; flex-direction: column; text-align: center; border:1px solid #ccc; background-color:#f5f5f5;">
            <div class="login-fields"
                 style="display:flex; flex-direction: column; justify-content: center; align-items: center; margin: 20px;">
                <h3 class="login-title">Login</h3>
                <label for="login-email"><b>Email</b></label>
                <input id="login-email" name="login-email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                       placeholder="Enter E-mail" required
                       type="email">
                <label for="password"><b>Password</b></label>
                <input id="password" name="password" autocomplete="password"
                       pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                       placeholder="Enter Password"
                       required
                       title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"
                       type="password">
            </div>

            <?php
                $error_msg = null;

                if(isset($_GET["error_msg"]))
                {
                    $error_msg = $_GET["error_msg"];
                }

                if ($error_msg)
                {
                    echo "<p style='color: red'>$error_msg</p>";
                }
            ?>

                <input type="submit" id="login-button" value="Login">
                <h5 style="display:flex; flex-direction: column; justify-content: center; align-items: center; margin: 0 auto 20px;width: fit-content">You don't have an account? Register <a href="register.php">here</a></h5>

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

<script>

    function sendMail() {
        //send mail part
        const url = 'api_send_mail.php?email=' + document.getElementById("email").value;

        function clearText() {
            document.getElementById("subscribe-newsletter-message-info").textContent = '';
        }

        requestOptions = {
            method: 'GET',
        };

        document.getElementById("subscribe-newsletter-message-info").textContent = "Se trimite...";

        fetch(url, requestOptions)
            .then(response => response.text())
            .then(result => {
                document.getElementById("subscribe-newsletter-message-info").textContent = result;
                setTimeout(clearText, 5000);
            })
            .catch(error => {
                console.log('error', error)
                document.getElementById("subscribe-newsletter-message-info").textContent = "eroare";
                setTimeout(clearText, 5000);
            });
    }

</script>

</html>

