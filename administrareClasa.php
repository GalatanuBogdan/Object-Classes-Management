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

<h1 style="text-align: center" id="administrare-clasa-titlu-id">Administrare clasa</h1>

<div class="product-container" id ="product-containerID">

</div>

<div class="management-container">
    <div class="create-item">
        <h4> Adaugare produs:</h4>
        <form enctype="multipart/form-data" id="add-product-class-form">
            <label for="name">Titlu:</label>
            <input type="text" id="title" name="name"><br><br>

            <label for="utilizationContext">Context Utilizare:</label>
            <input type="text" id="utilization-context" name="utilizationContext"><br><br>

            <label for="trasatura-dezirabila">Trasatura indezirabila:</label>
            <input type="text" id="trasatura-dezirabila" name="trasatura-dezirabila"><br><br>

            <label for="trasatura-indezirabila">Trasatura dezirabila:</label>
            <input type="text" id="trasatura-indezirabila" name="trasatura-indezirabila"><br><br>

            <label for="price">Pret:</label>
            <input type="text" id="price" name="price"><br><br>

            <label for="product-image">Adaugare imagine:</label>
            <input type="file" id="adaugare-imagine" name="product-image" alt="" accept="image/*" maxSize="5000000"><br><br>

            <input type="submit" value="Adauga produs" onclick="event.preventDefault(); add_product();">
            <p style="text-align: center; font-size: small; color:gray" id="add-product-message-info"></p>
        </form>
    </div>
</div>

<div style="display: flex; justify-content: center; margin-top:30px; margin-bottom: 30px">
    <button style="margin: 10px;" id="export-products-json" onclick="exportProductsAsJSON()">Export products as JSON</button>
    <button style="margin: 10px;" id="export-products-csv" onclick="exportProductsAsCSV()">Export products as CSV</button>
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
$objectClassId = -1;

if (isset($_GET['objectId'])) {
    $objectClassId = $_GET['objectId'];
}
?>

<script>
    function add_product() {
        const url = 'api_add_product.php';

        let jsUsername = "<?php echo $username;?>";
        let jsObjectClassId = "<?php echo $objectClassId;?>";

        const form = document.querySelector('#add-product-class-form');
        const data = new FormData(form);

        data.append('username', jsUsername);
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
                document.getElementById("add-product-message-info").textContent = data
                //trigger clearText
                setTimeout(clearText, 5000);

                updateProductsContainerAsHTML();
            })
            .catch(error => {
                console.error(error);
            });
    }

    function updateProductsContainerAsHTML()
    {
        let container = document.getElementById("product-containerID");
        container.innerHTML = "";

        const requestOptions = {
            method: 'GET'
        };

        let objectClassID = "<?php echo $objectClassId;?>";

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

                        const editButton = document.createElement("button");
                        editButton.className = "edit-product-btn";
                        editButton.textContent = "Edit";
                        editButton.onclick = function () {
                            window.location.href = "administrareProdus.php?objectId=" + obj.id + '&objectClassId=' + objectClassID;
                        };
                        controls.appendChild(editButton);

                        const removeButton = document.createElement("button");
                        removeButton.className = "remove-product-btn";
                        removeButton.textContent = "Remove";
                        removeButton.onclick= function () {
                            fetch('api_remove_product.php?productID=' + obj.id , {
                                method: 'DELETE'
                            })
                                .then(response => {
                                    if (response.ok) {
                                        console.log('Produsul a fost șters cu succes.');
                                        updateProductsContainerAsHTML();
                                    } else {
                                        console.log('Eroare la ștergerea produsului.');
                                    }
                                })
                                .catch(error => {
                                    console.error('Eroare la realizarea cererii:', error);
                                });
                        };

                        controls.appendChild(removeButton);

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
    const jsObjectClassId = "<?php echo $objectClassId;?>";

    function convertJsonToCsv(json) {
        const csvRows = [];

        const headers = Object.keys(json[0]);
        csvRows.push(headers.join(','));

        for (const row of json) {
            const values = headers.map(header => {
                let value = row[header];

                if (typeof value === 'string' && value.includes('"')) {
                    value = value.replace(/"/g, '');
                }

                return value;
            });

            csvRows.push(values.join(','));
        }

        const csvString = csvRows.join('\n');

        return csvString;
    }

    //generate inventory scripts
    function exportProductsAsJSON() {
        fetch("api_exporta_produsele_unei_clase.php?objectClassID=" + jsObjectClassId)
            .then(response => response.text())
            .then(result => {
                const blob = new Blob([result], {type: 'application/json'});
                const url = URL.createObjectURL(blob);

                const downloadLink = document.createElement('a');
                downloadLink.href = url;
                downloadLink.download = 'output.json';

                downloadLink.click();
            })
    }

    function exportProductsAsCSV()
    {
        fetch("api_exporta_produsele_unei_clase.php?objectClassID=" + jsObjectClassId)
            .then(response => response.text())
            .then(result => {
                jsonArray = JSON.parse(result);
                csvData = convertJsonToCsv(jsonArray);
                const blob = new Blob([csvData], { type: 'application/csv' });
                const url = URL.createObjectURL(blob);

                const downloadLink = document.createElement('a');
                downloadLink.href = url;
                downloadLink.download = 'output.csv';

                downloadLink.click();
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