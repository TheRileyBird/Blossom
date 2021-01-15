<?php
require_once "../pdo/pdo.php";
session_start();

if ( isset($_POST['name']) && isset($_POST['price']) ) {

    // Data validation
    if ( strlen($_POST['name']) < 1 || strlen($_POST['price']) < 1) {
        $_SESSION['error'] = 'Missing data';
        header("Location: add.php");
        return;
    }

    $sql = "INSERT INTO products (name, price)
              VALUES (:name, :price)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':name' => $_POST['name'],
        ':price' => $_POST['price']));
    $_SESSION['success'] = 'Record Added';
    header( 'Location: products.php' ) ;
    return;
}

// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}

require_once "../structure/header.php";
?>
<h1>Add A New Product</h1>
<form method="post">
<p>Name:
<input type="text" name="name"></p>
<p>Price:
<input type="text" name="price"></p>
<p><input class="button" type="submit" value="Add New"/>&nbsp;
<a class="button" href="products.php">Cancel</a></p>
</form>
