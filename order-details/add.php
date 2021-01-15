<?php
require_once "../pdo/pdo.php";
session_start();

if ( isset($_POST['name']) && isset($_POST['email'])
     && isset($_POST['address']) ) {

    // Data validation
    if ( strlen($_POST['name']) < 1 || strlen($_POST['address']) < 1) {
        $_SESSION['error'] = 'Missing data';
        header("Location: add.php");
        return;
    }

    if ( strpos($_POST['email'],'@') === false ) {
        $_SESSION['error'] = 'Bad data';
        header("Location: add.php");
        return;
    }

    $sql = "INSERT INTO customers (name, email, address)
              VALUES (:name, :email,  :address)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':name' => $_POST['name'],
        ':email' => $_POST['email'],
        ':address' => $_POST['address']));
    $_SESSION['success'] = 'Record Added';
    header( 'Location: customers.php' ) ;
    return;
}

// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}

require_once "../structure/header.php";
?>
<h1>Add A New Customer</h1>
<form method="post">
<p>Name:
<input type="text" name="name"></p>
<p>Email:
<input type="email" name="email"></p>
<p>Address:
<input type="text" name="address"></p>
<p><input class="button" type="submit" value="Add New"/>&nbsp;
<a class="button" href="customers.php">Cancel</a></p>
</form>
