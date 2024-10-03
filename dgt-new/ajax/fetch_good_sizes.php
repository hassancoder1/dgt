<?php include('../connection.php');
$output = array('size' => array());
if (isset($_POST["goods_id"])) {
    $goods_id = $_POST['goods_id'];
    //$goods = fetch('good_details', array('goods_id' => $goods_id));
    $goods = mysqli_query($connect, "SELECT DISTINCT size FROM good_details WHERE goods_id = '$goods_id'");
    $count = mysqli_num_rows($goods);
    if ($count > 0) {
        echo '<option value="">ALL SIZE</option>';
        while ($row = mysqli_fetch_array($goods)) {
            echo '<option value="' . $row['size'] . '">' . $row['size'] . '</option>';
        }
    } else {
        echo '<option value="">No record</option>';
    }
} ?>