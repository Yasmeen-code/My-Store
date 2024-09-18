<?php

require('partials/header.php');
$mycon = new PDO("mysql:host=localhost;dbname=video_create_product", "root", "");
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $mycon->prepare("SELECT * FROM products WHERE id=:id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
}


?>

<body>
    <div class="container1">
        <div class="left-section">
            <h1><strong><?php echo $product['name']; ?></strong></h1>
            <div class="stock" style="margin-bottom: 50px;margin-top:15px; ">
                <i> <small> Stock : <?php echo $product['stock']; ?></small></i><br>
                <i><small> Price : <?php echo $product['price']; ?></small></i><br>
                <i><small style="font-size:90%;"> ID : <?php echo $product['id']; ?></small></i><br>

                <hr>
            </div>



            <div class="full-description">
                <p><?php echo $product['desk']; ?></p>
            </div>
            <div class="btn_home"> <a href="index.php" class="back-btn">Back to Home</a></div>
        </div>
        <div class="right-section">
            <img src="<?php echo $product['photo'] ?>" alt="Product Image">
        </div>
    </div>
</body>





<?php
require('partials/footer.php');
?>