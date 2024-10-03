<?php
require_once '../connection.php';

if ($_GET) {
    $msg = 'DB Error';
    $type = 'danger';
    $id = mysqli_real_escape_string($connect, $_GET['id']);
    $r_type = mysqli_real_escape_string($connect, $_GET['r_type']);
    $url = '../' . mysqli_real_escape_string($connect, $_GET['url']);
    if ($id && $url && $r_type) {
        $r_moved = mysqli_query($connect, "INSERT INTO roznamchaas_deleted SELECT * FROM roznamchaas WHERE r_id = '$id'");
        if ($r_moved) {
            $deleted = mysqli_query($connect, "DELETE FROM `roznamchaas` WHERE r_id = '$id'");
            if ($deleted) {
                $msg = $r_type . ' deleted. ';
                $type = 'success';
            }
        }
    }
    message($type, $url, $msg);
} else {
    message('info', '../', 'DB Error');
}