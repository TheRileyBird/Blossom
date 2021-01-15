<?php
// Connect to the Database
require_once "../pdo/pdo.php";
// Start Session so that data is passed from one Request to the next
session_start();

// If $_POST contains these headers then run, else (if $_GET Request) skip over
if ( isset($_POST['name']) && isset($_POST['email']) && isset($_POST['address']) ) {

    // Data validation - make sure the form isn't empty
      // If empty, set and display $_SESSION error and redirect to self (current page)
      if ( strlen($_POST['name']) < 1 || strlen($_POST['address']) < 1) {
          $_SESSION['error'] = 'Missing data';
          header("Location: add.php");
          return;
      }
      // If no @ in email, set and display $_SESSION error and redirect to self
      if ( strpos($_POST['email'],'@') === false ) {
          $_SESSION['error'] = 'Bad data';
          header("Location: add.php");
          return;
      }

    // Create a variable for SQL statement. :name, etc are just placeholders for user-created values
    $sql = "INSERT INTO customers (name, email, address)
              VALUES (:name, :email,  :address)";
    // Prepared Statement - instead of running the query directly we say, we pass this SQL, the insert statement, we pass it in and say parse it and read it and figure out where the little placeholders are
    // The prepared statement execution consists of two stages: prepare and execute. At the prepare stage a statement template is sent to the database server. The server performs a syntax check and initializes server internal resources for later use.
    $stmt = $pdo->prepare($sql);
    // We say execute and then we pass in as a parameter. The name maps to $_POST['name'], email maps to $_POST['email'], etc.
    // During execute the client binds parameter values and sends them to the server. The server creates a statement from the statement template and the bound values to execute it using the previously created internal resources.
    $values = array(
        ':name' => $_POST['name'],
        ':email' => $_POST['email'],
        ':address' => $_POST['address']
    );

    // echo '<pre>';
    // var_dump($values, $_POST); exit;
    $stmt->execute($values);
    $_SESSION['success'] = 'Record Added';
    header( 'Location: customers.php' ) ;
    return;
    // In summary, this runs an INSERT statement taking the data from the FORM into the database.

}

// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}

// Require Header
require_once "../structure/header.php";
?>

<h1>Add A New Customer</h1>
<form method="post">
<p>Name:
<input type="text" name="name"></p>
<p>Email:
<input type="email" name="email"></p>
<p>Address:
<input type="text" name="address"></p>
<p><input class="button" type="submit" value="Add New"/>&nbsp;
<a class="button" href="customers.php">Cancel</a></p>
</form>
