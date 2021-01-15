<?php
require_once "../pdo/pdo.php";
session_start();
require_once "../structure/header.php";

  if ( isset($_SESSION['error']) ) {
    echo '<p style="color: red;">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
  }
  if ( isset($_SESSION['success']) ) {
    echo '<p style="color: green;">'.$_SESSION['success']."</p>\n";
    unset($_SESSION['success']);
  } ?>
<div class="row">
  <div class="column small-12 medium-6 large-10">
    <h1>Products</h1>
  </div>
  <div class="column small-12 medium-6 large-2">
    <a class="button" href="add.php">Add New</a>
  </div>
</div>
<div class="row">
  <table border="1">
    <thead>
      <tr>
        <td class="id">ID</td>
        <td>Name</td>
        <td>Price</td>
        <td class="edit">Edit</td>
        <td class="delete">Delete</td>
      </tr>
    </thead>
  <?php
    $stmt = $pdo->query("SELECT id, name, price FROM products");
    while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
      echo("<tr><td>");
      echo(htmlentities($row['id']));
      echo("</td><td>");
      echo(htmlentities($row['name']));
      echo("</td><td>$");
      echo(htmlentities($row['price']));
      echo("</td><td>");
      echo('<a class="button tiny" href="edit.php?id='.$row['id'].'"><i class="fas fa-pen"></i></a>');
      echo("</td><td>");
      echo('<a class="button tiny" href="delete.php?id='.$row['id'].'"><i class="fas fa-trash"></i></a>');
      echo("</td></tr>\n");
    }
  ?>
  </table>
  <p><a class="button" href="add.php">Add New</a></p>
</div>
