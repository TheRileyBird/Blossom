<?php
// Connect to the Database
require_once "../pdo/pdo.php";
// Start Session so that data is passed from one Request to the next
session_start();

// If a $_POST request, check to see these variable are set
if ( isset($_POST['name']) && isset($_POST['email'])
     && isset($_POST['address']) && isset($_POST['id']) ) {

    // Data validation - make sure these fields aren't empty
    if ( strlen($_POST['name']) < 1 || strlen($_POST['address']) < 1) {
        $_SESSION['error'] = 'Missing data';
        header("Location: edit.php?id=".$_POST['id']);
        return;
    }

    // Data validation - make sure email has an @ symbol
    if ( strpos($_POST['email'],'@') === false ) {
        $_SESSION['error'] = 'Bad data';
        header("Location: edit.php?id=".$_POST['id']);
        return;
    }

    // Prepared Statement
    $sql = "UPDATE customers SET name = :name,
            email = :email, address = :address
            WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':name' => $_POST['name'],
        ':email' => $_POST['email'],
        ':address' => $_POST['address'],
        ':id' => $_POST['id']));

    // Display success message in header
    $_SESSION['success'] = 'Record updated';
    header( 'Location: customers.php' ) ;
    return;
}

// Guardian: Make sure that id is present
if ( ! isset($_GET['id']) ) {
  $_SESSION['error'] = "Missing id";
  header('Location: customers.php');
  return;
}

// Prepared Statement
$stmt = $pdo->prepare("SELECT * FROM customers where id = :xyz");
$stmt->execute(array(":xyz" => $_GET['id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for id';
    header( 'Location: customers.php' ) ;
    return;
}

// Require Header
require_once "../structure/header.php";

// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}

// Convert all applicable characters to HTML entities for safety
$n = htmlentities($row['name']);
$e = htmlentities($row['email']);
$a = htmlentities($row['address']);
$id = $row['id'];
?>
<h1>Edit Customer</h1>
<form method="post">
<p>Name:
<input type="text" name="name" value="<?= $n ?>"></p>
<p>Email:
<input type="text" name="email" value="<?= $e ?>"></p>
<p>Address:
<input type="text" name="address" value="<?= $a ?>"></p>
<input type="hidden" name="id" value="<?= $id ?>">
<p><input class="button" type="submit" value="Update"/>&nbsp;
<a class="button" href="customers.php">Cancel</a></p>
</form>
