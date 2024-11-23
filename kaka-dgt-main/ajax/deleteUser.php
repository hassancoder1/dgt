<?php require_once '../connection.php';
if ($_GET) {
    $id = mysqli_real_escape_string($connect, $_GET['id']);
    $tbl = mysqli_real_escape_string($connect, $_GET['tbl']);
    $url = '../' . mysqli_real_escape_string($connect, $_GET['url']);
    if ($id) {
        $del = mysqli_query($connect, "UPDATE users SET is_active = 0 WHERE id = '$id'");
        if ($del) {
            message('success', $url, 'یوزر ختم ہوگیا');
        } else {
            message('danger', $url, 'ڈیٹا بیس پرابلم');
        }
    } else {
        message('info', $url, 'کوئی ٹیکنیکل پرابلم ہے۔ ایڈمن سے رابطہ کریں');
    }
}