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
  }
?>

  <div class="row">
    <div class="column small-12 medium-6 large-10">
      <h1>Orders</h1>
    </div>
    <div class="column small-12 medium-6 large-2">
      <a class="button" href="add.php">Add New</a>
    </div>
  </div>
  <div class="row">
  <table border="1">
    <thead>
      <tr>
        <td class="id">#</td>
        <td>Customer</td>
        <td>Order Details</td>
        <td>Date Ordered</td>
        <td>Desired Delivery</td>
        <td>Fulfilled</td>
        <td>Paid</td>
        <td class="edit">Edit</td>
        <td class="delete">Delete</td>
      </tr>
    </thead>
  <?php
    $stmt = $pdo->query("SELECT
        orders.id AS id,
        customers.name AS name,
        orders.order_date AS order_date,
        orders.desired_date AS desired_date,
        orders.fulfilled AS fulfilled,
        orders.paid AS paid
      FROM orders
      JOIN customers ON orders.customer_id=customers.id");

    while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
      echo("<tr><td>");
      echo(htmlentities($row['id']));
      echo("</td><td>");
      echo(htmlentities($row['name']));
      echo("</td><td>");
      echo(htmlentities($row['name']));
      echo("</td><td>");
      echo $row['order_date'] ? (new DateTime($row['order_date']))->format('m.d.y') : 'No order date';
      echo("</td><td>");
      echo $row['desired_date'] ? (new DateTime($row['desired_date']))->format('m.d.y') : 'None';
      echo("</td><td>");
      echo $row['fulfilled'] > 0 ? 'Yes' : 'No';
      echo("</td><td>");
      echo $row['paid'] > 0 ? 'Yes' : 'No';
      echo("</td><td>");
      echo('<a class="button tiny" href="edit.php?id='.$row['id'].'&name='.$row['name'].'"><i class="fas fa-pen"></i></a>');
      echo("</td><td>");
      echo('<a class="button tiny" href="delete.php?id='.$row['id'].'&name='.$row['name'].'"><i class="fas fa-trash"></i></a>');
      echo("</td></tr>\n");
    }
  ?>
  </table>
  <p><a class="button" href="add.php">Add New</a></p>
