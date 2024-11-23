<?php $backUrl = '../khaata';
if (isset($_GET['k_id']) && !(empty($_GET['k_id'])) && isset($_GET['secret'])) {
    if (base64_decode($_GET['secret']) == "powered-by-upsol") {
        require("../connection.php");
        $id = mysqli_real_escape_string($connect, base64_decode($_GET['k_id']));
        $records = fetch('khaata', array('id' => $id));
        $khaata = mysqli_fetch_assoc($records); ?>
        <!DOCTYPE html>
        <html lang="ar" dir="rtl">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <meta name="description" content="Damaan Impex">
            <meta name="author" content="Asmatullah">
            <meta name="keywords" content="Asmatullah Trading, dubai dry fruit">
            <title><?php echo $khaata['khaata_name']; ?>_<?php echo $khaata['khaata_no']; ?></title>
            <link rel="preconnect" href="https://fonts.googleapis.com/">
            <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
            <link rel="stylesheet" href="../assets/css/style-rtl.min.css">
            <link rel="stylesheet" href="../assets/css/custom.css">
            <link rel="shortcut icon" href="../assets/images/anitco.png"/>
            <link rel="stylesheet" href="../assets/vendors/font-awesome/css/font-awesome.min.css">
            <link rel="stylesheet" href="../assets/tooltip/tooltip.min.css">
            <style>
                input {
                    pointer-events: none;
                    font-weight: bold !important;
                    font-family: 'Noto Naskh Arabic', serif;
                }
                p .small, p span {
                    font-size: 9px !important;
                }
            </style>
        </head>
        <body>
        <div class="container-fluid">
            <div class="p-1">
                <?php include("inc-print-top.php"); ?>
            </div>
            <div class="card rounded-0 shadow-none border-0">
                <div class="card-body">
                    <div class="row gx-0">
                        <div class="col">
                            <div class="row">
                                <div class="col-12">
                                    <p class="urdu small mb-5">
                                        <span class="bold">کھاتہ نمبر:  </span>
                                        <span class="small"><?php echo $khaata['khaata_no']; ?></span>
                                        <span class="bold ms-3 me-1 me-1">کیٹیگیری:  </span>
                                        <span class="small"> <?php echo getTableDataByIdAndColName('cats',$khaata['cat_id'],'c_name'); ?></span>
                                        <span class="bold ms-3 me-1">برانچ:  </span>
                                        <span class="small"> <?php echo getTableDataByIdAndColName('branches',$khaata['branch_id'],'b_name'); ?></span>
                                        <span class="bold ms-3 me-1">تاریخ:  </span>
                                        <span class="small"> <?php echo $khaata['khaata_date']; ?></span>
                                        <span class="bold ms-3 me-1">کھاتہ نام:  </span>
                                        <span class="small"> <?php echo $khaata['khaata_name']; ?></span>
                                    </p>
                                </div>
                                <div class="col-12">
                                    <p class="urdu small mb-5">
                                        <span class="bold">کمپنی نام:  </span>
                                        <span class="small"><?php echo $khaata['comp_name']; ?></span>
                                        <span class="bold ms-3 me-1">کاروبارنام:  </span>
                                        <span class="small"> <?php echo $khaata['business_name']; ?></span>
                                        <span class="bold ms-3 me-1">شہر نام:  </span>
                                        <span class="small"> <?php echo $khaata['city']; ?></span>
                                        <span class="bold ms-3 me-1">کاروبار پتہ:  </span>
                                        <span class="small"> <?php echo $khaata['address']; ?></span>
                                    </p>
                                </div>
                                <div class="col-12">
                                    <p class="urdu small mb-5">
                                        <span class="bold">موبائل نمبر:  </span>
                                        <span class="small" dir="ltr"><?php echo $khaata['mobile']; ?></span>
                                        <span class="bold ms-3 me-1">واٹس ایپ نمبر:  </span>
                                        <span class="small" dir="ltr"> <?php echo $khaata['whatsapp']; ?></span>
                                        <span class="bold ms-3 me-1">فون نمبر:  </span>
                                        <span class="small" dir="ltr"> <?php echo $khaata['phone']; ?></span>
                                        <span class="bold ms-3 me-1">ای میل:  </span>
                                        <span class="small" dir="ltr"> <?php echo $khaata['email']; ?></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <img src="../<?php echo $khaata['image']; ?>" alt="img" class="img-fluid rounded">
                        </div>
                        <div class="col-12">
                            <p class="urdu small">
                                <span class="bold">شناختی کارڈ نمبر:  </span>
                                <span class="small" ><?php echo $khaata['cnic']; ?></span>
                                <span class="bold ms-3 me-1">شناختی کارڈ نام:  </span>
                                <span class="small" > <?php echo $khaata['cnic_name']; ?></span>
                                <span class="bold ms-3 me-1">والد کا نام:  </span>
                                <span class="small"> <?php echo $khaata['father_name']; ?></span>
                                <span class="bold ms-3 me-1">گھر کا پتہ:  </span>
                                <span class="small" > <?php echo $khaata['home_address']; ?></span>
                                <span class="bold ms-3 me-1">تفصیل:  </span>
                                <span class="small"><?php echo $khaata['details']; ?></span>
                            </p>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col">
                            <p class="urdu small">
                                <span class="bold">منشی دستخط:  </span>
                                <span class="small">__________________________</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="../assets/tooltip/tooltip.min.js"></script>
        <div class="sticky-social d-print-none">
            <ul class="social">
                <li class="bg-dark" data-tooltip="<?php $khaata['khaata_name']; ?>" data-tooltip-position="right">
                    <a href="../khaata-add?id=<?php echo $id; ?>"><i
                                class="fa fa-long-arrow-left"></i></a>
                </li>
                <li class="facebook" data-tooltip="PDF پرنٹ کریں" data-tooltip-position="right">
                    <a class="cursor-pointer" onclick="window.print();">
                        <i class="fa fa-print"></i>
                    </a>
                </li>
            </ul>
        </div>
        </body>
        </html>
        <?php
        if (isset($_GET['print'])) {
            echo '<script>window.print();</script>';
        }
    } else {
        echo '<script>window.location.href="' . $backUrl . '";</script>';
    }
} else {
    echo '<script>window.location.href="../";</script>';
} ?>