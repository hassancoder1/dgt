<?php require_once '../connection.php';
if ($_GET) {
    $id = mysqli_real_escape_string($connect, $_GET['id']);
    $pk = mysqli_real_escape_string($connect, $_GET['pk']);
    $active = mysqli_real_escape_string($connect, $_GET['active']);
    $message = mysqli_real_escape_string($connect, $_GET['message']);
    $tbl = mysqli_real_escape_string($connect, $_GET['tbl']);
    $url = '../' . mysqli_real_escape_string($connect, $_GET['url']);/*. '.php'*/
    if ($id) {
        $del = mysqli_query($connect, "UPDATE `$tbl` SET is_active = '$active' WHERE $pk = '$id'");
        if ($del) {
            message('success', $url, ' منتخب ریکارڈ ' . $message . ' ہو گیا ہے۔ ');
        } else {
            message('danger', $url, 'ڈیٹا بیس پرابلم');
        }
    } else {
        message('info', $url, 'کوئی ٹیکنیکل پرابلم ہے۔ ایڈمن سے رابطہ کریں');
    }
}