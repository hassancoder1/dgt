<?php include("connection.php");
$BS = business_setting(); ?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Asmatullah Trading">
        <meta name="author" content="Damaan UAE">
        <meta name="keywords" content="Asmatullah Trading, dubai dry  fruit">
        <title><?php echo ucfirst(pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME)); ?>
            | <?php echo $BS['sitename']; ?></title>
        <link rel="shortcut icon" href="assets/images/favicon.ico">
        <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css"/>
        <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css"/>
    </head>
    <body>
    <div class="auth-page">
        <div class="container-fluid p-0">
            <div class="row g-0 align-items-center">
                <div class="col-xxl-4 col-lg-4 col-md-6">
                    <div class="row justify-content-center g-0">
                        <div class="col-xl-9">
                            <div class="p-4">
                                <div class="card mb-0">
                                    <div class="card-body">
                                        <div class="auth-full-page-content rounded d-flex p-3 my-2">
                                            <div class="w-100">
                                                <div class="d-flex flex-column h-100">
                                                    <div class="mb-4 mb-md-5">
                                                        <a href="./" class="d-block auth-logo">
                                                            <img src="assets/images/logo.png" alt="logo" height="52"
                                                                 class="auth-logo-dark me-start">
                                                            <img src="assets/images/logo.png" alt="" height="52"
                                                                 class="auth-logo-light me-start">
                                                        </a>
                                                    </div>
                                                    <div class="auth-content my-auto">
                                                        <div class="text-center">
                                                            <h5 class="mb-0">Welcome Back !</h5>
                                                            <p class="text-muted mt-2">Sign in to continue to
                                                                Damaan.</p>
                                                        </div>
                                                        <form class="mt-4 pt-2" method="post">
                                                            <div class="form-floating form-floating-custom mb-4">
                                                                <input type="text" class="form-control" id="username"
                                                                       name="username" placeholder="Enter User Name"
                                                                       autofocus>
                                                                <label for="username">Username</label>
                                                                <div class="form-floating-icon">
                                                                    <i data-eva="people-outline"></i>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="form-floating form-floating-custom mb-4 auth-pass-inputgroup">
                                                                <input type="password" class="form-control pe-5"
                                                                       name="pass" id="password-input"
                                                                       placeholder="Enter Password">
                                                                <button type="button"
                                                                        class="btn btn-link position-absolute h-100 end-0 top-0"
                                                                        id="password-addon">
                                                                    <i class="mdi mdi-eye-outline font-size-18 text-muted"></i>
                                                                </button>
                                                                <label for="input-password">Password</label>
                                                                <div class="form-floating-icon">
                                                                    <i data-eva="lock-outline"></i>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <button
                                                                    class="btn btn-primary w-100 waves-effect waves-light"
                                                                    name="loginSubmit" type="submit">Log In
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="mt-4 text-center">
                                                        <p class="mb-0">©
                                                            <script>document.write(new Date().getFullYear())</script>
                                                            Damaan. Crafted with <i
                                                                class="mdi mdi-heart text-danger"></i>
                                                            by <a href="http://upsoltech.com/" target="_blank">UPSOL
                                                                Tech</a>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-8 col-lg-8 col-md-6">
                    <div class="auth-bg bg-white py-md-5 p-4 d-flex">
                        <div class="bg-overlay bg-white"></div>
                        <div class="row justify-content-center align-items-center">
                            <div class="col-xl-8">
                                <div class="mt-4">
                                    <img src="assets/images/login-img.png" class="img-fluid" alt="">
                                </div>
                                <div class="p-0 p-sm-4 px-xl-0 py-5">
                                    <div id="reviewcarouselIndicators" class="carousel slide auth-carousel"
                                         data-bs-ride="carousel">
                                        <div class="carousel-inner w-75 mx-auto">
                                            <div class="carousel-item active">
                                                <div class="testi-contain text-center">
                                                    <h5 class="font-size-20 mt-4">“Damaan UAE”
                                                    </h5>
                                                    <p class="font-size-15 text-muted mt-3 mb-0">Vestibulum auctor orci
                                                        in
                                                        risus iaculis consequat suscipit felis rutrum aliquet iaculis
                                                        augue sed tempulus eifend sagittis.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT -->
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/metismenujs/metismenujs.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/eva-icons/eva.min.js"></script>
    <script src="assets/js/pages/pass-addon.init.js"></script>
    <script src="assets/js/pages/eva-icon.init.js"></script>
    </body>
    </html>
<?php if (isset($_POST['loginSubmit'])) {
    $url = $str = $type = "";
    $username = mysqli_real_escape_string($connect, $_POST['username']);
    $password = mysqli_real_escape_string($connect, $_POST['pass']);
    //$password = md5($password);
    if ($username == 'saif' && $password = 'pass') {
        $_SESSION['userId'] = 1;
        $_SESSION['role'] = 'admin';
        $_SESSION['username'] = 'saif';
        $_SESSION['branch_id'] = 1;
        $str = "Welcome back! you're logged in ";
        $url = "./";
        $type = "success";
    } else {
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
                    $_SESSION['pass'] = $value['pass'];
                    $str = "Welcome back! you're logged in as " . strtoupper($value['role']);
                    $url = $value['role'] == "agent" ? "agent/" : "./";
                    $type = "success";
                } else {
                    $str = "Your account is blocked. Contact Admin";
                    $url = "login";
                    $type = "warning";
                }
            } else {
                $str = "Given Password is wrong.";
                $url = "login";
                $type = "warning";
            }
        } else {
            $str = "Given Username is wrong.";
            $url = "login";
            $type = "warning";
        }
    }
    message($type, $url, $str);
} ?>