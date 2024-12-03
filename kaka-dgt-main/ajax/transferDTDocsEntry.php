<?php require_once '../connection.php';
if ($_GET) {
    $id = mysqli_real_escape_string($connect, $_GET['id']);
    $url = '../' . mysqli_real_escape_string($connect, $_GET['url']);
    if ($id) {
        $data = array(
            'is_docs' => 1,
            'docs_at' => date('Y-m-d')
        );
        update('dt_truck_loadings', $data, array('id' => $id));
        message('success', $url, ' ٹرک لوڈنگ ڈاکومنٹس انٹری کے بعد مال انٹری میں ٹرانسفر ہوگئی ہے۔');

    } else {
        message('info', $url, 'کوئی ٹیکنیکل پرابلم ہے۔ ایڈمن سے رابطہ کریں');
    }
}