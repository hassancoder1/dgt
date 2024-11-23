<?php
require_once '../connection.php';
if ($_POST) {
    $id = mysqli_real_escape_string($connect, $_POST['user_id']);
    $url = '../' . mysqli_real_escape_string($connect, $_POST['url']);
    $image = $_FILES['fileUpload']['name'];
    $path = "../uploads/" . $image;
    $pathN = "uploads/" . $image;
    $moved = move_uploaded_file($_FILES['fileUpload']['tmp_name'], $path);
    if ($moved) {
        $yes = mysqli_query($connect, "UPDATE `users` SET image = '$pathN' WHERE id = '$id'");
        if ($yes) {
            message('success', $url, 'تصویر تبدیل ہوگئی ہے۔');
        } else {
            message('danger', $url, 'ڈیٹابیس پرابلم');
        }
    } else {
        message('info', $url, 'ڈیٹابیس پرابلم');
    }
}
