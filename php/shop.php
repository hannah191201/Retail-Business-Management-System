<a href=home.php>Back</a><hr>
<?php
include("conn.php");


$customers = array();
$res = mysqli_query($conn, "select * from customers");
$row = mysqli_num_rows($res);
for($i=0; $i<$row; $i++) {
    $customers[$i] = mysqli_fetch_assoc($res);
}

$employees = array();
$res = mysqli_query($conn, "select * from employees");
$row = mysqli_num_rows($res);
for($i=0; $i<$row; $i++) {
    $employees[$i] = mysqli_fetch_assoc($res);
}

$res = mysqli_query($conn, "select * from products, suppliers where products.sid=suppliers.sid");
$row = mysqli_num_rows($res);
$pur = mysqli_query($conn, "select max(pur) from purchases");
$max = mysqli_fetch_row($pur);

for($i=0; $i<$row; $i++) {
    echo '<form method="GET" action="shop_execute.php"><div>';
    $product = mysqli_fetch_assoc($res);

    echo 'PID: <span class="pid">' . $product["pid"] . '</span> ';
    echo '<br>Product: <span class="pname">' . $product["pname"] . '</span> ';
    echo '<br>Supplier: <span class="sname_city">' . $product["sname"] . '</span> ';
    echo '<span class="sname_city"> from ' . $product["city"] . '</span> ';
    echo '<br>Price: <span class="op"><s>' . $product["original_price"] . '</s></span> ';
    echo '<span class="cp">' . $product["original_price"] * (1-$product["discnt_rate"])  . '</span> ';
    echo '<input name="pid" value=' . $product["pid"] . ' style="display:none;">';

    echo '<span class="customers"><br>Customer: <select name="cid">';
    echo '<option value="-1">Select</option>';
    foreach($customers as $c) {
        echo '<option value="' . $c["cid"] . '">' . $c["cname"]. '</option>';
    }
    echo '</select></span>';

    echo '<span class="employees"><br>Employee: <select name="eid">';
    echo '<option value="-1">Select</option>';
    foreach($employees as $e) {
        echo '<option value="' . $e["eid"] . '">' . $e["ename"]. '</option>';
    }
    echo '</select></span>';

    echo '<span class="qty"><br>Quantity: <input name="qty" value="1">';
    echo ' / ' . $product["qoh"] . '</span>';

    echo '<span class="pur"><br>Purchase Index: <input name="pur" value=' . ($max[0]+1) . '>';
    echo '</span>';

    echo '<br><br><input type="submit" value="Order">';

    echo '</div><hr></form>';
}
?>

<style>
    tr {
        text-align: center;
    }
    td {
        padding: 10px;
    }
    input {
        width: 130px;
    }
</style>