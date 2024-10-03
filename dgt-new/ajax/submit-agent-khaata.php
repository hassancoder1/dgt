<?php require_once '../connection.php';
if ($_POST) {
    $type = mysqli_real_escape_string($connect, $_POST['type']);
    $pd_id = mysqli_real_escape_string($connect, $_POST['pd_id_hidden']);
    $khaata_id = mysqli_real_escape_string($connect, $_POST['khaata_id']);
    /*$p_id_hidden = mysqli_real_escape_string($connect, $_POST['p_id_hidden']);
     $title_hidden = mysqli_real_escape_string($connect, $_POST['title_hidden']);*/
    $data = array('type' => $type, 'd_id' => $pd_id, 'khaata_id' => $khaata_id,);
    $dd = fetch('purchase_agents', array('type' => $type, 'd_id' => $pd_id, 'khaata_id' => $khaata_id));
    if (mysqli_num_rows($dd) > 0) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['updated_by'] = $_SESSION['userId'];
        $done = update('purchase_agents', $data, array('type' => $type, 'd_id' => $pd_id, 'khaata_id' => $khaata_id));
    } else {
        $pa_sr = getPurchaseAgentSerial( $khaata_id);
        $data['a_sr'] = $pa_sr;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = $_SESSION['userId'];
        $done = insert('purchase_agents', $data);
    }
    if ($done) {
        $msg = "Agent saved successfully.";
        $type = "success";
    }
    echo messageAjax($type, $msg, '');
    //message('');
} ?>
