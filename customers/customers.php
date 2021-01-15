<?php
// Connect to the Database
require_once "../pdo/pdo.php";
// Start Session so that data is passed from one Request to the next
session_start();
// Require Header
require_once "../structure/header.php";

  // Flash Messages
  if ( isset($_SESSION['error']) ) {
    echo '<p style="color: red;">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
  }
  if ( isset($_SESSION['success']) ) {
    echo '<p style="color: green;">'.$_SESSION['success']."</p>\n";
    unset($_SESSION['success']);
  }

  // Sorting Functionality
  if ( isset($_GET['order']) ) {
    $order = $_GET['order'];
  } else {
    $order = 'id';
  }
  if ( isset($_GET['sort']) ) {
    $sort = $_GET['sort'];
  } else {
    $sort = 'ASC';
  }

?>

  <div class="row">
    <div class="column small-12 medium-6 large-10">
      <h1>Customers</h1>
    </div>
    <div class="column small-12 medium-6 large-2">
      <a class="button" href="add.php">Add New</a>
    </div>
  </div>
  <div class="row">
  <table border="1">
    <thead>
      <tr>
        <?php // Ternary Sorting Logic
          // $sort == 'DESC' ? $sort = 'ASC' : $sort = 'DESC';
        ?>
        <td class="id"><a href="?order=id&&sort=<?php $sort ?>">ID</a></td>
        <td><a href="?order=name&&sort=<?php $sort ?>">Name</a></td>
        <td><a href="?order=email&&sort=<?php $sort ?>">Email</a></td>
        <td><a href="?order=address&&sort=<?php $sort ?>">Address</a></td>
        <td class="edit">Edit</td>
        <td class="delete">Delete</td>
      </tr>
    </thead>
  <?php
    // Query the Database for specific data and put into a variable called $stmt
    $stmt = $pdo->query("SELECT id, name, email, address FROM customers ORDER BY $order $sort");
    // Loop through each row in the table and print out the data as an Associative Array ( key-value pairs)
    while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
      echo("<tr><td>");
      // Call a specific key in the Assoc Array
      echo(htmlentities($row['id']));
      echo("</td><td>");
      echo(htmlentities($row['name']));
      echo("</td><td>");
      echo(htmlentities($row['email']));
      echo("</td><td>");
      echo(htmlentities($row['address']));
      echo("</td><td>");
      // Create a parameter using the ID of a row
      echo('<a class="button tiny" href="edit.php?id='.$row['id'].'"><i class="fas fa-pen"></i></a>');
      echo("</td><td>");
      echo('<a class="button tiny" href="delete.php?id='.$row['id'].'"><i class="fas fa-trash"></i></a>');
      echo("</td></tr>\n");
    }
  ?>
  </table>
  <p><a class="button" href="add.php">Add New</a></p>
