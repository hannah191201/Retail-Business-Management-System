<a href=home.php>Back</a><hr>
<div align="center"><table border="2">

<?php
include("conn.php");

$tableName = $_GET["tableName"];

echo "<span style='font-size: 32px'>$tableName";

$columns = array();
$res = mysqli_query($conn, "show columns from " . $tableName);
$row = mysqli_num_rows($res);

for($i=0; $i<$row; $i++) {
    $dbrow = mysqli_fetch_array($res);
    echo '<td><b>' . $dbrow[0] . '</b></td>';
    array_push($columns, $dbrow[0]);
}

echo '<input name="tableName" value="' . $tableName . '" style="display:none;">';
echo '<input name="opType" value="insert" style="display:none;">';
echo '</tr></form>';

$res = mysqli_query($conn, "call show_" . $tableName);
$row = mysqli_num_rows($res);

for($i=0; $i<$row; $i++) {
    echo '<form method="GET" action="table_execute.php"><tr>';
    $dbrow = mysqli_fetch_array($res);

    $url = "table_execute.php?tableName=" . $tableName . '&';

    for($j=0; $j<count($columns); $j++) {
        $key = $columns[$j];
        $val = $dbrow[$key];
        echo '<td><input name="' . $key . '" value="' . $val . '"></td>';

        $url .= $key . '=' . $val . '&';
    }

    $url .= 'opType=delete';

    echo '<input name="tableName" value="' . $tableName . '" style="display:none;">';
    echo '<input name="opType" value="update" style="display:none;">';
}
?>
</table>

<?php
if($row==0) {
    echo "No record";
}
?>
<hr></div>


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