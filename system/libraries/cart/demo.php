<?php
// You must included cart.php BEFORE you start the session. 
include "Cart.php";
session_start();      // start the session

$cart = $_SESSION['cart']; // point $cart to session cart.
if ( ! is_object($cart)) {
	$cart = new Cart(); // if $cart ( $_SESSION['cart'] ) isn't an object, make a new cart
}

// end of header stuff

?>
<html><head><title>Cart Demo</title></head>
<body><h3>Cart Demo</h3>

<?php

// Usually you would get your products from a database but we'll pretend.. 

$products = array();
$products['A'] = array("id"=>'A',"name"=>"A Bar of Soap","price"=>2.00);
$products['B'] = array("id"=>'B',"name"=>"Shampoo","price"=>4.80);
$products['C'] = array("id"=>'C',"name"=>"Pizza","price"=>12.95);


// check to see if any items are being added
if(isset($_POST['add'])) {
	$product = $products[$_POST['id']];
	$cart->add_item($product['id'],$_POST['qty'],$product['price'],$product['name']);
}
if(isset($_POST['remove'])) {
	$rid = $_POST['id'];
	$cart->remove_item($rid);

}

if(isset($_POST['empty'])) {
	$cart->empty_cart();

}

// spit some forms
// You can have many different types of forms, such as many quantity boxes
// and an "add to cart" button at the bottom which adds all items
// but for the purposes of this demo we will handle one item at a time. 
echo "<table>";
foreach($products as $p) {
	echo "<tr><td><form method='post' action='demo.php'>";
	echo "<input type='hidden' name='id' value='".$p['id']."'/>";
	echo "".$p['name'].' $'.number_format($p['price'],2)." ";
	echo "<input type='text' name='qty' size='5' value='1'><input type='submit' value='Add to cart' name='add'>";
	echo "</form></td></tr>";
}
echo "</table>";


echo "<h2>Items in cart</h2>";

if(count($cart->items) > 0) {
	foreach($cart->get_cart_data() as $item) {
		echo "<br />Item:<br/>";
		echo "Code/ID :".$item['id']."<br/>";
		echo "Quantity:".$item['quantity']."<br/>";
		echo "Price   :$".number_format($item['price'],2)."<br/>";
		echo "Info    :".$item['info']."<br />";
		echo "Subtotal :$".number_format($item['subtotal_price'],2)."<br />";
		echo "<form method=post><input type='hidden' name='id' value='".$item['id']."'/><input type='submit' name='remove' value='Remove'/></form>";
	}
	echo "---------------------<br>";
	echo "total: $".number_format($cart->total_price,2);
} else {
	echo "No items in cart";
}

echo "<form method=post><input type='submit' name='empty' value='Empty Cart'/></form>";

echo '<pre>';
print_r($cart->items);
print_r($cart->item_prices);
print_r($cart->item_quantities);
print_r($cart->item_info);
print_r($cart->get_cart_data());
echo '</pre>';
?>
