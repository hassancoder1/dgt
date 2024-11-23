<?php
require_once '../connection.php';
if ($_POST) {
    $sender_id = mysqli_real_escape_string($connect, $_POST['sender_id']);
    $receiver_id = mysqli_real_escape_string($connect, $_POST['receiver_id']);
    $url = '../chat.php?id=' . $receiver_id;
    $image = $_FILES['attachment']['name'];
    $path = "../attachment/" . $image;
    $pathN = "attachment/" . $image;
    $today = date('y-m-d h:i:s');
    $moved = move_uploaded_file($_FILES['attachment']['tmp_name'], $path);
    if ($moved) {
        $yes = mysqli_query($connect, "INSERT INTO chats(is_file,msg, sender_id, receiver_id) VALUES (1,'$pathN', '$sender_id','$receiver_id')");
        //$yes = mysqli_query($connect, "UPDATE `users` SET image = '$pathN' WHERE id = '$id'");
        if ($yes) {
            message('success', $url, 'Attachment sent.');
        } else {
            message('danger', $url, 'Database Error!!!');
        }
    } else {
        message('info', $url, 'Something is wrong. Contact Admin');
    }
}
