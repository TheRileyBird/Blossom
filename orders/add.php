<?php
require_once "../pdo/pdo.php";
session_start();

if ( isset($_POST['customer'])  && isset($_POST['product']) ) {

    // Data validation
    // if ( strlen($_POST['customer_id']) < 1 ) {
    //     $_SESSION['error'] = 'Missing data';
    //     header("Location: add.php");
    //     return;
    // }

    $sql = "INSERT INTO orders (customer_id, desired_date, notes)
              VALUES (:customer, :delivery, :notes)";

    $stmt = $pdo->prepare($sql);

    $values = (array(
        ':customer' => $_POST['customer'],
        ':delivery' => $_POST['delivery'],
        ':notes' => $_POST['notes']
        )
      );

    // echo '<pre>';
    // var_dump($values, $_POST); exit;
    $stmt->execute($values);

    $orderid = $pdo->lastInsertId();

//    // Find Price
//    $price = $pdo->query("SELECT
//        products.price AS price,
//        products.id AS id,
//        order_details.price AS order_price,
//      FROM products
//      JOIN order_details ON products.price=order_details.price");

    // Order Details
    $sql2 = "INSERT INTO order_details (order_id, product_id, quantity, price)
              VALUES (:order, :product, :quantity, :price)";
    $total = $price * $_POST['quantity'];
    $stmt2 = $pdo->prepare($sql2);

    $values2 = (array(
        ':order' => $orderid,
        ':product' => $_POST['product'],
        ':quantity' => $_POST['quantity'],
        ':price' => $price,
        ':$total' => $total
        )
      );

     echo '<pre>';
     var_dump($values, $values2, $_POST); exit;
    $stmt2->execute($values2);


    $_SESSION['success'] = 'Record Added';
    header( 'Location: orders.php' ) ;
    return;
}

// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}

require_once "../structure/header.php";
?>
<h1>Add A New Order</h1>
<form method="post">
<!--<h3>Order #: --><?//= $orderid = $pdo->lastInsertId(); ?><!-- </h3>-->
<div class="row">
  <div class="medium-6 columns">
    <p><label for="customer">Customer:</label>
      <select id="customer" name="customer">
        <option value="none" selected disabled hidden><em>Select Customer</em></option>
        <?php
        $stmt = $pdo->query("SELECT
            customers.name AS name,
            customers.id AS id
          FROM customers");
        while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) { ?>
          <option value="<?php echo(htmlentities($row['id'])); ?>"><?php echo(htmlentities($row['name'])); ?></option>
        <?php } ?>
      </select>
    </p>
  </div>
  <div class="medium-6 columns">
    <p><label for="delivery">Desired Delivery:</label>
      <input type="date" name="delivery">
    </p>
  </div>
</div>

<div class="row">
  <div class="medium-6 columns">
    <p><label for="product">Product:</label>
      <select id="product" name="product">
        <option value="none" selected disabled hidden><em>Select Product</em></option>
        <?php
        $stmt = $pdo->query("SELECT
            products.name AS name,
            products.id AS id,
            products.price AS price
          FROM products");
        while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) { ?>
              <option value="<?php echo(htmlentities($row['id'])); ?>"><?php echo(htmlentities($row['name'])); ?> - $<?php echo(htmlentities($row['price'])); ?></option>
        <?php } ?>
      </select>
    </p>
  </div>
  <div class="medium-6 columns">
    <p><label for="quantity">Quantity:</label>
      <select id="quantity" name="quantity">
        <option value="none" selected disabled hidden><em>Select Quantity</em></option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7">7</option>
        <option value="8">8</option>
        <option value="9">9</option>
        <option value="10">10</option>
      </select>
    </p>
  </div>
</div>

<p><label for="notes">Extra Order Info</label>
<textarea id="notes" name="notes" rows="4" maxlength="150"></textarea></p>

<p><input class="button" type="submit" value="Add New"/>&nbsp;
<a class="button" href="orders.php">Cancel</a></p>
</form>
