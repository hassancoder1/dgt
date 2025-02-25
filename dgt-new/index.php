<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once('header.php'); ?>
<div class="fixed-top">
    <?php require_once('nav-links.php'); ?>

</div>
<div class="container text-center">
    <div class="row justify-content-md-center">
        <div class="col-md-6">
            <h2 class="text-muted mt-5">Welcome to</h2>
            <h1>New Software</h1>
            <hr class="text-warning">
            <?php echo $_SESSION['response'] ?? '';
            //unset($_SESSION['response']); 
            ?>
            <?php //echo $_SESSION['pass'];
            ?>
        </div>
    </div>
</div>
<?php require_once('footer.php'); ?>
<script>
    $("#home").addClass('active');
</script>