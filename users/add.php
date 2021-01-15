<?php
require_once "../pdo/pdo.php";
session_start();

if ( isset($_POST['username']) && isset($_POST['email'])
     && isset($_POST['password'])) {

    // Data validation
    if ( strlen($_POST['username']) < 1 || strlen($_POST['password']) < 1) {
        $_SESSION['error'] = 'Missing data';
        header("Location: add.php");
        return;
    }

    if ( strpos($_POST['email'],'@') === false ) {
        $_SESSION['error'] = 'Bad data';
        header("Location: add.php");
        return;
    }

    $sql = "INSERT INTO users (username, email, password)
              VALUES (:username, :email, :password)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':username' => $_POST['username'],
        ':email' => $_POST['email'],
        ':password' => $_POST['password']));
    $_SESSION['success'] = 'Record Added';
    header( 'Location: users.php' ) ;
    return;
}

// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}

// Require Header
require_once "../structure/header.php";
?>

<h1>Add A New User</h1>
<form method="post">
<p>Username:
<input type="text" name="username"></p>
<p>Email:
<input type="text" name="email"></p>
<p>Password:
<input type="password" name="password"></p>
<p><input class="button" type="submit" value="Add New"/>&nbsp;
<a class="button" href="users.php">Cancel</a></p>
</form>
