<?php //ssh -p 65002 u152432976@185.212.70.153
include("connection.php");
include("variables.php");

$disabledAttr = Administrator() ? "" : "disabled";
$BS = busines_setting();
if (isset($_SESSION['userId']) && isset($_SESSION['branch_id']) && isset($_SESSION['username']) && isset($_SESSION['role'])) {
    $userId = $_SESSION['userId'];
    $branchId = $_SESSION['branch_id'];
    $branchName = getTableDataByIdAndColName('branches', $branchId, 'b_name');
    $userName = $_SESSION['username'];  
    $userData = getUser($userId);
    //var_dump($userData);

    if ($_SESSION['role'] == 'admin') {

    } else {
        $pageName = str_ireplace(array('.php'), array(''), basename($_SERVER['PHP_SELF']));
        $permission = !empty($userData['permission'])? json_decode($userData['permission']) :array('');
        //var_dump($permission);
        $ex_pages = array('index');
        if (in_array($pageName, $permission) || in_array($pageName, $ex_pages)) {/*||$pageName == 'index'*/
        } else {
            echo '<script>window.location.href="./";</script>';
        }
    }
} else {
    echo '<script>window.location.href="login";</script>';
} ?>
<!DOCTYPE html>
<html lang="" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Damaan Impex">
    <meta name="author" content="Asmatullah">
    <meta name="keywords" content="Asmatullah Trading, dubai dry fruit">
    <title><?php echo ucfirst(pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME)); ?>
        | <?php echo $BS['sitename']; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <!--<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&amp;display=swap"
          rel="stylesheet">-->
    <!--    <link href="https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu&display=swap" rel="stylesheet">-->
    <link rel="stylesheet" href="assets/vendors/core/core.css">

    <link rel="stylesheet" href="assets/vendors/select2/select2.min.css">
    <link rel="stylesheet" href="assets/vendors/jquery-tags-input/jquery.tagsinput.min.css">
    <link rel="stylesheet" href="assets/vendors/dropzone/dropzone.min.css">
    <link rel="stylesheet" href="assets/vendors/dropify/dist/dropify.min.css">
    <link rel="stylesheet" href="assets/vendors/pickr/themes/classic.min.css">
    <link rel="stylesheet" href="assets/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/vendors/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" href="assets/fonts/feather-font/css/iconfont.css">
    <link rel="stylesheet" href="assets/vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/css/style-rtl.min.css">
    <link rel="stylesheet" href="assets/css/custom.css">
    <link rel="shortcut icon" href="assets/images/anitco.png"/>
    <link rel="stylesheet" href="assets/css/virtual-select.min.css">
    <link rel="stylesheet" href="assets/tooltip/tooltip.min.css">
    <style type="text/css">
        @media all and (min-width: 992px) {
            .dropdown-menu li {
                position: relative;
            }

            .dropdown-menu .submenu {
                display: none;
                position: absolute;
                left: 100%;
                top: -7px;
            }

            .dropdown-menu .submenu-left {
                right: 100%;
                left: auto;
            }

            .dropdown-menu > li:hover {
                background-color: #f1f1f1
            }

            .dropdown-menu > li:hover > .submenu {
                display: block;
            }
        }
        @media (min-width: 992px) {
            .dropdown-menu .submenu.show {
                display: block;
            }
        }
        @media (max-width: 991px) {
            li.dropdown .dropdown-menu {
                position: relative !important;
                transform: translateY(0px) !important;
            }
            .dropdown-menu .dropdown-menu {
                margin-left: 0.7rem;
                margin-right: 0.7rem;
                margin-bottom: .5rem;
                background-color: #eee;
                position: relative !important;
            }
        }
        .nav-main li.dropdown > ul.dropdown-menu > li > a:after {
            display: none;
        }
    </style>
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function () {
            var screenWidth = jQuery(window).width();
            if(screenWidth < 992) {
                $('.nav-main li.dropdown > ul.dropdown-menu > li > a').addClass('dropdown-toggle').attr('data-bs-toggle', 'dropdown')
            }
            document.querySelectorAll('.dropdown-menu').forEach(function (element) {
                element.addEventListener('click', function (e) {
                    e.stopPropagation();
                });
            });
            if (window.innerWidth < 992) {
                document.querySelectorAll('.nav .dropdown').forEach(function (everydropdown) {
                    everydropdown.addEventListener('hidden.bs.dropdown', function () {
                        this.querySelectorAll('.submenu').forEach(function (everysubmenu) {
                            everysubmenu.style.display = 'none';
                        });
                    })
                });

                document.querySelectorAll('.dropdown-menu a').forEach(function (element) {
                    element.addEventListener('click', function (e) {

                        let nextEl = this.nextElementSibling;
                        if (nextEl && nextEl.classList.contains('submenu')) {
                            // prevent opening link if link needs to open dropdown
                            e.preventDefault();
                            console.log(nextEl);
                            if (nextEl.style.display == 'block') {
                                nextEl.style.display = 'none';
                            } else {
                                nextEl.style.display = 'block';
                            }

                        }
                    });
                })
            }
        });
    </script>
</head>
<body>
<div class="main-wrapper">
    <?php /*echo '<div class="text-center mt-5 pt-5"><h2 class="text-center display-1 urdu mb-5">تکلیف کے لیے معذرت خواہ ہیں </h2>';
    echo '<h2 class="urdu">مرمتی کام کی وجہ سے سافٹ وئیر کچھ دیر کے لیے بند ہے </h2></div>';
    die(); */?>
    <div class="horizontal-menu">
        <!--<nav class="navbar top-navbar">
            <div class="container">
                <div class="navbar-content">
                    <a href="index.php" class="navbar-brand"><span></span><?php /*echo $BS['sitename']; */?></a>
                    <form class="search-form">
                        <div class="input-group">
                            <div class="input-group-text">
                                <i data-feather="search"></i>
                            </div>
                            <input type="text" class="form-control" id="navbarForm" placeholder="Search here...">
                        </div>
                    </form>
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button"
                               data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i data-feather="bell"></i>
                                <div class="indicator">
                                    <div class="circle"></div>
                                </div>
                            </a>
                            <div class="dropdown-menu p-0" aria-labelledby="notificationDropdown">
                                <div class="px-3 py-2 d-flex align-items-center justify-content-between border-bottom">
                                    <p>6 New Notifications</p>
                                    <a href="javascript:;" class="text-muted">Clear all</a>
                                </div>
                                <div class="p-1">
                                    <a href="javascript:;" class="dropdown-item d-flex align-items-center py-2">
                                        <div class="wd-30 ht-30 d-flex align-items-center justify-content-center bg-primary rounded-circle me-3">
                                            <i class="icon-sm text-white" data-feather="gift"></i>
                                        </div>
                                        <div class="flex-grow-1 me-2">
                                            <p>New Order Recieved</p>
                                            <p class="tx-12 text-muted">30 min ago</p>
                                        </div>
                                    </a>
                                </div>
                                <div class="px-3 py-2 d-flex align-items-center justify-content-center border-top">
                                    <a href="javascript:;">View all</a>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link " href="profile.php">
                                <?php /*if (!empty($userData['image'])) {
                                    echo '<img class="wd-30 ht-30 rounded-circle" src="' . $userData['image'] . '" alt="profile">';
                                } else {
                                    echo '<img src="assets/images/others/logo-placeholder.png" alt="profile" class="wd-30 ht-30 rounded-circle">';
                                } */?>
                            </a>
                            <div class="dropdown-menu p-0" aria-labelledby="profileDropdown">
                                <div class="d-flex flex-column align-items-center border-bottom px-5 py-3">
                                    <div class="mb-3">
                                        <?php /*if (!empty($userData['image'])) {
                                            echo '<img class="wd-80 ht-80 rounded-circle" src="' . $userData['image'] . '" alt="profile">';
                                        } else {
                                            echo '<img src="assets/images/others/logo-placeholder.png" alt="profile" class="wd-80 ht-80 rounded-circle">';
                                        } */?>
                                    </div>
                                    <div class="text-center">
                                        <p class="tx-16 fw-bolder"><?php /*echo $userData['username']; */?></p>
                                        <p class="tx-12 text-muted"><?php /*echo $userData['role'];  */?></p>
                                    </div>
                                </div>
                                <ul class="list-unstyled p-1">
                                    <li class="dropdown-item py-2">
                                        <a href="profile.php" class="text-body ms-0">
                                            <i class="me-2 icon-md" data-feather="user"></i>
                                            <span>پروفائل</span>
                                        </a>
                                    </li>
                                    <li class="dropdown-item py-2">
                                        <a href="logout.php" class="text-body ms-0">
                                            <i class="me-2 icon-md" data-feather="log-out"></i>
                                            <span>لاگ آؤٹ</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                            data-toggle="horizontal-menu-toggle">
                        <i data-feather="menu"></i>
                    </button>
                </div>
            </div>
        </nav>-->
        <div class="d-lg-none d-flex align-items-center justify-content-between" style="background: #c37c2e">
            <ul class="nav navbar-nav_ navbar-right me-auto nav-main">
                <li class="nav-item ">
                    <a href="logout" class="nav-link px-2 rounded-0 btn btn-dark">لاگ آؤٹ
                        </a>
                </li>
                <!--<li class="nav-item mt-lg-2">
                    <a href="chat" class="nav-link px-3 rounded-0 btn btn-warning">بات چیت</a>
                </li>-->
                <li class="nav-item ">
                    <a class="nav-link pe-1 py-0 mt-1" href="user-add?id=<?php echo $userId; ?>">
                        <span class="me-1 text-dark"><?php echo $userData['username']; ?></span>
                        <?php if (!empty($userData['image']) && file_exists($userData['image'])) {
                            echo '<img class="wd-35 ht-35 rounded" src="' . $userData['image'] . '" alt="">';
                        } else {
                            echo '<img src="assets/images/others/logo-placeholder.png" alt="profile" class="wd-35 ht-35 rounded-pill">';
                        } ?>
                    </a>
                </li>
            </ul>
            <a href="./">
                <img src="assets/images/favicon.jpg" alt="logo" class="wd-45 me-3">
            </a>
            <button class="navbar-toggler navbar-toggler-right align-self-center p-2" type="button"
                    data-toggle="horizontal-menu-toggle">
                <i data-feather="menu"></i>
            </button>
        </div>
        <nav class="bottom-navbar fixed-on-scroll" style="background: #c37c2e">
            <div class="container">
                <?php if (ClearingAgent()) {
                    include("nav-clearing-agent.php");
                } else {
                    include("nav-admin.php");
                } ?>
            </div>
        </nav>
    </div>
    <div class="page-wrapper">
        <div class="page-content">