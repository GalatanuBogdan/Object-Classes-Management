<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/category-selector.css">
    <link rel="stylesheet" href="css/products.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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


<div class="wrapper" style="min-height: 80vh;">
    <section style="display: flex; justify-content: center">

        <div class="view-product-container", id="view-product-containerID">
            <h3 style="margin-right: 10px">Apartament 2 camere Podul Ros</h3>

            <div class="view-selected-info">
                <img src="images/pozaApartament2Camere.jpg" width="400px" height="400px"/>
            </div>

            <div class="view-selected-info">
                <h3>Clasa de obiecte: </h3>
                <button class="view-selected-info-buttons">Clasa de obiecte</button>
            </div>

            <div class="view-selected-info">
                <h3>Category: </h3>
                    <button class="view-selected-info-buttons">Magazine</button>
            </div>

            <div class="view-selected-info">
                <h3>Context usage:</h3>
                    <button class="view-selected-info-buttons">Inchiriere</button>
            </div>

            <div class="view-selected-info">
                <h3>Price:</h3>
                <button class="view-selected-info-buttons">90000€</button>
            </div>
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
$productID = -1;
$categoryName = "";
$objectClassName = "";


if (isset($_GET['productID'])) {
    $productID = $_GET['productID'];
}

if (isset($_GET['objectName'])) {
    $objectClassName = $_GET['objectName'];
}

if (isset($_GET['categoryName'])) {
    $categoryName = $_GET['categoryName'];
}
?>

<script>
let jsProductID = "<?php echo $productID;?>";
let objectClassName = "<?php echo $objectClassName;?>";
let categoryName = "<?php echo $categoryName;?>";

function updateReviewCounts()
{
    const url = 'api_get_product_reviews.php?productID=' + jsProductID;

    const options = {
        method: 'GET',
    };

    fetch(url, options)
        .then(response => {
            if (!response.ok) {
                throw new Error('Request failed');
            }
            return response.text();
        })
        .then(data => {
            const review = JSON.parse(data);
            document.getElementById('count-likes').innerText = review.likesCount;
            document.getElementById('count-dislikes').innerText = review.dislikeCount;
        })
        .catch(error => {
            console.error(error);
        });
}


function updateProductHTML()
{
    const requestOptions = {
        method: 'GET'
    };

    const container = document.getElementById("view-product-containerID");
    container.innerText = '';

    fetch("api_get_product.php?productId=" + jsProductID, requestOptions)
        .then(response => response.text())
        .then(result => {
            const objs = JSON.parse(result);
            if (!objs.hasOwnProperty('error'))
            {
                objs.forEach(function (obj) {
                    console.log(obj)
                    const h3 = document.createElement("h3");
                    h3.textContent = 'Nume produs: ' + obj.name;
                    h3.style.marginRight = "10px";
                    h3.style.marginTop = "50px";

                    const img = document.createElement("img");
                    img.src = obj.pathImagine;
                    img.setAttribute("width", "400px");
                    img.setAttribute("height", "400px");

                    const div1 = document.createElement("div");
                    div1.className = "view-selected-info";
                    const h3ObjClass = document.createElement("h3");
                    h3ObjClass.textContent = "Clasa de obiecte: ";
                    const buttonObjClass = document.createElement("button");
                    buttonObjClass.className = "view-selected-info-buttons";
                    buttonObjClass.textContent = objectClassName;
                    div1.appendChild(h3ObjClass);
                    div1.appendChild(buttonObjClass);

                    const div2 = document.createElement("div");
                    div2.className = "view-selected-info";
                    const h3Category = document.createElement("h3");
                    h3Category.textContent = "Category: ";
                    const buttonCategory = document.createElement("button");
                    buttonCategory.className = "view-selected-info-buttons";
                    buttonCategory.textContent = categoryName;
                    div2.appendChild(h3Category);
                    div2.appendChild(buttonCategory);

                    const div3 = document.createElement("div");
                    div3.className = "view-selected-info";
                    const h3Context = document.createElement("h3");
                    h3Context.textContent = "Context usage:";
                    const buttonContext = document.createElement("button");
                    buttonContext.className = "view-selected-info-buttons";
                    buttonContext.textContent = obj.utilizationContext;
                    div3.appendChild(h3Context);
                    div3.appendChild(buttonContext);

                    const div4 = document.createElement("div");
                    div4.className = "view-selected-info";
                    const h3dezirabil = document.createElement("h3");
                    h3dezirabil.textContent = "Trasatura dezirabila:";
                    const buttonDezirabil = document.createElement("button");
                    buttonDezirabil.className = "view-selected-info-buttons";
                    buttonDezirabil.textContent = obj.trasaturaDezirabila;
                    div4.appendChild(h3dezirabil);
                    div4.appendChild(buttonDezirabil);

                    const div5 = document.createElement("div");
                    div5.className = "view-selected-info";
                    const h3indezirabil = document.createElement("h3");
                    h3indezirabil.textContent = "Trasatura indezirabila:";
                    const buttonIndezirabil = document.createElement("button");
                    buttonIndezirabil.className = "view-selected-info-buttons";
                    buttonIndezirabil.textContent = obj.trasaturaIndezirabila;
                    div5.appendChild(h3indezirabil);
                    div5.appendChild(buttonIndezirabil);

                    const div6 = document.createElement("div");
                    div6.className = "view-selected-info";
                    const h3Price = document.createElement("h3");
                    h3Price.textContent = "Price:";
                    const buttonPrice = document.createElement("button");
                    buttonPrice.className = "view-selected-info-buttons";
                    buttonPrice.textContent = obj.price + "€";
                    div6.appendChild(h3Price);
                    div6.appendChild(buttonPrice);

                    const div7 = document.createElement("div");
                    div7.className = "view-selected-info";
                    div7.appendChild(createLikeDislikeContainer());

                    container.appendChild(h3);
                    container.appendChild(img);
                    container.appendChild(div1);
                    container.appendChild(div2);
                    container.appendChild(div3);
                    container.appendChild(div4);
                    container.appendChild(div5);
                    container.appendChild(div6);
                    container.appendChild(div7);

                });

                updateReviewCounts();
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

updateProductHTML();

function createLikeDislikeContainer()
{
    const container = document.createElement("div");
    container.setAttribute("class", "buttonsContainer")

    container.style.display = "flex";
    container.style.alignItems = "center";

    const createCountElement = function(countElementId) {
        const count = document.createElement("p");
        count.classList.add("count");
        count.style.fontWeight = "bold";
        count.innerText="0";
        count.id = countElementId;
        return count;
    };

    const createButton = function(imageUrl, countElementId) {
        const button = document.createElement("button");
        button.classList.add("button");
        button.style.border = "none";
        button.style.background = "none";
        button.style.cursor = "pointer";

        const image = document.createElement("img");
        image.src = imageUrl;
        image.style.width = "32px";
        image.style.height = "32px";
        button.appendChild(image);

        button.appendChild(createCountElement(countElementId));

        return button;
    };

    function updateLike()
    {
        const url = 'api_add_like_to_product.php';

        const data = new FormData();
        data.append('productID', jsProductID)

        const options = {
            method: 'POST',
            body: data
        };

        fetch(url, options)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Request failed');
                }
                return response.text();
            })
            .then(data => {
                updateReviewCounts();
            })
            .catch(error => {
                console.error(error);
            });
    }

    function updateDislike()
    {
        const url = 'api_add_dislike_to_product.php';

        const data = new FormData();
        data.append('productID', jsProductID)

        const options = {
            method: 'POST',
            body: data
        };

        fetch(url, options)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Request failed');
                }
                return response.text();
            })
            .then(data => {
                updateReviewCounts();
            })
            .catch(error => {
                console.error(error);
            });
    }


    const likeButton = createButton('images/likeIcon.png', 'count-likes')

    likeButton.addEventListener("click",
        function () {
            updateLike();

        })

    const dislikeButton = createButton('images/dislikeIcon.png', 'count-dislikes')

    dislikeButton.addEventListener("click",
        function () {
            updateDislike();
        })

    container.appendChild(likeButton);
    container.appendChild(dislikeButton);

    return container;
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