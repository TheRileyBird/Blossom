<?php
require_once "../pdo/pdo.php";
session_start();

if ( isset($_POST['delete']) && isset($_POST['id']) ) {
    $sql = "DELETE FROM users WHERE id = :zip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_POST['id']));
    $_SESSION['success'] = 'Record deleted';
    header( 'Location: users.php' ) ;
    return;
}

// Guardian: Make sure that user_id is present
if ( ! isset($_GET['id']) ) {
  $_SESSION['error'] = "Missing id";
  header('Location: users.php');
  return;
}

$stmt = $pdo->prepare("SELECT id, username FROM users where id = :xyz");
$stmt->execute(array(":xyz" => $_GET['id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for id';
    header( 'Location: users.php' ) ;
    return;
}
// Require Header
require_once "../structure/header.php";
?>
<h3>Confirm: Deleting <strong><?= htmlentities($row['username']) ?></strong></h3>

<form method="post">
<input type="hidden" name="id" value="<?= $row['id'] ?>">
<input class="button" type="submit" value="Delete" name="delete">&nbsp;
<a class="button" href="users.php">Cancel</a>
</form>
