<script type="text/javascript" src="lib_js/jscharts_mb.js"></script>
</form><a href=report.php>Back</a><hr>
<?php
include("conn.php");

$pid = $_GET["pid"];

$data = array();

$columns = array();
$res = mysqli_query($conn, 'call report_monthly_sale(\'' . $pid . '\')');
$row = mysqli_num_rows($res);
for($i=0; $i<$row; $i++) {
    $dbrow = mysqli_fetch_array($res);
    $year = $dbrow[2];

    if(!isset($data[$year])) {
        $data[$year] = array();
    }

    $arr = array();
    for($j=0; $j<6; $j++) {
        $arr[$j] = $dbrow[$j];
    }
    array_push($data[$year], $arr);
}

if(count($data)==0) {
    die('<script>alert("No data!");</script>');
}

echo "<script>var data=JSON.parse('" . json_encode($data) . "');</script>";

foreach($data as $year => $yearTable) {

    echo '<h3>' . $year . 'Annual Report</h3><table border="2"><tr><td>Product</td><td>Month</td><td>Year</td><td>Sale</td><td>Total Price</td><td>Average Price</td></tr>';

    foreach($yearTable as $row) {
        echo '<tr>';
        for($j=0; $j<6; $j++) {
            echo '<td>' . $row[$j] . '</td>';
        }
        echo '</tr>';
    }
    echo '</table>';
    echo '<div id="' . $year . '1" style="display:inline-block;"></div>';
    echo '<div id="' . $year . '2" style="display:inline-block;"></div><hr>';
}
?>
</div>

<style>
    tr {
        text-align: center;
    }
    td {
        padding: 10px;
    }
</style>