<?php
require_once('function.php');
dbconnect();
session_start();

if (!is_user()) {
    redirect('index.php');
}

// Ensure PDO is available
global $pdo;
$user = isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin';

// Fetch Item Details
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $pdo->prepare("SELECT * FROM boutique WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$item) {
        die("Item not found.");
    }
} else {
    die("Invalid ID.");
}

// Handle Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_item'])) {
    $name = htmlspecialchars($_POST['name']);
    $category = htmlspecialchars($_POST['category']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $description = htmlspecialchars($_POST['description']);

    $stmt = $pdo->prepare("UPDATE boutique SET name=?, category=?, price=?, stock=?, description=? WHERE id=?");
    if ($stmt->execute([$name, $category, $price, $stock, $description, $id])) {
        $_SESSION['message'] = "Item updated successfully!";
        header("Location: boutique.php");
        exit();
    } else {
        $_SESSION['message'] = "Update failed.";
    }
}

?>

<?php include ('header.php'); ?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><i class="fa fa-edit"></i> Edit Boutique Item</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <form method="POST">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" value="<?php echo $item['name']; ?>" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <input type="text" name="category" value="<?php echo $item['category']; ?>" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Price</label>
                    <input type="number" name="price" step="0.01" value="<?php echo $item['price']; ?>" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Stock</label>
                    <input type="number" name="stock" value="<?php echo $item['stock']; ?>" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control"><?php echo $item['description']; ?></textarea>
                </div>
                <button type="submit" name="update_item" class="btn btn-success"><i class="fa fa-save"></i> Update Item</button>
                <a href="boutique.php" class="btn btn-danger"><i class="fa fa-times"></i> Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php include ('footer.php'); ?>
