<?php
  include("conn.php");

  $pid = $_GET["pid"];
  $pname = $_GET["pname"];
  $sid = $_GET["sid"];
  $qoh = $_GET["qoh"];
  $qoh_threshold = $_GET["qoh_threshold"];
  $original_price = $_GET["original_price"];
  $discnt_rate = $_GET["discnt_rate"];
  
  $res = mysqli_query($conn, 'call add_product(' . $pid . ',\'' . $pname . '\',' . $qoh . ',' . $qoh_threshold . ',' . $original_price . ',' . $discnt_rate . ',\'' . $sid . '\')');
  if (!$res) {
      echo '<script>alert("fail to add new product!");window.location.href=document.referrer;</script>';
      exit();
  }

  echo '<script>alert("added successfully!");window.location.href=document.referrer;</script>';
  ?>
