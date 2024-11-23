<?php include("connection.php");
$BS = busines_setting(); ?>
    <!DOCTYPE html>
    <html lang="ar" dir="rtl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="description" content="BISMILLAH & BROTHERS Import Export Wholesaler">
        <meta name="author" content="BISMILLAH & BROTHERS">
        <meta name="keywords" content="Import Export Wholesaler">
        <title><?php echo ucfirst(pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME)); ?>
            | <?php echo $BS['sitename']; ?></title>
        <link rel="preconnect" href="https://fonts.googleapis.com/">
        <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&amp;display=swap"
              rel="stylesheet">
        <link rel="stylesheet" href="assets/vendors/core/core.css">
        <link rel="stylesheet" href="assets/fonts/feather-font/css/iconfont.css">
        <link rel="stylesheet" href="assets/vendors/flag-icon-css/css/flag-icon.min.css">
        <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
        <link rel="stylesheet" href="assets/css/style-rtl.min.css">
        <link rel="stylesheet" href="assets/css/custom.css">
        <link rel="shortcut icon" href="assets/images/anitco.png"/>
        <style>
            .card {
                /*border: 1px solid #e6934b;*/
            }
        </style>
    </head>
    <body style="background: radial-gradient(circle, #c37c2e 0%, rgba(0,0,0,1) 92%);">

    <div class="main-wrapper">
        <div class="page-wrapper full-page">
            <div class="page-content d-flex align-items-center justify-content-center">
                <div class="row w-100 mx-0 auth-page">
                    <div class="col-md-5 col-xl-3 mx-auto">
                        <div class="card shadow-lg border border-primary border-opacity-50">
                            <div class="row">
                                <div class="col-md-12 text-center ">
                                    <img src="assets/images/favicon.jpg" alt="logo"
                                         class="img-fluid w-25 rounded shadow rounded-2" style="margin-top: -3rem !important">
                                    <div class="px-4 pb-3 pt-3">
                                        <form class="forms-sample" method="post">
                                            <?php if (isset($_SESSION['response'])) {
                                                echo $_SESSION['response'];
                                                unset($_SESSION['response']);
                                            } ?>
                                            <div class="mb-3">
                                                <div class="input-group">
                                                    <label for="username"
                                                           class="input-group-text border-bottom border-primary rounded-0"><i
                                                            class="mdi mdi-account"></i></label>
                                                    <input type="text" autofocus class="form-control form-control-lg" id="username"
                                                           placeholder="یوزر نام" name="username" required>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <div class="input-group">
                                                    <label for="pass"
                                                           class="input-group-text border-bottom border-primary rounded-0"><i
                                                            class="mdi mdi-lock-open-outline"></i></label>
                                                    <input type="password" class="form-control form-control-lg" id="pass" name="pass"
                                                           autocomplete="current-password" placeholder="پاسورڈ"
                                                           required>
                                                </div>
                                            </div>
                                            <div class="text-start">
                                                <button name="loginSubmit" id="loginSubmit" type="submit"
                                                        class="btn btn-dark btn-icon-text mt-3">
                                                    <span>جاری رکھیں</span>
                                                    <i class="btn-icon-prepend flip-x ms-2" data-feather="log-in"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="assets/vendors/core/core.js"></script>
    <script src="assets/vendors/feather-icons/feather.min.js"></script>
    <script src="assets/js/template.js"></script>
    </body>
    </html>
<?php if (isset($_POST['loginSubmit'])) {
    $url = $str = $type = "";
    $username = mysqli_real_escape_string($connect, $_POST['username']);
    $password = mysqli_real_escape_string($connect, $_POST['pass']);
    //$password = md5($password);
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $connect->query($sql);
    if ($result->num_rows > 0) {
        $mainSql = "SELECT * FROM users WHERE username = '$username' AND pass = '$password'";
        $mainResult = $connect->query($mainSql);
        if ($mainResult->num_rows > 0) {
            $value = $mainResult->fetch_assoc();
            if ($value['is_active'] == 1) {
                $_SESSION['userId'] = $value['id'];
                $_SESSION['role'] = $value['role'];
                $_SESSION['username'] = $username;
                $_SESSION['branch_id'] = $value['branch_id'];
                if ($value['role'] == 'admin') {
                    $str = "آپ بطورایڈمن لاگ ان ہوگئے ہیں۔";
                }
                if ($value['role'] == 'manager') {
                    $str = "آپ بطورمینیجر لاگ ان ہوگئے ہیں۔";
                }
                $url = "./";
                $type = "success";
            } else {
                $str = "آپ کا اکاونٹ بلاک کردیا گیا ہے۔ ایڈمن سے رابطہ کریں۔";
                $url = "login";
                $type = "info";
            }
        } else {
            $str = "آپ کادرج کردہ پاسورڈغلط ہے۔";
            $url = "login";
            $type = "warning";
        }
    } else {
        $str = "آپ کادرج کردہ یوزرنام غلط ہے۔";
        $url = "login";
        $type = "warning";
    }
    message($type, $url, $str);
} ?>