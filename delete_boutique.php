<?php
require_once('function.php');
dbconnect();
session_start();

if (!is_user()) {
    redirect('index.php');
}

// Ensure PDO is available
global $pdo;

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $pdo->prepare("DELETE FROM boutique WHERE id = ?");
    if ($stmt->execute([$id])) {
        $_SESSION['message'] = "Item deleted successfully!";
    } else {
        $_SESSION['message'] = "Failed to delete item.";
    }
} else {
    $_SESSION['message'] = "Invalid ID.";
}

header("Location: boutique.php");
exit();
?>
