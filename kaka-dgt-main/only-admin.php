<?php if (Administrator()) {
    //$_SESSION['response'] = 'Welcome Admin';
    //echo '<script>alert("Welcome Admin");</script>';
} else {
    message('danger', 'index.php', 'صرف ایڈمن یہ پیج استعمال کر سکتے ہیں۔');
}