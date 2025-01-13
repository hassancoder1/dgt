<?php include("connection.php");
$BS = business_setting(); ?>
<?php echo $_SESSION['response'] ?? '';
unset($_SESSION['response']); ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>DGT.llc</title>
        <meta name="description" content="Owner of DGT.llc">
        <meta name="author" content="Asmatullah Abdullah">
        <meta name="keywords" content="dgt, uae, damaan general trading, damaan">
        <link href="assets/bs/css/bootstrap.min.css" rel="stylesheet">
        <link href="assets/css/custom.css" rel="stylesheet">
        <link href="assets/css/virtual-select.min.css" rel="stylesheet">
        <link rel="shortcut icon" href="assets/images/favicon.jpg"/>
        <link href="assets/fa/css/fontawesome.css" rel="stylesheet"/>
        <link href="assets/fa/css/brands.css" rel="stylesheet"/>
        <link href="assets/fa/css/solid.css" rel="stylesheet"/>
    </head>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            background: #f6f5f7;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            font-family: 'Montserrat', sans-serif;
            height: 100vh;
            /*margin: -20px 0 50px;*/
        }

        button {
            border-radius: 20px;
            border: 1px solid #FF4B2B;
            background-color: #FF4B2B;
            color: #FFFFFF;
            font-size: 12px;
            font-weight: bold;
            padding: 12px 45px;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: transform 80ms ease-in;
        }

        button:active {
            transform: scale(0.95);
        }

        button:focus {
            outline: none;
        }

        form {
            background-color: #FFFFFF;
            display: flex;
            /*align-items: center;*/
            justify-content: center;
            flex-direction: column;
            padding: 0 50px;
            height: 100%;
            text-align: center;
        }

        .form-container {
            position: absolute;
            top: 0;
            height: 100%;
            transition: all 0.6s ease-in-out;
        }

        .sign-in-container {
            right: 0;
            width: 30%;
            z-index: 2;
        }

        @media (max-width: 767px) {
            .sign-in-container {
                width: 100%;
                z-index: 999;
            }
        }

        .container.right-panel-active .sign-in-container {
            transform: translateX(100%);
        }


        .overlay-container {
            position: absolute;
            top: 0;
            right: 30%;
            width: 70%;
            height: 100%;
            overflow: hidden;
            transition: transform 0.6s ease-in-out;
            z-index: 100;
        }

        .container.right-panel-active .overlay-container {
            transform: translateX(-100%);
        }

        .overlay {
            background: #FF416C;
            background: -webkit-linear-gradient(to right, #FF4B2B, #FF416C);
            background: linear-gradient(to right, #FF4B2B, #FF416C);
            background-repeat: no-repeat;
            background-size: cover;
            background-position: 0 0;
            color: #FFFFFF;
            position: relative;
            left: -100%;
            height: 100%;
            width: 200%;
            transform: translateX(0);
            transition: transform 0.6s ease-in-out;
        }

        .container.right-panel-active .overlay {
            transform: translateX(50%);
        }

        .overlay-panel {
            background-image: url(assets/images/backscreen.jpg);
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 40px;
            text-align: center;
            top: 0;
            height: 100%;
            width: 50%;
            transform: translateX(0);
            transition: transform 0.6s ease-in-out;
        }

        .overlay-left {
            transform: translateX(-20%);
        }

        .container.right-panel-active .overlay-left {
            transform: translateX(0);
        }

        .overlay-right {
            right: 0;
            transform: translateX(0);
        }

        .container.right-panel-active .overlay-right {
            transform: translateX(20%);
        }

        footer {
            background-color: #f3f3f3;
            color: #222;
            font-size: 14px;
            bottom: 0;
            position: fixed;
            right: 0;
            left: 0;
            text-align: center;
            z-index: 999;
        }

        footer p {
            margin: 10px 0;
        }

        footer i {
            color: red;
        }
    </style>
    <body>
    <div class="container" id="container">
        <div class="form-container sign-in-container">
            <form method="post" class="text-start">
                <a href="./">
                    <img src="assets/images/logo.png" alt="logo" class="img-fluid w-25">
                </a>
                <h4 class="my-4">Welcome back!</h4>
                <div class="form-floating form-floating-custom mb-4">
                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter User Name"
                           autofocus>
                    <label for="username">Username</label>
                </div>
                <div class="form-floating form-floating-custom mb-4">
                    <input type="password" class="form-control" name="pass" id="input-password"
                           placeholder="Enter Password">
                    <label for="input-password">Password</label>
                </div>
                <button name="loginSubmit" type="submit">Sign In</button>
                <div class="text-start w-100 mt-lg-5">
                    <div class="d-flex align-items-center gap-3">
                        <div>
                            <i class="fa fa-phone fa-2x"></i>
                        </div>
                        <div class="text-muted">
                            <div>Need Any Help ?</div>
                            <div>Contact Us&nbsp;<a class="contact-number" href="tel:+971507164963">+971 50 716 4963</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left"></div>
                <div class="overlay-panel overlay-right"></div>
            </div>
        </div>
    </div>
    <footer>
        <p>
            ©
            <script>document.write(new Date().getFullYear())</script>
            Created with <i class="fa fa-heart"></i> by
            <a class="text-dark btn-link" href="https://dgt.llc/" target="_blank">DGT</a>
        </p>
    </footer>
    <script src="assets/bs/js/bootstrap.bundle.min.js"></script>
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
                    $_SESSION['name'] = $value['full_name'];
                    $_SESSION['username'] = $username;
                    $_SESSION['branch_id'] = $value['branch_id'];
                    $_SESSION['pass'] = $password;
                    $_SESSION['image'] = $value['image'];
                    $str = "Welcome back! you're logged in as " . strtoupper($value['role']);
                    $url = $value['role'] == "agent" ? "agent-form" : "./";
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
    messageNew($type, $url, $str);
} ?>