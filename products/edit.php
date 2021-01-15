<?php
require_once "../pdo/pdo.php";
session_start();

if ( isset($_POST['name']) && isset($_POST['price']) ) {

    // Data validation
    if ( strlen($_POST['name']) < 1 || strlen($_POST['price']) < 1) {
        $_SESSION['error'] = 'Missing data';
        header("Location: edit.php?id=".$_POST['id']);
        return;
    }

    $sql = "UPDATE products SET name = :name, price = :price
            WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':name' => $_POST['name'],
        ':price' => $_POST['price'],
        ':id' => $_POST['id']));
    $_SESSION['success'] = 'Record updated';
    header( 'Location: products.php' ) ;
    return;
}

// Guardian: Make sure that id is present
if ( ! isset($_GET['id']) ) {
  $_SESSION['error'] = "Missing id";
  header('Location: products.php');
  return;
}

$stmt = $pdo->prepare("SELECT * FROM products where id = :xyz");
$stmt->execute(array(":xyz" => $_GET['id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for id';
    header( 'Location: products.php' ) ;
    return;
}

require_once "../structure/header.php";

// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}

$n = htmlentities($row['name']);
$p = htmlentities($row['price']);
$id = $row['id'];
?>
<h1>Edit User</h1>
<form method="post">
<p>Name:
<input type="text" name="name" value="<?= $n ?>"></p>
<p>Price:
<input type="text" name="price" value="<?= $p ?>"></p>
<input type="hidden" name="id" value="<?= $id ?>">
<p><input class="button" type="submit" value="Update"/>&nbsp;
<a class="button" href="products.php">Cancel</a></p>
</form>
