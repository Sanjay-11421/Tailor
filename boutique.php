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

// Handle Add Item
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_item'])) {
    $name = htmlspecialchars(trim($_POST['name']));
    $category = htmlspecialchars(trim($_POST['category']));
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $description = htmlspecialchars(trim($_POST['description']));
    
    $stmt = $pdo->prepare("INSERT INTO boutique (name, category, price, stock, description, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->execute([$name, $category, $price, $stock, $description]);
    header("Location: boutique.php");
    exit();
}

// Fetch Boutique Items
$items = $pdo->query("SELECT * FROM boutique ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

include ('header.php');
?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><i class="fa fa-shopping-bag"></i> Boutique Facilities</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <form method="POST">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <input type="text" name="category" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Price</label>
                    <input type="number" name="price" step="0.01" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Stock</label>
                    <input type="number" name="stock" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control"></textarea>
                </div>
                <button type="submit" name="add_item" class="btn btn-primary"><i class="fa fa-plus"></i> Add Item</button>
            </form>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-12">
            <h2><i class="fa fa-list"></i> Boutique Items</h2>
            <table class="table table-bordered table-striped">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['id']); ?></td>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td><?php echo htmlspecialchars($item['category']); ?></td>
                            <td><?php echo number_format($item['price'], 2); ?></td>
                            <td><?php echo htmlspecialchars($item['stock']); ?></td>
                            <td><?php echo htmlspecialchars($item['description']); ?></td>
                            <td>
                                <a href="edit_boutique.php?id=<?php echo $item['id']; ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> Edit</a>
                                <a href="delete_boutique.php?id=<?php echo $item['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');"><i class="fa fa-trash"></i> Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include ('footer.php'); ?>