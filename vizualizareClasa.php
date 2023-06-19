<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/category-selector.css">
    <link rel="stylesheet" href="css/products.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/global.css">
    <title>Administrare clasa</title>
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

<h1 style="text-align: center" id="class-title">Vizualizare Clasa: FideliaCasa</h1>
<h2 style="text-align: center" id="class-category">Categoria: </h2>

<div class="product-container" id ="product-containerID">

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

<?php
$objectClassId = -1;
$objectClassName = "";
$categoryName = "";

if (isset($_GET['objectId'])) {
    $objectClassId = $_GET['objectId'];
}

if (isset($_GET['objectName'])) {
    $objectClassName = $_GET['objectName'];
}

if (isset($_GET['categoryName'])) {
    $categoryName = $_GET['categoryName'];
}
?>

<script>
    let objectClassID = "<?php echo $objectClassId;?>";
    let objectClassName = "<?php echo $objectClassName;?>";
    let categoryName = "<?php echo $categoryName;?>";

    document.getElementById("class-title").innerText = 'Vizualizare clasa de obiecte ' + objectClassName;
    document.getElementById("class-category").innerText = 'Categoria ' + categoryName;


    function updateProductsContainerAsHTML()
    {
        let container = document.getElementById("product-containerID");
        container.innerHTML = "";

        const requestOptions = {
            method: 'GET'
        };

        fetch("api_get_products.php?objectOfClassId=" + objectClassID, requestOptions)
            .then(response => response.text())
            .then(result => {
                const objs = JSON.parse(result);
                if (!objs.hasOwnProperty('error'))
                {
                    objs.forEach(function (obj) {
                        const productItem = document.createElement("div");
                        productItem.className = "product-item";

                        const image = document.createElement("img");
                        image.src = obj.pathImagine;
                        image.className = "product-image";
                        image.alt = "";
                        productItem.appendChild(image);

                        const details = document.createElement("div");
                        details.className = "product-details";

                        const title = document.createElement("p");
                        title.className = "product-title";
                        title.textContent = obj.name;
                        details.appendChild(title);

                        const controls = document.createElement("div");
                        controls.className = "product-controls";

                        const viewButton = document.createElement("button");
                        viewButton.className = "view-product-btn";
                        viewButton.textContent = "View";
                        viewButton.onclick = function () {
                            window.location.href = "vizualizareProdus.php?productID=" + obj.id + '&objectClassId=' + objectClassID + '&objectName=' + objectClassName + '&categoryName=' + categoryName;
                        };
                        controls.appendChild(viewButton);

                        details.appendChild(controls);

                        productItem.appendChild(details);

                        container.appendChild(productItem);
                    });
                } else {
                    const noProductsContainer = document.createElement("div");
                    noProductsContainer.style = "height:400px;";

                    const message = document.createElement("h3");
                    message.textContent = objs.error;
                    noProductsContainer.appendChild(message);

                    container.appendChild(noProductsContainer);
                }
            })
            .catch(error => console.log('error', error));
    }

    updateProductsContainerAsHTML();

</script>

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