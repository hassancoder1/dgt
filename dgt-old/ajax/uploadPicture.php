<?php
require_once '../connection.php';
if ($_POST) {
    $pk = mysqli_real_escape_string($connect, $_POST['pk']);
    $id = mysqli_real_escape_string($connect, $_POST['id']);
    $tbl = mysqli_real_escape_string($connect, $_POST['tbl']);
    $url = '../' . mysqli_real_escape_string($connect, $_POST['url']);

    $image = $_FILES['fileUpload']['name'];
    $path = "../uploads/" . $image;
    $pathN = "uploads/" . $image;
    $moved = move_uploaded_file($_FILES['fileUpload']['tmp_name'], $path);
    if ($moved) {
        $yes = mysqli_query($connect, "UPDATE `$tbl` SET image = '$pathN' WHERE `$pk` = '$id'");
        if ($yes) {
            message('success', $url, 'Picture updated');
        } else {
            message('danger', $url, 'DB Problem');
        }
    } else {
        message('info', $url, 'DB Problem');
    }
}
