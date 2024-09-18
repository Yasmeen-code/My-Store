<?php
ob_start();

require('partials/header.php');

?>

<main class="container mt-5">
    <?php
    try {
        $mycon = new PDO(
            "mysql:host=localhost;dbname=Video_create_product",
            "root",
            ""
        );
        $mycon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $stmt = $mycon->prepare("DELETE FROM products WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        header("Location: index.php");
    }

    $stmt = $mycon->query("SELECT * FROM products");

    if ($stmt && $stmt->rowCount() > 0) { ?>
        <div class="row mb-5">
            <?php
            // Loop through the results
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            ?>
                <div class="col-md-6 mb-4">
                    <div class="row g-0 border rounded overflow-hidden flex-md-row shadow-sm h-md-250 position-relative">
                        <!-- Product details -->
                        <div class="col-md-6 p-4 d-flex flex-column position-static">
                            <strong class="d-inline-block mb-2 text-primary">Product <?php echo htmlspecialchars($row['id']); ?></strong>
                            <h3 class="mb-0"><?php echo htmlspecialchars($row['name']); ?></h3>
                            <p class="card-text mt-3"><?php echo substr($row['desk'], 0, 50) . " ..."; ?></p>
                            <div class="mt-3 d-flex">
                                <a href="details_btn.php?id=<?= $row['id'] ?>" class="btn btn-info me-2">
                                    <i class="bi bi-eye"></i> Details
                                </a>
                                <a href="edit_page.php?id=<?php echo $row['id']; ?>" class="btn btn-primary me-2">

                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <a href="?<?php echo  "id=" . $row['id']; ?>" class="btn btn-danger me-2">

                                    <i class="bi bi-trash"></i> Delete
                                </a>
                            </div>
                        </div>

                        <!-- Product image placeholder -->
                        <div class="col-md-6 d-none d-md-block">
                            <img src="<?php echo htmlspecialchars($row['photo']); ?>" alt="Product Image" class="img-fluid product-image">
                        </div>

                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
</main>

<?php
ob_end_flush();
require('partials/footer.php'); ?>