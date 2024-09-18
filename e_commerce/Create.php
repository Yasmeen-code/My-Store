<?php
ob_start();
require('partials/header.php'); ?>

<main class="container mb-10">
    <?php
    if (isset($_POST['submit'])) {
        // Check if all required fields are filled
        if (
            !empty($_POST['name']) &&
            !empty($_POST['price']) &&
            !empty($_POST['stock']) &&
            !empty($_POST['desk']) &&
            isset($_FILES['photo']) &&
            $_FILES['photo']['error'] === UPLOAD_ERR_OK
        ) {
            $photo_path = 'Images/' . ($_FILES['photo']['name']);

            move_uploaded_file($_FILES['photo']['tmp_name'], $photo_path);

            try {
                $mycon = new PDO(
                    "mysql:host=localhost;dbname=Video_create_product",
                    "root",
                    ""
                );
                $mycon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
                exit;
            }

            $query = "INSERT INTO products (name, price, stock, photo, desk) VALUES (:name, :price, :stock, :photo, :desk)";
            $stat = $mycon->prepare($query);
            $data = [
                ':name' => $_POST['name'],
                ':price' => $_POST['price'],
                ':stock' => $_POST['stock'],
                ':photo' => $photo_path,
                ':desk' => $_POST['desk']
            ];
            if ($stat->execute($data)) {
                header('Location: index.php');
                exit;
                echo "<p class='text-success'>Product added successfully!</p>";
            } else {
                echo "<p class='text-danger'>Failed to add product.</p>";
            }
        } else {
            echo "<p class='text-danger'>Please fill in all fields and upload a valid photo.</p>";
        }
    }
    ?>

    <form action="" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Name Of Product</label>
            <input type="text" class="form-control" name="name">
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Price</label>
            <input type="text" class="form-control" name="price">
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Stock Number</label>
            <input type="text" class="form-control" name="stock">
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Description</label>
            <textarea name="desk" rows="5" class="form-control"></textarea>
        </div>
        <div class="input-group mb-3">
            <input type="file" class="form-control" id="inputGroupFile02" name="photo">
        </div>
        <button type="submit" class="btn btn-primary" name="submit">Save</button>
    </form>
</main>

<?php
ob_end_flush();
require('partials/footer.php');
?>