<?php
session_reset();
session_start();
require("cart.php");
$cart = new Cart();
if(!empty($_GET["action"])) {
    switch($_GET["action"]) {
        case "add":
            if(!empty($_POST["quantity"] && (int)$_POST["quantity"] >= 1)) {
                $productByName = $cart->GetProductByName($_GET["name"]);
                $item_array = array(
                    "name" => $productByName["name"],
                    "price" => $productByName["price"],
                    "quantity" => $_POST["quantity"]);

                if (!empty($_SESSION["cart_item"])) {
                    if (In_array($item_array["name"], array_column($_SESSION["cart_item"],"name"))) {
                        $index = array_search($item_array["name"],array_column($_SESSION["cart_item"], "name"));
                        if (empty($_SESSION["cart_item"][$index]["quantity"])) {
                            $_SESSION["cart_item"][$index]["quantity"] = $item_array["quantity"];
                        }
                        $_SESSION["cart_item"][$index]["quantity"] += (int)$item_array["quantity"];
                    } else {
                        array_push($_SESSION["cart_item"], $item_array);
                    }
                } else {
                    $_SESSION["cart_item"] = array($item_array);
                }
            }
            break;
        case "empty":
            unset($_SESSION["cart_item"]);
            break;
        case "remove":
            if(!empty($_SESSION["cart_item"])) {
                if (count($_SESSION["cart_item"])==1) {
                    unset($_SESSION["cart_item"]);
                }
                else {
                    if (In_array($_GET["name"], array_column($_SESSION["cart_item"],"name"))) {
                        $index = array_search($_GET["name"],array_column($_SESSION["cart_item"],"name"));
                        array_splice($_SESSION["cart_item"],$index,1);
                        if(empty($_SESSION["cart_item"]))
                            unset($_SESSION["cart_item"]);
                    }
                }
            }
            break;
    }
}
?>
<HTML>
<HEAD>
    <TITLE>Shopping Cart</TITLE>
    <link href="style.css" type="text/css" rel="stylesheet" />
</HEAD>
<BODY>
<div id="shopping-cart">
    <div class="txt-heading">Shopping Cart</div>

    <a id="btnEmpty" href="index.php?action=empty">Empty Cart</a>
    <?php
    if(isset($_SESSION["cart_item"])){
        $total_quantity = 0;
        $total_price = 0;
    ?>
    <table class="tbl-cart" cellpadding="10" cellspacing="1">
        <tbody>
        <tr>
            <th style="text-align:left;" width="30%">Name</th>
            <th style="text-align:right;" width="10%">Unit Price</th>
            <th style="text-align:right;" width="5%">Quantity</th>
            <th style="text-align:right;" width="10%">Line Total</th>
            <th style="text-align:center;" width="5%">Remove</th>
        </tr>

        <?php
        $lines = $_SESSION["cart_item"];
        $item = new stdClass();
        foreach ($lines as $item => $value){
            $item_price = $lines[$item]["quantity"] * $lines[$item]["price"];
            ?>
            <tr>
                <td style="text-align:left;"><?php echo $lines[$item]["name"]; ?></td>
                <td style="text-align:right;"><?php echo "$ ".$lines[$item]["price"]; ?></td>
                <td style="text-align:right;"><?php echo $lines[$item]["quantity"]; ?></td>
                <td style="text-align:right;"><?php echo "$ ". number_format($item_price,2); ?></td>
                <td style="text-align:center;">
                    <a href="index.php?action=remove&name=<?php echo $_SESSION["cart_item"][$item]["name"]; ?>" class="btnRemoveAction">
                        <img src="icon-delete.png" alt="Remove Item" />
                    </a>
                </td>
            </tr>
            <?php
            $total_quantity += (int)$_SESSION["cart_item"][$item]["quantity"];
            $total_price += ((float)$_SESSION["cart_item"][$item]["price"]*(int)$_SESSION["cart_item"][$item]["quantity"]);
        }
        ?>

        <tr>
            <td colspan="2" align="right">Total:</td>
            <td align="right"><?php echo $total_quantity; ?></td>
            <td align="right" colspan="1"><strong><?php echo "$".number_format($total_price, 2); ?></strong></td>
            <td></td>
        </tr>
        </tbody>
    </table>
        <?php
    } else {
        ?>
        <div class="no-records">Your Cart is Empty</div>
        <?php
    }
    ?>
</div>

<div id="product-grid">
    <div class="txt-heading">Products</div>
    <?php
    $product_array = $cart -> GetProducts();
    if (!empty($product_array)) {
        foreach($product_array as $key=>$value){
            ?>
            <div class="product-item">
                <form method="POST" action="index.php?action=add&name=<?php echo $product_array[$key]["name"]; ?>">
                    <div class="product-tile-footer">
                        <div class="product-title"><?php echo $product_array[$key]["name"]; ?></div>
                        <div class="product-price"><?php echo "$".number_format($product_array[$key]["price"],2); ?></div>
                        <div class="cart-action">
                            <input type="number" min="1" name="quantity" class="product-quantity"  value="1" size="2" />
                            <input type="submit" value="Add to Cart"  class="btnAddAction" />
                        </div>
                    </div>
                </form>
            </div>
            <?php
        }
    }
    ?>
</div>
</BODY>
</HTML>