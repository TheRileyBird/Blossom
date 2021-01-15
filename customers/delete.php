<?php
// Connect to the Database
require_once "../pdo/pdo.php";
// Start Session so that data is passed from one Request to the next
session_start();

// If a $_POST request, check to see these variable are set
if ( isset($_POST['delete']) && isset($_POST['id']) ) {
    // Run SQL delete statement
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

// Prepared Statement
$stmt = $pdo->prepare("SELECT id, name FROM customers where id = :xyz");
$stmt->execute(array(":xyz" => $_GET['id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for id';
    header( 'Location: customers.php' ) ;
    return;
}

// Require Header
require_once "../structure/header.php";
?>

<!-- Convert all applicable characters to HTML entities for safety -->
<h3>Confirm:<br>Deleting <strong><?= htmlentities($row['name']) ?></strong></h3>

<form method="post">
<input type="hidden" name="id" value="<?= $row['id'] ?>">
<input class="button" type="submit" value="Delete" name="delete">&nbsp;
<a class="button" href="customers.php">Cancel</a>
</form>
