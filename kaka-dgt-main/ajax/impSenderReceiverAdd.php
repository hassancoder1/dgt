<?php include("../connection.php");
$userId = $_SESSION['userId'];
if ($_POST['tl_id_hidden']) {
    $message = '';
    $data = array(
        'sender_receiver' => json_encode($_POST, JSON_UNESCAPED_UNICODE)
    );
    $done = update('imp_truck_loadings', $data, array('id' => $_POST['tl_id_hidden']));
    if ($done) {
        $recc = fetch('imp_truck_loadings', array('id' => $_POST['tl_id_hidden']));
        $rec = mysqli_fetch_assoc($recc);
        $sender_receiver = $rec['sender_receiver'];
        $message = messageAjax('success', 'مال بھیجنے والا / وصول کرنے والا محفوظ ہو گیا ہے۔');
    } else {
        $message = messageAjax('danger', 'ڈیٹا بیس پرابلم');
    }
    echo json_encode($_POST);
} ?>
