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

<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include 'utils.php';
    echo getHeader();

    if(!isUserLoggedIn())
    {
        header("Location: login.php", true, 303);
        exit();
    }
?>

<h1 style="text-align: center">Administrare produs</h1>

<div class="wrapper" style="min-height: 80vh;">
    <section style="display: flex; justify-content: center;">
        <div class="edit-product-container" id="edit-product-containerID">
            <form style="display: flex; justify-content: flex-start; align-items: center; margin-top: 10px; margin-left: 35px; margin-bottom: 5px;">
                <label for="product-name" style="margin-right: 10px">Nume Produs</label>
                <input type="text" id="product-name" name="product-name" placeholder="Nume produs">
            </form>

            <div class="search-bar">
                <div class="search-category">
                    <div class="category-dropdown">
                        <label for="category-name">Select a category:</label>
                        <select name="category-name" id="category-name">
                            <option value="none">Selecteaza</option>
                            <option value="apartamente">Apartamente</option>
                            <option value="magazine">Magazine</option>
                        </select>
                    </div>
                </div>
            </div>

            <form style="display: flex; justify-content: flex-start; align-items: center; margin-left: 35px;margin-bottom: 5px;">
                <label for="product-context" style="margin-right: 10px">Context de utilizare</label>
                <input type="text" id="product-context" name="product-context" placeholder="Apartament de vanzare">
            </form>


            <form style="display: flex; justify-content: flex-start; align-items: center; margin-left: 35px; margin-bottom: 5px;">
                <label for="product-price" style="margin-right: 10px">Pret produs</label>
                <input type="text" id="product-price" name="product-price" placeholder="90.000 euro">
            </form>

            <form style="display: flex; justify-content: flex-start; align-items: center; margin-left: 35px " id="add-image-form">
                <label for="product-image" style="margin-right: 10px">Imagine produs</label>
                <image src="images/fideliaCasa.png" width="100px" height="100px"></image>
                <input type="file" id="product-image" width="50px" height="50px" name="product-image">
                <input type="submit" value="Update produs" onclick="event.preventDefault(); add_product();">
            </form>
            <p style="text-align: center; font-size: small; color:gray" id="add-product-message-info"></p>
        </div>
    </section>
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
$username = getUserLoggedInName();
$productID = -1;
$objectClassID = -1;

if (isset($_GET['objectId'])) {
    $productID = $_GET['objectId'];
}

if (isset($_GET['objectClassId'])) {
    $objectClassID = $_GET['objectClassId'];
}
?>

<script>
    function updateProductsContainerAsHTML()
    {
        let container = document.getElementById("edit-product-containerID");
        container.innerHTML = "";

        const requestOptions = {
            method: 'GET'
        };

        let productID = "<?php echo $productID;?>";

        fetch("api_get_product.php?productId=" + productID, requestOptions)
            .then(response => response.text())
            .then(result => {
                const product = JSON.parse(result);
                if (!product.hasOwnProperty('error'))
                {
                    product.forEach(function (obj) {
                        const form1 = document.createElement("form");
                        form1.style.display = "flex";
                        form1.style.justifyContent = "flex-start";
                        form1.style.alignItems = "center";
                        form1.style.marginTop = "10px";
                        form1.style.marginLeft = "35px";
                        form1.style.marginBottom = "5px";

                        const nameLabel = document.createElement("label");
                        nameLabel.setAttribute("for", "product-name");
                        nameLabel.style.marginRight = "10px";
                        nameLabel.textContent = "Nume produs";
                        form1.appendChild(nameLabel);

                        const nameInput = document.createElement("input");
                        nameInput.setAttribute("type", "text");
                        nameInput.setAttribute("id", "product-name");
                        nameInput.setAttribute("name", "product-name");
                        nameInput.setAttribute("placeholder", obj.name);
                        nameInput.value = obj.name;
                        form1.appendChild(nameInput);

                        const form2 = document.createElement("form");
                        form2.style.display = "flex";
                        form2.style.justifyContent = "flex-start";
                        form2.style.alignItems = "center";
                        form2.style.marginLeft = "35px";
                        form2.style.marginBottom = "5px";

                        const contextLabel = document.createElement("label");
                        contextLabel.setAttribute("for", "product-context");
                        contextLabel.style.marginRight = "10px";
                        contextLabel.textContent = "Context de utilizare";
                        form2.appendChild(contextLabel);

                        const contextInput = document.createElement("input");
                        contextInput.setAttribute("type", "text");
                        contextInput.setAttribute("id", "product-context");
                        contextInput.setAttribute("name", "product-context");
                        contextInput.setAttribute("placeholder", obj.utilizationContext);
                        contextInput.value = obj.utilizationContext;
                        form2.appendChild(contextInput);

                        const form3 = document.createElement("form");
                        form3.style.display = "flex";
                        form3.style.justifyContent = "flex-start";
                        form3.style.alignItems = "center";
                        form3.style.marginLeft = "35px";
                        form3.style.marginBottom = "5px";

                        const trasaturaDezirabilaText = document.createElement("label");
                        trasaturaDezirabilaText.setAttribute("for", "trasatura-dezirabila");
                        trasaturaDezirabilaText.style.marginRight = "10px";
                        trasaturaDezirabilaText.textContent = "Trasatura dezirabila";
                        form3.appendChild(trasaturaDezirabilaText);

                        const trasaturaDezirabilaInput = document.createElement("input");
                        trasaturaDezirabilaInput.setAttribute("type", "text");
                        trasaturaDezirabilaInput.setAttribute("id", "trasatura-dezirabila");
                        trasaturaDezirabilaInput.setAttribute("name", "trasatura-dezirabila");
                        trasaturaDezirabilaInput.setAttribute("placeholder", obj.trasaturaDezirabila);
                        trasaturaDezirabilaInput.value = obj.trasaturaDezirabila;
                        form3.appendChild(trasaturaDezirabilaInput);

                        const form4 = document.createElement("form");
                        form4.style.display = "flex";
                        form4.style.justifyContent = "flex-start";
                        form4.style.alignItems = "center";
                        form4.style.marginLeft = "35px";
                        form4.style.marginBottom = "5px";

                        const trasaturaIndezirabilaText = document.createElement("label");
                        trasaturaIndezirabilaText.setAttribute("for", "trasatura-indezirabila");
                        trasaturaIndezirabilaText.style.marginRight = "10px";
                        trasaturaIndezirabilaText.textContent = "Trasatura indezirabila";
                        form4.appendChild(trasaturaIndezirabilaText);

                        const trasaturaIndezirabilaInput = document.createElement("input");
                        trasaturaIndezirabilaInput.setAttribute("type", "text");
                        trasaturaIndezirabilaInput.setAttribute("id", "trasatura-indezirabila");
                        trasaturaIndezirabilaInput.setAttribute("name", "trasatura-indezirabila");
                        trasaturaIndezirabilaInput.setAttribute("placeholder", obj.trasaturaIndezirabila);
                        trasaturaIndezirabilaInput.value = obj.trasaturaIndezirabila;
                        form4.appendChild(trasaturaIndezirabilaInput);

                        const form5 = document.createElement("form");
                        form5.style.display = "flex";
                        form5.style.justifyContent = "flex-start";
                        form5.style.alignItems = "center";
                        form5.style.marginLeft = "35px";
                        form5.style.marginBottom = "5px";

                        const priceLabel = document.createElement("label");
                        priceLabel.setAttribute("for", "product-price");
                        priceLabel.style.marginRight = "10px";
                        priceLabel.textContent = "Pret produs";
                        form5.appendChild(priceLabel);

                        const priceInput = document.createElement("input");
                        priceInput.setAttribute("type", "text");
                        priceInput.setAttribute("id", "product-price");
                        priceInput.setAttribute("name", "product-price");
                        priceInput.setAttribute("placeholder", obj.price);
                        priceInput.value = obj.price;
                        form5.appendChild(priceInput);

                        const form6 = document.createElement("form");
                        form6.setAttribute("enctype", "multipart/form-data");
                        form6.setAttribute("id", "add-image-form");
                        form6.style.display = "flex";
                        form6.style.justifyContent = "flex-start";
                        form6.style.alignItems = "center";
                        form6.style.marginLeft = "35px";

                        const imageLabel = document.createElement("label");
                        imageLabel.setAttribute("for", "product-image");
                        imageLabel.style.marginRight = "10px";
                        imageLabel.textContent = "Imagine produs";
                        form6.appendChild(imageLabel);

                        const imagePreview = document.createElement("img")
                        imagePreview.setAttribute("src", obj.pathImagine)
                        imagePreview.setAttribute("width", "100px");
                        imagePreview.setAttribute("height", "100px");
                        form6.appendChild(imagePreview);

                        const imageInput = document.createElement("input");
                        imageInput.setAttribute("type", "file");
                        imageInput.setAttribute("id", "product-image");
                        imageInput.setAttribute("name", "product-image");
                        imageInput.setAttribute("accept", "image/*");
                        imageInput.setAttribute("accept", "5000000");
                        form6.appendChild(imageInput);

                        const updateButton = document.createElement("input");
                        updateButton.setAttribute("type", "submit");
                        updateButton.setAttribute("onclick", "event.preventDefault(); updateProduct();")
                        updateButton.style.margin = "10px";
                        updateButton.value = "Update produs";
                        form6.appendChild(updateButton);

                        // <input type="submit" value="Update produs" onclick="event.preventDefault(); add_product();">

                        const messageText = document.createElement("p");
                        messageText.setAttribute("style", "text-align: center; font-size: small; color:gray");
                        messageText.setAttribute("id", "add-product-message-info");

                        container.appendChild(form1);
                        container.appendChild(form2);
                        container.appendChild(form3);
                        container.appendChild(form4);
                        container.appendChild(form5);
                        container.appendChild(form6);

                        container.appendChild(messageText);

                    });
                } else
                {
                    const noProductsContainer = document.createElement("div");
                    noProductsContainer.style = "height:400px;";

                    const message = document.createElement("h3");
                    message.textContent = product.error;
                    noProductsContainer.appendChild(message);

                    container.appendChild(noProductsContainer);
                }
            })
            .catch(error => console.log('error', error));
    }

    updateProductsContainerAsHTML();

    function updateProduct()
    {
        const url = 'api_update_product.php';

        let jsUsername = "<?php echo $username;?>";
        let jsProductId = "<?php echo $productID;?>";
        let jsObjectClassId = "<?php echo $objectClassID;?>";

        const form = document.querySelector('#add-image-form');
        const data = new FormData(form);

        data.append('username', jsUsername);
        data.append('productID', jsProductId)
        data.append('name', document.getElementById("product-name").value);
        data.append('utilizationContext', document.getElementById("product-context").value);
        data.append('trasaturaDezirabila', document.getElementById("trasatura-dezirabila").value);
        data.append('trasaturaIndezirabila', document.getElementById("trasatura-indezirabila").value);
        data.append('price', document.getElementById("product-price").value);
        data.append('objectClassID', jsObjectClassId)

        const options = {
            method: 'POST',
            body: data
        };

        function clearText() {
            document.getElementById("add-product-message-info").textContent = '';
        }

        fetch(url, options)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Request failed');
                }
                return response.text();
            })
            .then(data => {
                console.log(data)
                document.getElementById("add-product-message-info").textContent = data
                //trigger clearText
                setTimeout(clearText, 2000);

                setTimeout(updateProductsContainerAsHTML, 2000);
            })
            .catch(error => {
                console.error(error);
            });
    }

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