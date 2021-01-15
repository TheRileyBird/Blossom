<?php
require_once "../pdo/pdo.php";
session_start();
// require_once "../structure/header.php";

if ( isset($_POST['name']) && isset($_POST['email'])
     && isset($_POST['address']) && isset($_POST['id']) ) {

    // Data validation
    if ( strlen($_POST['name']) < 1 || strlen($_POST['address']) < 1) {
        $_SESSION['error'] = 'Missing data';
        header("Location: edit.php?id=".$_POST['id']);
        return;
    }

    if ( strpos($_POST['email'],'@') === false ) {
        $_SESSION['error'] = 'Bad data';
        header("Location: edit.php?id=".$_POST['id']);
        return;
    }

    $sql = "UPDATE customers SET name = :name,
            email = :email, address = :address
            WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':name' => $_POST['name'],
        ':email' => $_POST['email'],
        ':address' => $_POST['address'],
        ':id' => $_POST['id']));
    $_SESSION['success'] = 'Record updated';
    header( 'Location: order-details.php' ) ;
    return;
}

// Guardian: Make sure that id is present
if ( ! isset($_GET['id']) ) {
  $_SESSION['error'] = "Missing id";
  header('Location: order-details.php');
  return;
}

$stmt = $pdo->prepare("SELECT * FROM customers where id = :xyz");
$stmt->execute(array(":xyz" => $_GET['id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for id';
    header( 'Location: order-details.php' ) ;
    return;
}

require_once "../structure/header.php";

// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}

id, order_id, product_id, price, quantity, total FROM order_details
$n = htmlentities($row['name']);
$e = htmlentities($row['email']);
$a = htmlentities($row['address']);
$id = $row['id'];
?>
<h1>Edit Order Details</h1>
<form method="post">
<p>Name:
<input type="text" name="name" value="<?= $n ?>"></p>
<p>Email:
<input type="text" name="email" value="<?= $e ?>"></p>
<p>Address:
<input type="text" name="address" value="<?= $a ?>"></p>
<input type="hidden" name="id" value="<?= $id ?>">
<p><input class="button" type="submit" value="Update"/>&nbsp;
<a class="button" href="order-details.php">Cancel</a></p>
</form>
