<div align="center">
<h1>Retail Business Management System</h1>
<hr><table border="2">
<?php
include("conn.php");

$res = mysqli_query($conn, "show tables");
$row = mysqli_num_rows($res);
    echo '<tr><td>';
    echo '<a href="table.php?tableName=products">products</a>';
    echo '</td></tr>';

    echo '<tr><td>';
    echo '<a href="table.php?tableName=purchases">purchases</a>';
    echo '</td></tr>';

    echo '<tr><td>';
    echo '<a href="table.php?tableName=customers">customers</a>';
    echo '</td></tr>';

    echo '<tr><td>';
    echo '<a href="table.php?tableName=employees">employees</a>';
    echo '</td></tr>';

    echo '<tr><td>';
    echo '<a href="table.php?tableName=logs">logs</a>';
    echo '</td></tr>';

    echo '<tr><td>';
    echo '<a href="table.php?tableName=suppliers">suppliers</a>';
    echo '</td></tr>';

?>

</table>
<hr>
</div>
<a href="shop.php"><div class="item">Add Purchase</div></a><br>
<a href="product.php"><div class="item">Add Product</div></a><br>
<a href="report.php"><div class="item">Report Monthly Sale</div></a><br>

<style>
    tr {
        text-align: center;
    }
    td {
        padding: 10px;
    }
</style>