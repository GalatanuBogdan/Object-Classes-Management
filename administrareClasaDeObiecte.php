<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/products.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/global.css">
    <title>Title</title>
</head>
<body>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'utils.php';
echo getHeader();

if (!isUserLoggedIn()) {
    header("Location: login.php", true, 303);
    exit();
}
?>

<h1 style="text-align: center">Administrare clase de obiecte</h1>

<div class="product-container" id="product-containerID">
<!--    <div class="product-item">-->
<!--        <img src="images/fideliaCasa.png" class="product-image" alt="">-->
<!--        <div class="product-details">-->
<!--            <p class="product-title">Fidelia Casa</p>-->
<!--            <p class="product-category">Categorie: Agentii imobiliare</p>-->
<!--            <div class="product-controls">-->
<!--                <button onClick="window.location.href='administrareClasa.php';" class="edit-product-btn">Edit</button>-->
<!--                <button class="remove-product-btn">Remove</button>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!---->
<!--    <div class="product-item">-->
<!--        <img src="images/imobiliare.png" class="product-image" alt="">-->
<!--        <div class="product-details">-->
<!--            <p class="product-title">Imobiliare.ro</p>-->
<!--            <p class="product-category">Categorie: Agentii imobiliare</p>-->
<!--            <div class="product-controls">-->
<!--                <button onClick="window.location.href='administrareClasa.php';" class="edit-product-btn">Edit</button>-->
<!--                <button class="remove-product-btn">Remove</button>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
</div>


<div class="management-container">
    <div class="create-item">
        <h4> Adauga clasa de obiecte:</h4>
        <form enctype="multipart/form-data" id="add-object-class-form">
            <label for="name">Titlu:</label>
            <input type="text" id="clasa-noua-de-obiecte-titlu" name="name"><br><br>

            <label for="selectedCategoryName">Selecteaza o categorie din dreapta:</label>
            <input type="button" id="selected-category-btn" name="selectedCategoryName" value="Neselectat"><br><br>

            <label for="product-image" style="margin-right: 10px">Imagine clasa de obiecte</label>
            <input type="file" id="product-image" name="product-image" accept="image/*" maxSize="5000000"><br><br>

            <input type="submit" value="Adauga" onclick="event.preventDefault(); add_object_class()">
            <p style="text-align: center; font-size: small; color:gray" id="add-object-class-message-info"></p>
        </form>
    </div>

    <div class="manage-categories">
        <div class="search-category">
            <div class="search-input">
                <label>
                    <input id="cauta_categorie_input" type="text" placeholder="Cauta o categorie.."
                           style="display: flex">
                </label>
                <button id="cauta_categorie_button">Cauta</button>
            </div>
            <div id="categories-search-result" class="search-result"
                 style="overflow-y: scroll; max-height: 75px; max-width:400px;">

            </div>
        </div>
        <div class="add-category">
            <label for="title">Adauga categorie noua:</label>
            <label>
                <input id="add-category-input" type="text" placeholder="Nume categorie..">
            </label>
            <button id="addCategoryBtn">Adauga</button>
        </div>
        <p style="text-align: center; font-size: small; color:gray" id="add-category-message-info"></p>
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
?>

<script>

    let categories = Array()
    let selectedCategory = null

    document.getElementById("addCategoryBtn").onclick = function jsFunc() {
        const url = 'api_add_category.php';

        const data = {
            'name': document.getElementById("add-category-input").value
        };

        const options = {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'name=' + encodeURIComponent(data.name)
        };

        function clearText() {
            document.getElementById("add-category-message-info").textContent = '';
        }

        fetch(url, options)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Request failed');
                }
                return response.text();
            })
            .then(data => {
                document.getElementById("add-category-message-info").textContent = data

                //trigger clearText
                setTimeout(clearText, 5000);

                setTimeout(displayCategories, 1000);
            })
            .catch(error => {
                console.error(error);
            });
    }

    function selectCategory(name, id) {
        selectedCategory = {name, id};
        document.getElementById("selected-category-btn").value = name;
    }

    function createButtonElement(btnText) {
        let btnElement = document.createElement("button");
        btnElement.textContent = btnText;
        return btnElement;
    }

    function createCategoryButtonElement(btnText, btnId) {
        let btnElement = createButtonElement(btnText);
        btnElement.setAttribute("categoryId", btnId);

        btnElement.onclick = function jsFunc() {
            selectCategory(btnText, btnId)
        }

        return btnElement;
    }

    function createParagraphElement(pText) {
        let pElement = document.createElement("p");
        pElement.textContent = pText;
        return pElement;
    }

    function filterCategories(_categories, searchValue, maxResults) {
        const similarCategories = [];
        for (const category of _categories) {
            const name = category.name;
            const similarity = calculateSimilarity(searchValue, name);
            similarCategories.push({...category, similarity});
        }

        similarCategories.sort((a, b) => b.similarity - a.similarity);

        if (maxResults && similarCategories.length > maxResults) {
            similarCategories.length = maxResults;
        }

        return similarCategories;
    }


    function calculateSimilarity(str1, str2) {
        const m = str1.length;
        const n = str2.length;

        if (m === 0) {
            return n;
        }

        if (n === 0) {
            return m;
        }

        const matrix = Array.from(Array(m + 1), () => Array(n + 1).fill(0));

        for (let i = 0; i <= m; i++) {
            matrix[i][0] = i;
        }

        for (let j = 0; j <= n; j++) {
            matrix[0][j] = j;
        }

        for (let i = 1; i <= m; i++) {
            for (let j = 1; j <= n; j++) {
                const cost = str1[i - 1] === str2[j - 1] ? 0 : 1;
                matrix[i][j] = Math.min(
                    matrix[i - 1][j] + 1,
                    matrix[i][j - 1] + 1,
                    matrix[i - 1][j - 1] + cost
                );
            }
        }

        const maxDistance = Math.max(m, n);

        return 1 - matrix[m][n] / maxDistance;
    }

    function displayCategories() {
        let categoriesContainer = document.getElementById("categories-search-result");
        categoriesContainer.innerHTML = "";

        let getCategoriesApiURL = 'api_get_categories.php';
        fetch(getCategoriesApiURL)
            .then(function (response) {
                if (response.ok) {
                    return response.json();
                } else {
                    throw new Error('Cererea API-ului a eșuat.');
                }
            })
            .then(function (data) {
                if (data.hasOwnProperty('message')) {
                    categoriesContainer.appendChild(createButtonElement(data.message));
                } else {

                    categories = data;
                    const searchInput = document.getElementById("cauta_categorie_input").value;
                    let filteredCategories = filterCategories(categories, searchInput, 20);
                    filteredCategories.forEach(function (category) {
                        categoriesContainer.appendChild(createCategoryButtonElement(category.name, category.id));
                    });
                }
            })
            .catch(function (error) {
                console.error(error);
            });
    }

    displayCategories();

    document.getElementById("cauta_categorie_button").onclick = function jsFunc() {
        displayCategories();
    }

    function add_object_class() {
        const url = 'api_add_object_class.php';

        let jsUsername = "<?php echo $username;?>";

        const form = document.querySelector('#add-object-class-form');
        const data = new FormData(form);

        data.append('selectedCategoryName', document.getElementById("selected-category-btn").value);
        data.append('username', jsUsername);

        const options = {
            method: 'POST',
            body: data
        };

        function clearText() {
            document.getElementById("add-object-class-message-info").textContent = '';
        }

        fetch(url, options)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Request failed');
                }
                return response.text();
            })
            .then(data => {
                document.getElementById("add-object-class-message-info").textContent = data
                //trigger clearText
                setTimeout(clearText, 5000);

                updateObjectClasesAsHTML();
            })
            .catch(error => {
                console.error(error);
            });
    }

    function updateObjectClasesAsHTML()
    {
        let container = document.getElementById("product-containerID");
        container.innerHTML = "";

        const requestOptions = {
            method: 'GET'
        };

        let jsUsername = "<?php echo $username;?>";
        // let userHasAtLeastOneObject = false;

        fetch("api_get_objects_of_class.php?ownerUsername=" + jsUsername, requestOptions)
            .then(response => response.text())
            .then(result => {
                const objs = JSON.parse(result);
                if (!objs.hasOwnProperty('error'))
                {
                    objs.forEach(function (obj) {
                        // userHasAtLeastOneObject = true;

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

                        const category = document.createElement("p");
                        category.className = "product-category";
                        category.textContent = "Categorie: " + obj.categoryName;
                        details.appendChild(category);

                        const controls = document.createElement("div");
                        controls.className = "product-controls";

                        const editButton = document.createElement("button");
                        editButton.className = "edit-product-btn";
                        editButton.textContent = "Edit";
                        editButton.onclick = function () {
                            window.location.href = "administrareClasa.php?objectId=" + obj.id;
                        };
                        controls.appendChild(editButton);

                        const removeButton = document.createElement("button");
                        removeButton.className = "remove-product-btn";
                        removeButton.textContent = "Remove";
                        removeButton.onclick= function () {
                            fetch('api_remove_object_of_class.php?objectClassID=' + obj.id , {
                                method: 'DELETE'
                            })
                                .then(response => {
                                    if (response.ok) {
                                        console.log('Produsul a fost șters cu succes.');
                                        updateObjectClasesAsHTML ();
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

    updateObjectClasesAsHTML();


</script>

<script>
    let jsUsername = "<?php echo $username;?>";
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
        fetch("api_exporta_produsele_unei_clase.php?username=" + jsUsername)
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
        fetch("api_exporta_produsele_unei_clase.php?username=" + jsUsername)
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