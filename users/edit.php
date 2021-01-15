<?php
require_once "../pdo/pdo.php";
session_start();

if ( isset($_POST['username']) && isset($_POST['email'])
     && isset($_POST['password']) && isset($_POST['id']) ) {

    // Data validation
    if ( strlen($_POST['username']) < 1 || strlen($_POST['password']) < 1) {
        $_SESSION['error'] = 'Missing data';
        header("Location: edit.php?id=".$_POST['id']);
        return;
    }

    if ( strpos($_POST['email'],'@') === false ) {
        $_SESSION['error'] = 'Bad data';
        header("Location: edit.php?id=".$_POST['id']);
        return;
    }

    $sql = "UPDATE users SET username = :username,
            email = :email, password = :password
            WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':username' => $_POST['username'],
        ':email' => $_POST['email'],
        ':password' => $_POST['password'],
        ':id' => $_POST['id']));
    $_SESSION['success'] = 'Record updated';
    header( 'Location: users.php' ) ;
    return;
}

// Guardian: Make sure that id is present
if ( ! isset($_GET['id']) ) {
  $_SESSION['error'] = "Missing id";
  header('Location: users.php');
  return;
}

$stmt = $pdo->prepare("SELECT * FROM users where id = :xyz");
$stmt->execute(array(":xyz" => $_GET['id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for id';
    header( 'Location: users.php' ) ;
    return;
}

// Require Header
require_once "../structure/header.php";

// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}

$n = htmlentities($row['username']);
$e = htmlentities($row['email']);
$p = htmlentities($row['password']);
$id = $row['id'];
?>
<h1>Edit User</h1>
<form method="post">
<p>Name:
<input type="text" name="name" value="<?= $n ?>"></p>
<p>Email:
<input type="text" name="email" value="<?= $e ?>"></p>
<p>Password:
<input type="text" name="password" value="<?= $p ?>"></p>
<input type="hidden" name="id" value="<?= $id ?>">
<p><input class="button" type="submit" value="Update"/>&nbsp;
<a class="button" href="customers.php">Cancel</a></p>
</form>
