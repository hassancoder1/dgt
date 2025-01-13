<?php //ssh -p 65002 u152432976@185.212.70.153
include("connection.php");
$myKhaataNo = $khaataId = '';
if (
    isset($_SESSION['userId']) && $_SESSION['userId'] > 0
    && isset($_SESSION['branch_id']) && $_SESSION['branch_id'] >= 0
    && isset($_SESSION['username']) && isset($_SESSION['role']) && isset($_SESSION['pass'])
) {
    $userId = $_SESSION['userId'];
    $ddd = getUser($userId);
    $pass = $_SESSION['pass'];
    if ($pass != '' && $pass == $ddd['pass']) {
        $branchId = $_SESSION['branch_id'];
        $branchName = branchName($branchId);
        $userName = $_SESSION['username'];
        $userData = getUser($userId);
        /*$khaataId = $userData['khaata_id'];
        $agentKhaata = khaataSingle($khaataId);
        if (!empty($agentKhaata)) {
            $myKhaataNo = $agentKhaata['khaata_no'];
        }*/
        if ($_SESSION['role'] == 'superadmin') {
            echo '';
        } else {
            // if ($_SESSION['role'] == 'agent') {
            //     if(basename($_SERVER['REQUEST_URI']) === 'agent-form' || basename($_SERVER['REQUEST_URI']) === 'agent-payments-form'){}else{
            //     message('danger', 'agent-form', 'Hi.');
            //     }
            // } else {
            $pageName = str_ireplace(array('.php'), array(''), basename($_SERVER['PHP_SELF']));
            $permission = !empty($userData['permission']) ? json_decode($userData['permission']) : array();
            $ex_pages = array('index');
            /*if ($_SESSION['role'] == 'agent') {
                    //$ex_pages[] = 'purchase-transit-agent';
                    $ex_pages = array_merge($ex_pages, array('purchase-transit-agent', 'purchase-import-agent', 'purchase-warehouse-agent', 'purchase-loading-add'));
                } else {
                    $ex_pages = array_merge($ex_pages, array('khaata-add'));
                }*/
            //var_dump($permission);
            //echo navbarCol(1,'url');
            if (in_array($pageName, $permission) || in_array($pageName, $ex_pages)) {
            } else {
                message('danger', './', 'Permission denied.');
                //echo '<script>window.location.href="./";</script>';
            }
        }
    } else {
        echo '<script>window.location.href="login";</script>';
    }
} else {
    echo '<script>window.location.href="login";</script>';
}
$BS = business_setting();
$page_title = $page_title ?? 'Dashboard';
$back_page_url = $back_page_url ?? './'; ?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $page_title . ' - DGT.llc'; ?> </title>
    <meta name="description" content="Owner of DGT.llc">
    <meta name="author" content="Asmatullah Abdullah">
    <meta name="keywords" content="dgt, uae, damaan general trading, damaan">
    <link href="assets/bs/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/custom.css" rel="stylesheet">
    <link href="assets/css/virtual-select.min.css" rel="stylesheet">
    <!-- <link href="assets/fa/css/fontawesome.css" rel="stylesheet"/> -->
    <!-- <link href="assets/fa/css/brands.css" rel="stylesheet"/> -->
    <!-- <link href="assets/fa/css/solid.css" rel="stylesheet"/> -->
    <script src="assets/fa/fontawesome.js" crossorigin="anonymous"></script>
    <link rel="shortcut icon" href="assets/images/favicon.jpg" />
    <link href="assets/fonts/lexend.css" rel="stylesheet">
    <style>
        * {
            font-family: "Lexend", serif;
        }

        .sidebar-menu-scroll {
            margin-top: calc(50px + 26px);
        }

        .fix-head-table {
            position: relative;
        }

        .fix-head-table thead {
            position: sticky !important;
            top: -1px;
            background-color: #FFFFFF;
            z-index: 999;
        }

        .fix-head-table tbody {
            margin-top: 2px;
        }

        /*.table > :not(caption) > * > * {
            border-color: #c5c5c5;
        }*/

        .vscomp-toggle-button {
            padding: 3px;
            border-radius: 0;
        }

        .vscomp-search-container {
            height: 30px;
        }

        /*modal: fixed right sidebar*/
        .fixed-sidebar {
            position: fixed;
            /*top: 58px;*/
            right: 0;
            height: 100%;
            /*width: 220px;*/
            background-color: #fff;
            /* Set the background color */
            /*border-left: 1px solid #ccc; !* Optional: Add a border for separation *!*/
            overflow-y: auto;
            /* Allow vertical scrolling if the content is longer */
            padding: 0 10px;
            border-left: 1px solid #dee2e6;
        }

        .bottom-buttons {
            position: absolute;
            bottom: 80px;
            /* Adjust the distance from the bottom as needed */
            right: 0;
            width: 100%;
        }

        .content-column {
            padding: 10px 5px;
            /*margin-right: 2200px;*/
        }
    </style>
    <style>
        .socials {
            list-style: none;
            display: flex;
            /*align-items: center;*/
            /*justify-content: space-between;*/
            gap: .5rem;
            padding-left: 0;
            margin-bottom: 0;

        }

        .socials li {}

        .socials a:hover {
            background: #222;
            color: #ffffff;
        }

        .socials a {
            font-size: 15px;
            display: inline-block;
            background: transparent;
            border: 1px solid #a09cbe;
            color: #222;
            line-height: 1;
            padding: 4px 0;
            border-radius: 5%;
            text-align: center;
            width: 25px;
            height: 25px;
            transition: 0.3s;
        }
    </style>
    <style>
        /* Submenu positioning */
        .dropdown-menu .dropdown-submenu {
            position: relative;
        }

        .dropdown-menu .dropdown-submenu .dropdown-menu {
            top: 0;
            left: 100%;
            margin-top: -1px;
        }

        /* Optional: Hover effect for submenu */
        .dropdown-menu .dropdown-submenu:hover>.dropdown-menu {
            display: block;
        }

        /* Optional: Ensure parent link is clickable */
        .dropdown-submenu>.dropdown-item::after {
            /*content: ">";
            float: right;
            margin-right: 10px;*/
        }
    </style>
</head>
<?php echo $_SESSION['response'] ?? '';
unset($_SESSION['response']); ?>

<body class="bg-light">
    <div class="container-fluid px-0 overflow-hidden -pb-4 special-emails" style="margin-top: 2rem">
        <div class="page-wrapper">