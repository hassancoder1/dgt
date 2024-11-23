<?php require_once '../connection.php';
if ($_GET) {
    $id = mysqli_real_escape_string($connect, $_GET['id']);
    $url = '../' . mysqli_real_escape_string($connect, $_GET['url']);
    if ($id) {
        $data = array(
            'is_godam' => 1,
            'godam_date' => date('Y-m-d')
        );
        update('ut_bail_entries', $data, array('id' => $id));
        message('success', $url, ' قندھار کلئیرنگ کسٹم سے خالی کرنے گودام میں ٹرانسفر ہوگیا ہے۔', ' بیل انٹری نمبر ', $id);
    } else {
        message('info', $url, 'کوئی ٹیکنیکل پرابلم ہے۔ ایڈمن سے رابطہ کریں');
    }
}
