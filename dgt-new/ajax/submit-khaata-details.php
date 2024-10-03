<?php require_once '../connection.php';
if ($_POST) {
    //$msg = "DB Error";$type = "danger";
    $khaata_id_hidden = mysqli_real_escape_string($connect, $_POST['khaata_id_hidden']);
    $title_hidden = mysqli_real_escape_string($connect, $_POST['title_hidden']);
    $type_hidden = mysqli_real_escape_string($connect, $_POST['type_hidden']);
    $url = "purchase-loading";
    $data = array('khaata_id' => $khaata_id_hidden, 'static_type' => 'Extra');
    $temp = array('comp_name' => 'comp_name', 'country_id' => 'country_id', 'city' => 'city', 'address' => 'address', 'report' => 'report');;
    if (isset($_POST['indexes'])) {
        $data['indexes'] = json_encode($_POST['indexes']);
        $data['vals'] = json_encode($_POST['vals']);
    }
    foreach ($temp as $index => $value) {
        $data[$index] = mysqli_real_escape_string($connect, $_POST[$value]);
    }
    $data['created_at'] = date('Y-m-d H:i:s');
    $data['created_by'] = $_SESSION['userId'];
    $done = insert('khaata_details', $data);
    if ($done) {
        $msg = "Acc. details saved successfully.";
        $type = "success";
    }
    echo messageAjax($type,$msg,'');
    //message($type, $url, $msg);
}
?>
