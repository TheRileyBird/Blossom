<?php
require_once "../pdo/pdo.php";
session_start();

if ( isset($_POST['delete']) && isset($_POST['id']) ) {
    $sql = "DELETE FROM customers WHERE id = :zip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_POST['id']));
    $_SESSION['success'] = 'Record deleted';
    header( 'Location: customers.php' ) ;
    return;
}

// Guardian: Make sure that user_id is present
if ( ! isset($_GET['id']) ) {
  $_SESSION['error'] = "Missing id";
  header('Location: customers.php');
  return;
}

$stmt = $pdo->prepare("SELECT id, name FROM customers where id = :xyz");
$stmt->execute(array(":xyz" => $_GET['id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for id';
    header( 'Location: customers.php' ) ;
    return;
}

require_once "../structure/header.php";
?>
<h3>Confirm:<br>Deleting <strong><?= htmlentities($row['name']) ?></strong></h3>

<form method="post">
<input type="hidden" name="id" value="<?= $row['id'] ?>">
<input class="button" type="submit" value="Delete" name="delete">&nbsp;
<a class="button" href="customers.php">Cancel</a>
</form>
