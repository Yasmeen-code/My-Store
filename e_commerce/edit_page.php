<?php
ob_start();
require('partials/header.php');
?>

<?php

$id = $_GET['id'];
$stmt = $mycon->prepare("SELECT * FROM products WHERE id=:id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$product = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<main class="container mb-10">
    <?php
    if (isset($_POST['submit'])) {

        if (!empty($_FILES['photo']['name'])) {
            $photo_path = 'Images/' . ($_FILES['photo']['name']);
            move_uploaded_file($_FILES['photo']['tmp_name'], $photo_path);
        }
        $data = [
            'name' => $_POST['name'],
            'price' => $_POST['price'],
            'stock' => $_POST['stock'],
            'desk' => $_POST['desk']
        ];
        if (isset($photo_path)) {
            $data['photo'] = $photo_path;
        }
        $colums = array_keys($data);
        $cols = array_map(function ($item) {
            return "$item=:$item";
        }, $colums);
        $cols = implode(',', $cols);


        $query = "UPDATE products SET $cols WHERE id = $id";
        $stmt1 = $mycon->prepare($query);
        $stmt1->execute($data);
        header('Location: index.php');
    }



    ?>

    <form action="" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Name Of Product</label>
            <input type="text" class="form-control" name="name" value="<?php echo $product['name']; ?>">

        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Price</label>
            <input type="text" class="form-control" name="price" value="<?php echo $product['price']; ?>">

        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Stock Number</label>
            <input type="text" class="form-control" name="stock" value="<?php echo $product['stock']; ?>">
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Description</label>
            <textarea name="desk" rows="5" class="form-control"><?php echo $product['desk']; ?></textarea>
        </div>
        <div class=" mb-3">
            <label class="form-label"> Product Photo</label><br>
            <input type="file" class="form-control" name="photo">
        </div>
        <div style="margin-bottom: 20px;">
            <img src="<?php echo $product['photo']; ?>" width="200px" height="200px" class="img-thumbnail">
        </div>
        <div class="btn_save">
            <button type="submit" class="btn btn-primary" name="submit">Save</button>
        </div>

    </form>
</main>


<?php
ob_end_flush();
require('partials/footer.php');
?>