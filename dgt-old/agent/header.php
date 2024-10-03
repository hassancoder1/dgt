<?php include("../connection.php");
$myKhaataNo = $khaataId = '';
if (isset($_SESSION['userId']) && $_SESSION['userId'] > 0 && isset($_SESSION['branch_id']) && $_SESSION['branch_id'] >= 0 && isset($_SESSION['username']) && isset($_SESSION['role'])) {
    $userId = $_SESSION['userId'];
    $branchId = $_SESSION['branch_id'];
    $branchName = branchName($branchId);
    $userName = $_SESSION['username'];
    $userData = getUser($userId);
    $khaataId = $userData['khaata_id'];
    $agentKhaata = khaataSingle($khaataId);
    if (!empty($agentKhaata)){
        $myKhaataNo = $agentKhaata['khaata_no'];
    }
} else {
    echo '<script>window.location.href="../login";</script>';
}
$BS = business_setting();
include("../variables.php");
$page_title = $page_title ?? 'AGENT Dashboard';
$back_page_url = $back_page_url ?? './'; ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title><?php echo $page_title; ?> - AGENT DGT</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Al Ras Deira Dubai office UAE dubai" name="description"/>
    <meta content="DGT L.L.C" name="author"/>
    <link rel="shortcut icon" href="../assets/images/favicon.jpg">
    <link href="../assets/libs/choices.js/public/assets/styles/choices.min.css" rel="stylesheet" type="text/css"/>
    <link href="../assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css"/>
    <link href="../assets/css/icons.min.css" rel="stylesheet" type="text/css"/>
    <link href="../assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css"/>

    <!--others by Saif-->
    <link href="../assets/css/virtual-select.min.css" rel="stylesheet" type="text/css"/>
    <link href="../assets/tooltip/tooltip.min.css" rel="stylesheet" type="text/css"/>
    <link href="../assets/css/custom.css" rel="stylesheet" type="text/css"/>
    <link href="../assets/libs/dropify/dist/dropify.min.css" rel="stylesheet" type="text/css"/>
    <style>
        .fix-head-table {
            position: relative;
        }

        .fix-head-table thead {
            position: sticky !important;
            top: 0;
            background-color: #FFFFFF;
            z-index: 999;
        }

        .fix-head-table tbody {
            margin-top: 2px;
        }
        .table > :not(caption) > * > * {
            border-color: #c5c5c5;
        }

        /*modal: fixed right sidebar*/
        .fixed-sidebar {
            position: fixed;
            top: 58px;
            right: 0;
            height: 100%;
            /*width: 220px;*/
            background-color: #fff; /* Set the background color */
            /*border-left: 1px solid #ccc; !* Optional: Add a border for separation *!*/
            overflow-y: auto; /* Allow vertical scrolling if the content is longer */
            padding: 20px 10px;

        }

        .bottom-buttons {
            position: absolute;
            bottom: 80px; /* Adjust the distance from the bottom as needed */
            right: 0;
            width: 100%;
        }

        .content-column {
            padding: 10px 5px;
            /*margin-right: 2200px;*/
        }
    </style>
</head>
<body data-sidebar="dark">
<!-- <body data-layout="horizontal"> -->
<div id="layout-wrapper">
    <header id="page-topbar" class="isvertical-topbar">
        <div class="navbar-header">
            <div class="d-flex">
                <div class="navbar-brand-box">
                    <a href="./" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="../assets/images/logo.png" alt="" height="22">
                                </span>
                        <span class="logo-lg">
                                    <img src="../assets/images/logo.png" alt="" height="22">
                                </span>
                    </a>
                    <a href="./" class="logo logo-light">
                                <span class="logo-lg">
                                    <img src="../assets/images/logo.png" alt="" height="22">
                                </span>
                        <span class="logo-sm">
                                    <img src="../assets/images/logo.png" alt="" height="22">
                                </span>
                    </a>
                </div>
                <button type="button"
                        class="btn btn-sm px-3 font-size-16 header-item vertical-menu-btn topnav-hamburger">
                    <div class="hamburger-icon open">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </button>
                <div class="d-none d-sm-block ms-3 align-self-center">
                    <div class="d-flex align-items-center gap-1">
                        <a href="<?php echo $back_page_url; ?>"><i class="icon-sm" data-eva="close"></i></a>
                        <span class="mx-3">|</span>
                        <h4 class="page-title"><?php echo $page_title; ?></h4>
                        <span
                            class="fs-6 badge badge-soft-primary text-uppercase"><?php echo $userData['role']; ?></span>
                        <span
                            class="fs-6 badge badge-soft-dark text-uppercase">A/C <?php echo $myKhaataNo . '-' . $khaataId; ?></span>
                    </div>
                </div>
            </div>
            <div class="d-flex">
                <div class="dropdown">
                    <button type="button" class="btn header-item"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="icon-sm" data-eva="search-outline"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-md p-0">
                        <form class="p-2">
                            <div class="search-box">
                                <div class="position-relative">
                                    <input type="text" class="form-control bg-light border-0" placeholder="Search...">
                                    <i class="search-icon" data-eva="search-outline" data-eva-height="26"
                                       data-eva-width="26"></i>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="dropdown d-inline-block">
                    <button type="button" class="btn header-item noti-icon" id="page-header-notifications-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="icon-sm" data-eva="bell-outline"></i>
                        <span class="noti-dot bg-danger rounded-pill">4</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                         aria-labelledby="page-header-notifications-dropdown">
                        <div class="p-3">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h5 class="m-0 font-size-15"> Notifications </h5>
                                </div>
                                <div class="col-auto">
                                    <a href="#!" class="small fw-semibold text-decoration-underline"> Mark all as
                                        read</a>
                                </div>
                            </div>
                        </div>
                        <div data-simplebar style="max-height: 250px;">
                            <a href="#!" class="text-reset notification-item">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <img src="../assets/images/users/avatar-3.jpg" class="rounded-circle avatar-sm"
                                             alt="user-pic">
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">James Lemire</h6>
                                        <div class="font-size-13 text-muted">
                                            <p class="mb-1">It will seem like simplified English.</p>
                                            <p class="mb-0"><i class="mdi mdi-clock-outline"></i>
                                                <span>1 hour ago</span></p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <a href="#!" class="text-reset notification-item">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 avatar-sm me-3">
                                                <span class="avatar-title bg-primary rounded-circle font-size-16">
                                                    <i class="bx bx-cart"></i>
                                                </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">Your order is placed</h6>
                                        <div class="font-size-13 text-muted">
                                            <p class="mb-1">If several languages coalesce the grammar</p>
                                            <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span>3 min ago</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <a href="#!" class="text-reset notification-item">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 avatar-sm me-3">
                                                <span class="avatar-title bg-success rounded-circle font-size-16">
                                                    <i class="bx bx-badge-check"></i>
                                                </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">Your item is shipped</h6>
                                        <div class="font-size-13 text-muted">
                                            <p class="mb-1">If several languages coalesce the grammar</p>
                                            <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span>3 min ago</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </a>

                            <a href="#!" class="text-reset notification-item">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <img src="../assets/images/users/avatar-6.jpg" class="rounded-circle avatar-sm"
                                             alt="user-pic">
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">Salena Layfield</h6>
                                        <div class="font-size-13 text-muted">
                                            <p class="mb-1">As a skeptical Cambridge friend of mine occidental.</p>
                                            <p class="mb-0"><i class="mdi mdi-clock-outline"></i>
                                                <span>1 hour ago</span></p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="p-2 border-top d-grid">
                            <a class="btn btn-sm btn-link font-size-14 btn-block text-center" href="javascript:void(0)">
                                <i class="uil-arrow-circle-right me-1"></i> <span>View More..</span>
                            </a>
                        </div>
                    </div>
                </div>
                <!--<div class="dropdown d-inline-block">
                    <button type="button" class="btn header-item noti-icon right-bar-toggle" id="right-bar-toggle">
                        <i class="icon-sm" data-eva="settings-outline"></i>
                    </button>
                </div>-->

                <div class="dropdown d-inline-block">
                    <button type="button" class="btn header-item user text-start d-flex align-items-center"
                            id="page-header-user-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php if (!empty($userData['image'])) {
                            $img_src = $userData['image'];
                        } else {
                            $img_src = 'assets/images/avatar.jpg';
                        }
                        echo '<img src="' . $img_src . '" class="rounded-circle header-profile-user" alt="Header Avatar">'; ?>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end pt-0">
                        <div class="p-3 border-bottom">
                            <h6 class="mb-0"><?php echo $userData['username']; ?></h6>
                            <p class="mb-0 font-size-11 text-muted"><?php echo $userData['email']; ?></p>
                        </div>
                        <a class="dropdown-item" href="user-add?id=<?php echo $userId; ?>">
                            <i class="mdi mdi-account-circle text-muted font-size-16 align-middle me-1"></i>
                            <span class="align-middle">Profile</span>
                        </a>
                        <a class="dropdown-item" href="../logout">
                            <i class="mdi mdi-logout text-muted font-size-16 align-middle me-1"></i>
                            <span class="align-middle">Logout</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- ========== Left Sidebar Start ========== -->
    <div class="vertical-menu">
        <div class="navbar-brand-box">
            <a href="./" class="logo logo-dark">
                <span class="logo-sm"><img src="../assets/images/logo.png" alt="" height="22"></span>
                <span class="logo-lg"><img src="../assets/images/logo.png" alt="" height="22"></span>
            </a>
            <a href="./" class="logo logo-light">
                <span class="logo-lg"><img src="../assets/images/logo.png" alt="" height="22"></span>
                <span class="logo-sm"><img src="../assets/images/logo.png" alt="" height="22"></span>
            </a>
        </div>
        <button type="button" class="btn btn-sm px-3 header-item vertical-menu-btn topnav-hamburger">
            <div class="hamburger-icon">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </button>
        <div data-simplebar class="sidebar-menu-scroll">
            <!--- Sidemenu -->
            <div id="sidebar-menu">
                <ul class="metismenu list-unstyled" id="side-menu">
                    <li>
                        <a href="./">
                            <i class="icon nav-icon" data-eva="grid-outline"></i>
                            <span class="menu-item" data-key="t-dashboards">Dashboards</span>
                        </a>
                    </li>
                    <li>
                        <a href="my-forms">
                            <i class="icon nav-icon" data-eva="grid-outline"></i>
                            <span class="menu-item" data-key="t-dashboards">My Forms</span>
                        </a>
                    </li>
                    <li>
                        <a href="my-bills">
                            <i class="icon nav-icon" data-eva="grid-outline"></i>
                            <span class="menu-item" data-key="t-dashboards">My Bills</span>
                        </a>
                    </li>
                    <li>
                        <a href="my-ledger">
                            <i class="icon nav-icon" data-eva="grid-outline"></i>
                            <span class="menu-item" data-key="t-dashboards">My Ledger</span>
                        </a>
                    </li>
                    <li class="d-none">
                        <a href="javascript: void(0);" class="has-arrow">
                            <i class="icon nav-icon" data-eva="shopping-bag-outline"></i>
                            <span class="menu-item">Entries</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="">My Forms</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="p-3 px-4 sidebar-footer">
                <p class="mb-1 main-title">
                    <script>document.write(new Date().getFullYear())</script> &copy; DGT L.L.C
                </p>
                <p class="mb-0">Design & Develop by UPSOL Technologies</p>
            </div>
        </div>
    </div>
    <!-- Left Sidebar End -->
    <header id="page-topbar" class="ishorizontal-topbar">
        <div class="navbar-header"></div>
        <div class="topnav"></div>
    </header>

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">