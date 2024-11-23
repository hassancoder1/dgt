<?php
require_once '../connection.php';

if ($_GET) {
    $id = mysqli_real_escape_string($connect, $_GET['id']);
    $url = '../' . mysqli_real_escape_string($connect, $_GET['url']);
    $r_type = mysqli_real_escape_string($connect, $_GET['r_type']);
    if ($id && $url && $r_type) {
        $r_moved = mysqli_query($connect, "INSERT INTO roznamchaas_deleted SELECT * FROM roznamchaas WHERE r_id = '$id'");
        if ($r_moved) {
            $deleted = mysqli_query($connect, "DELETE FROM `roznamchaas` WHERE r_id = '$id'");
            if ($deleted) {
                message('success', $url, roznamchaName($r_type) . ' ختم ہوگیا. ');
            } else {
                message('danger', $url, 'ڈیٹا بیس پرابلم');
            }
        } else {
            message('danger', $url, 'ڈیٹا بیس پرابلم');
        }
    } else {
        message('info', $url, 'کوئی ٹیکنیکل پرابلم ہے۔ ایڈمن سے رابطہ کریں');
    }
} else {
    message('info', '../', 'کوئی ٹیکنیکل پرابلم ہے۔ ایڈمن سے رابطہ کریں');
}