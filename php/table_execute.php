<?php
include("conn.php");

$tableName = $_GET["tableName"];

$columns = array();
$res = mysqli_query($conn, "show columns from " . $tableName);
$row = mysqli_num_rows($res);
for($i=0; $i<$row; $i++) {
    $dbrow = mysqli_fetch_array($res);
    array_push($columns, $dbrow[0]);
}

$sql = "SELECT column_name FROM INFORMATION_SCHEMA.`KEY_COLUMN_USAGE` WHERE table_name='" . $tableName . "' AND constraint_name='PRIMARY'";
$res = mysqli_query($conn, $sql);
$primaryKeyName = mysqli_fetch_array($res)[0];

$res = mysqli_query($conn, $sql);
$fetch_array = mysqli_fetch_array($res);
$msg = $fetch_array[1];

echo '<script>alert("$msg");window.location.href=document.referrer;</script>';
?>