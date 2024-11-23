<?php
require_once ('functions.php');
session_start();
//message('success','index.php','Logged out successfully.');
session_destroy();
unset($_SESSION['userId']);
unset($_SESSION['role']);
unset($_SESSION['email']);
unset($_SESSION['franchise_id']);
unset($_SESSION['full_name']);
echo '<script>window.location.href="login";</script>';
