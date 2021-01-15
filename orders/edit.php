<?php
// Connect to the Database
$pdo = include "../pdo/pdo.php";
// Start Session so that data is passed from one Request to the next
session_start();

// If a $_POST request, check to see these variable are set
// - removed: && isset($_POST['order_date']
if ( isset($_POST['id']) ) {

    // Data validation
    if ( strlen($_POST['id']) < 1 ) {
        $_SESSION['error'] = 'Missing data';
        header("Location: edit.php?id=".$_POST['id']);
        return;
    }

    $sql = "UPDATE orders
            SET id = :id,
                desired_date = :desired_date,
                fulfilled = :fulfilled,
                paid = :paid
            WHERE orders.id = :id";
    $stmt = $pdo->prepare($sql);

    $values = array(
        ':desired_date' => $_POST['desired_date'],
        ':fulfilled' => $_POST['fulfilled'],
        ':paid' => $_POST['paid'],
        ':id' => $_POST['id']
    );
//     echo '<pre>';
// var_dump($values, $_POST); exit;
    $stmt->execute($values);

    // Display success message in header
    $_SESSION['success'] = 'Record updated';
    header( 'Location: orders.php' ) ;
    return;
}

// Guardian: Make sure that id is present
if ( ! isset($_GET['id']) ) {
  $_SESSION['error'] = "Missing id";
  header('Location: orders.php');
  return;
}
if ( ! isset($_GET['name']) ) {
  $_SESSION['error'] = "Missing name";
  header('Location: orders.php');
  return;
}

// Prepared Statement
$stmt = $pdo->prepare("SELECT * FROM orders where id = :xyz");
$stmt->execute(array(":xyz" => $_GET['id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for id';
    header( 'Location: orders.php' ) ;
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
$d = htmlentities($row['desired_date']);
$f = htmlentities($row['fulfilled']);
$p = htmlentities($row['paid']);
$id = $row['id'];
$name = $_GET['name'];

?>
<h1>Edit Order</h1>
<form method="post">
<h3>Customer: <?= $name ?></h3>
<p>Desired Date:
<input type="date" name="desired_date" value="<?= $d ?>"></p>

<p>Fulfilled:
<input type="radio" name="fulfilled" value="1" <?php if ($f == 1) { echo 'checked'; } ?>>
<label for="fulfilled">Yes</label>&nbsp;
<input type="radio" name="fulfilled" value="0" <?php if ($f == 0) { echo 'checked'; } ?>>
<label for="fulfilled">No</label></p>

<p>Paid:
<input type="radio" name="paid" value="1" <?php if ($p == 1) { echo 'checked'; } ?>>
<label for="paid">Yes</label>&nbsp;
<input type="radio" name="paid" value="0" <?php if ($p == 0) { echo 'checked'; } ?>>
<label for="paid">No</label></p>

<?php
$f = 'Yes' ?  $f = 1 : $f = 0;
$p = 'Yes' ?  $p = 1 : $p = 0;
?>

<input type="hidden" name="id" value="<?= $id ?>">
<p><input class="button" type="submit" value="Update"/>&nbsp;
<a class="button" href="orders.php">Cancel</a></p>
</form>
