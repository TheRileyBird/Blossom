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
    <h1>Users</h1>
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
      <td><a href="?order=name&&sort=<?php $sort ?>">Username</a></td>
      <td><a href="?order=email&&sort=<?php $sort ?>">Email</a></td>
      <td><a href="?order=address&&sort=<?php $sort ?>">Password</a></td>
      <td class="edit">Edit</td>
      <td class="delete">Delete</td>
    </tr>
  </thead>
  <?php
  $stmt = $pdo->query("SELECT id, username, email, password, create_time FROM users");
  while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    echo("<tr><td>");
    echo(htmlentities($row['id']));
    echo("</td><td>");
    echo(htmlentities($row['username']));
    echo("</td><td>");
    echo(htmlentities($row['email']));
    echo("</td><td>");
    echo(htmlentities($row['password']));
    echo("</td><td>");
    echo('<a class="button tiny" href="edit.php?id='.$row['id'].'"><i class="fas fa-pen"></i></a>');
    echo("</td><td>");
    echo('<a class="button tiny" href="delete.php?id='.$row['id'].'"><i class="fas fa-trash"></i></a>');
    echo("</td></tr>\n");
  }
  ?>
  </table>
  <p><a class="button" href="add.php">Add New</a></p>
