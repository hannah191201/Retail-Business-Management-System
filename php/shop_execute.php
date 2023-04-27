<?php
  include("conn.php");

  $cid = $_GET["cid"];
  $eid = $_GET["eid"];
  $pid = $_GET["pid"];
  $qty = $_GET["qty"];
  $pur = $_GET["pur"];
  
  $res = mysqli_query($conn, 'call add_purchase(' . $pur . ',\'' . $cid . '\',\'' . $eid . '\',\'' . $pid . '\',' . $qty . ')');
  if (!$res) {
      printf("Error: %s\n", mysqli_error($conn));
      exit();
    }
  $row = mysqli_num_rows($res);
  $fetch_array = mysqli_fetch_array($res);
  $msg = $fetch_array[1];

  echo'<script>alert("'.$msg.'");window.location.href=document.referrer;</script>';
  ?>
