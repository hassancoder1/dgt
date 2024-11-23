<?php require_once '../connection.php';
if ($_GET) {
    $id = mysqli_real_escape_string($connect, $_GET['id']);
    $url = '../' . mysqli_real_escape_string($connect, $_GET['url']);
    if ($id) {
        $data = array(
            'is_border' => 1,
            'border_date' => date('Y-m-d')
        );
        update('ut_bail_entries', $data, array('id' => $id));
        message('success', $url, 'ایکسپورٹ کسٹم سے افغان بارڈرکلیننگ کسٹم میں ٹرانسفر ہوگیا ہے۔', ' بیل انٹری نمبر ', $id);
    } else {
        message('info', $url, 'کوئی ٹیکنیکل پرابلم ہے۔ ایڈمن سے رابطہ کریں');
    }
}
