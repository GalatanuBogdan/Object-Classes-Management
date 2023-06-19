<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/category-selector.css">
    <link rel="stylesheet" href="css/products.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/global.css">
    <title>Home</title>
</head>
<body>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'utils.php';
echo getHeader();

?>

<div class="search-bar" style="margin-top:50px; margin-left:100px">
    <div class="search-category">
        <div class="category-dropdown">
            <label for="category-name">Select a category:</label>
            <select name="category-name" id="category-nameID">

            </select>
        </div>
    </div>

    <div class="search-products">
        <form>
            <label for="search-product"></label>
            <input type="text" id="search-product" name="search"
                   placeholder="cautare clasa...">
<!--            <button type="submit" style="margin: 10px;">Search</button>-->
            <input type="submit" value="Search" onclick="event.preventDefault(); setFilteredProducts();" style="margin: 10px;">
        </form>
    </div>
</div>

<div class="product-container" id="product-containerID">
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
    var categories = getCategories();
    var products = null;
    var filteredProducts = null;

    function getCategories() {
        const requestOptions = {
            method: 'GET'
        };

        fetch("api_get_categories.php", requestOptions)
            .then(response => response.text())
            .then(result => {
                categories = JSON.parse(result);
                setCategoriesInDropDown()
            })
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

    function filterProductsByName(_products, searchValue, maxResults) {
        const similarProducts = [];
        for (const product of _products) {
            const name = product.name;
            const similarity = calculateSimilarity(searchValue, name);
            similarProducts.push({...product, similarity});
        }

        similarProducts.sort((a, b) => b.similarity - a.similarity);

        if (maxResults && similarProducts.length > maxResults) {
            similarProducts.length = maxResults;
        }

        return similarProducts;
    }

    function setFilteredProducts()
    {
        let selectedCategory = document.getElementById('category-nameID').value;
        console.log(selectedCategory)
        if(selectedCategory !== 'none')
        {
            //remove all the products that doesn't have that category
            filteredProducts = products.filter(function(element) {
                return element.categoryName === selectedCategory;
            })
        }
        else
        {
            //otherwise, just copy all the products
            filteredProducts = products.slice();
        }

        const searchInput = document.getElementById("search-product").value;
        filteredProducts = filterProductsByName(filteredProducts, searchInput, 20);

        updateObjectClasesAsHTML(filteredProducts);
    }

    function setCategoriesInDropDown() {
        categoriesDropdown = document.getElementById('category-nameID');

        categoriesDropdown.innerText = '';

        defaultOption = document.createElement("option");
        defaultOption.setAttribute("value", "none");
        defaultOption.innerText = 'Neselectat';
        categoriesDropdown.appendChild(defaultOption);

        categories.forEach(function (category) {
            let option = document.createElement("option");
            option.setAttribute("value", category.name);
            option.innerText = category.name;
            categoriesDropdown.appendChild(option);
        })
    }

    function loadProducts()
    {
        const requestOptions = {
            method: 'GET'
        };

        fetch("api_get_classes.php", requestOptions)
            .then(response => response.text())
            .then(result => {
                products = JSON.parse(result);
                console.log(products);
                updateObjectClasesAsHTML(products);
            })
            .catch(error => console.log('error', error));
    }

    function updateObjectClasesAsHTML(filteredProducts) {
        let container = document.getElementById("product-containerID");
        container.innerHTML = "";

        if (!filteredProducts.hasOwnProperty('error'))
        {
            filteredProducts.forEach(function (obj) {
                const productItem = document.createElement("div");
                productItem.className = "product-item";

                const hyperlink = document.createElement("a");
                hyperlink.setAttribute("href", "vizualizareClasa.php?objectId=" + obj.id + " &objectName=" + obj.name + "&categoryName=" + obj.categoryName);
                hyperlink.setAttribute("style", "text-decoration: none; color: black")

                const image = document.createElement("img");
                image.src = obj.pathImagine;
                image.className = "product-image";
                image.alt = "";
                hyperlink.appendChild(image);

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

                hyperlink.appendChild(details);

                productItem.appendChild(hyperlink);

                container.appendChild(productItem);
            });
        }
        else
        {
            const noProductsContainer = document.createElement("div");
            noProductsContainer.style = "height:400px;";

            const message = document.createElement("h3");
            message.textContent = objs.error;
            noProductsContainer.appendChild(message);

            container.appendChild(noProductsContainer);
        }
    }

    loadProducts();

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