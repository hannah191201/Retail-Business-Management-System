<a href=home.php>Back</a><hr>
<?php
include("conn.php");

echo '<form method="GET" action="product_execute.php"><div>';

echo '<span class="set">PID: <input name="pid"<br>';
echo '<br>Product Name: <input name="pname"<br>';
echo '<br>SID: <input name="sid"<br>';
echo '<br>Quantity on Hand: <input name="qoh"<br>';
echo '<br>Quantity Threshold: <input name="qoh_threshold"<br>';
echo '<br>Original Price: <input name="original_price"<br>';
echo '<br>Discount Rate: <input name="discnt_rate"<br>';

echo '<br><br><input type="submit" value="Add">';

echo '</div><hr></form>';
?>

<style>
    tr {
        text-align: center;
    }
    td {
        padding: 20px;
    }
    input {
        width: 100px;
    }
</style>