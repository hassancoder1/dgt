<?php
require_once '../connection.php';
$branchId = $_SESSION['branch_id'];
if ($_GET) {
    $id = mysqli_real_escape_string($connect, $_GET['id']);
    $url = '../' . mysqli_real_escape_string($connect, $_GET['url']);
    if ($id) {
        $data = array(
            'is_saved' => 1,
            'saved_at' => date('Y-m-d')
        );
        update('dt_truck_loadings', $data, array('id' => $id));
        message('success', $url, ' ٹرک لوڈنگ ٹرانسفر ہوگئی ہے۔');

    } else {
        message('info', $url, 'کوئی ٹیکنیکل پرابلم ہے۔ ایڈمن سے رابطہ کریں');
    }
}
