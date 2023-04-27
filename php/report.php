<a href=home.php>Back</a><hr>
<?php
include("conn.php");

$res = mysqli_query($conn, "select * from products, suppliers where products.sid=suppliers.sid");
$row = mysqli_num_rows($res);
for($i=0; $i<$row; $i++) {
    echo '<form method="GET" action="report_execute.php"><div>';
    $product = mysqli_fetch_assoc($res);

    echo '<span class="pname">' . $product["pname"] . '</span> ';
    echo '<span class="sname_city">' . $product["sname"] . '</span> ';
    echo '<span class="sname_city">' . $product["city"] . '</span> ';
    echo '<span class="op"><s>' . $product["original_price"] . '</s></span> ';
    echo '<span class="cp">' . $product["original_price"] * (1-$product["discnt_rate"])  . '</span> ';
    echo '<input name="pid" value=' . $product["pid"] . ' style="display:none;">';
    echo '<input type="submit" value="Report">';

    echo '</div><hr></form>';
}
?>

<style>
    div {
        display: table-cell;
        height: 200px;
        vertical-align: middle;
        text-align: center
    }
    .pname {
        display: inline-block;
        width: 200px;
        font-size: 32px;
        padding: 20px;
    }
    .sname_city {
        display: inline-block;
        width: 100px;
        font-size: 24px;
        padding: 10px;
    }
    .op {
        margin-left: 20px;
        display: inline-block;
        width: 50px;
        color: gray;
    }
    .cp {
        display: inline-block;
        width: 50px;
        color: red;
        font-size: 24px;
    }
    input {
        width: 100px;
        padding: 5px;
    }

</style>