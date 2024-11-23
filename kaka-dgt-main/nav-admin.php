<?php $li_dropdown_divider = '<li><hr class="dropdown-divider"></li>'; ?>
<ul class="nav navbar-nav_ navbar-right float-end nav-main d-none d-lg-inline-flex">
    <!--<li class="nav-item mt-2">
        <a href="chat" class="text-bg-warning px-3">بات چیت</a>
    </li>-->
    <li class="nav-item ">
        <a class="nav-link pe-1 py-0 mt-1" href="user-add?id=<?php echo $userId; ?>">
            <span class="me-1"><?php echo $userData['username']; ?></span>
            <?php if (!empty($userData['image'])) {
                echo '<img class="wd-35 ht-35 " src="' . $userData['image'] . '" alt="">';
            } else {
                echo '<img src="assets/images/others/logo-placeholder.png" alt="profile" class="wd-35 ht-35 ">';
            } ?>
        </a>
    </li>
    <li class="nav-item ">
        <a href="logout" class="nav-link px-2 rounded-0 btn btn-dark">لاگ آؤٹ <i class="icon-md flip-x"
                                                                                   data-feather="log-out"></i></a>
    </li>
</ul>
<ul class="nav me-auto nav-main d-none d-lg-flex flex-column flex-lg-row ">
    <li class="nav-item">
        <a href="./" class="nav-link fs-5"><span></span><?php echo $BS['sitename']; ?></a>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">اکاؤنٹ</a>
        <ul class="dropdown-menu dropdown-menu-right">
            <li><a class="dropdown-item" href="users">نیا یوزر</a></li>
            <?php echo $li_dropdown_divider; ?>
            <li><a class="dropdown-item" href="khaata">نیا کھاتہ</a></li>
            <?php echo $li_dropdown_divider; ?>
            <li><a class="dropdown-item" href="categories">نیا کیٹیگری</a></li>
            <?php echo $li_dropdown_divider; ?>
            <li><a class="dropdown-item" href="branches">نیا برانچ</a></li>

            <li class="d-none"><a class="dropdown-item" href="clearing-agents">کلئیرنگ ایجنٹ</a></li>
            <li class="d-none"><a class="dropdown-item" href="brokers">بروکرز</a></li>
            <li class="d-none"><a class="dropdown-item" href="staffs">ملازم</a></li>
            <li class="d-none"><a class="dropdown-item" href="banks">بینک</a></li>
            <li class="d-none"><a class="dropdown-item" href="truck-loadings">ٹرک لوڈنگ</a></li>
            <li class="d-none">
                <a class="dropdown-item" href="#"> امپورٹر / ایکسپورٹر &DoubleLeftArrow;</a>
                <ul class="submenu submenu-left dropdown-menu">
                    <li><a class="dropdown-link" href="importers">امپورٹر</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="exporters">ایکسپورٹر</a></li>
                </ul>
            </li>
            <li class="d-none">
                <a class="dropdown-item" href="#"> گودام&DoubleLeftArrow;</a>
                <ul class="submenu submenu-left dropdown-menu">
                    <li>
                        <a class="dropdown-link" href="godam-empty-forms">گودام خالی کرنے کاانٹری</a>
                    </li>
                    <?php echo $li_dropdown_divider; ?>
                    <li>
                        <a class="dropdown-link" href="godam-loading-forms">گودام لوڈنگ کرنے کاانٹری</a>
                    </li>
                </ul>
            </li>
            <li class="d-none">
                <a class="dropdown-item" href="#"> بھیجنے / وصول کرنے والا&DoubleLeftArrow;</a>
                <ul class="submenu submenu-left dropdown-menu">
                    <li><a class="dropdown-link" href="senders">مال بھیجنے والا</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="receivers">مال وصول کرنے والا</a></li>
                </ul>
            </li>

        </ul>
    </li>
    <?php if (Administrator()) { ?>
        <li class="nav-item dropdown d-none">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"> ایڈمن روزنامچہ</a>
            <ul class="dropdown-menu dropdown-menu-right">
                <li><a class="dropdown-item" href="roznamcha-karobar-admin">کاروبار روزنامچہ</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="roznamcha-bank-admin">بینک روزنامچہ</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="roznamcha-bill-admin">بل روزنامچہ</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="roznamcha-general-admin">جنرل روزنامچہ</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
            </ul>
        </li>
    <?php } ?>
    <li class="nav-item"><a class="nav-link" href="roznamcha"> روزنامچہ</a></li>
    <li class="nav-item dropdown d-none">
        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"> روزنامچہ</a>
        <ul class="dropdown-menu dropdown-menu-right">
            <li><a class="dropdown-item" href="roznamcha"> روزنامچہ</a></li>
            <?php echo $li_dropdown_divider; ?>
            <li><a class="dropdown-item" href="#"> روزنامچہ برانچ &DoubleLeftArrow;</a>
                <ul class="submenu submenu-left dropdown-menu">
                    <li><a class="dropdown-link" href="roznamcha-karobar">کاروبار روزنامچہ</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="roznamcha-bank">بینک روزنامچہ</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="roznamcha-bill">بل روزنامچہ</a></li>
                </ul>
            </li>
            <?php echo $li_dropdown_divider; ?>
            <li class="d-none"><a class="dropdown-item" href="#"> روزنامچہ کلئیرنس &DoubleLeftArrow;</a>
                <ul class="submenu submenu-left dropdown-menu">
                    <?php if (is_clearance_roznamcha_allowed(KARACHI, $userId)) {
                        echo '<li><a class="dropdown-link" href="roznamcha-karachi">کراچی روزنامچہ</a></li>';
                        echo '<li><hr class="dropdown-divider"></li>';
                    }
                    if (is_clearance_roznamcha_allowed(CHAMAN, $userId)) {
                        echo '<li><a class="dropdown-link" href="roznamcha-chaman">چمن روزنامچہ</a></li>';
                        echo '<li><hr class="dropdown-divider"></li>';
                    }
                    if (is_clearance_roznamcha_allowed(QANDHAR, $userId)) {
                        echo '<li><a class="dropdown-link" href="roznamcha-qandhar">قندھار روزنامچہ</a></li>';
                        echo '<li><hr class="dropdown-divider"></li>';
                    }
                    if (is_clearance_roznamcha_allowed(BORDER, $userId)) {
                        echo '<li><a class="dropdown-link" href="roznamcha-border">بارڈر روزنامچہ</a></li>';
                        echo '<li><hr class="dropdown-divider"></li>';
                    } ?>
                </ul>
            </li>
            <?php echo $li_dropdown_divider; ?>
            <li><a class="dropdown-item text-danger" href="roznamcha-general"> جنرل روزنامچہ</a></li>
            <?php echo $li_dropdown_divider; ?>
            <li>
                <a class="dropdown-item" href="#"> بل &DoubleLeftArrow;</a>
                <ul class="submenu submenu-left dropdown-menu">
                    <li><a class="dropdown-link" href="afghani-truck-kiraya">افغانی ٹرک کرایہ</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="import-kharcha">امپورٹ کسٹم خرچہ</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="dt-custom-kharcha">ڈاون ٹرانزٹ کسٹم خرچہ</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="office-exp">آفس خرچہ</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="home-exp">گھرکاخرچہ</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="munshi-exp">منشی خرچہ فارم</a></li>
                </ul>
            </li>
            <?php echo $li_dropdown_divider; ?>

        </ul>
    </li>

    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"> کھاتہ</a>
        <ul class="dropdown-menu dropdown-menu-right">
            <li>
                <a class="dropdown-item" href="ledger-form"> کھاتہ تفصیل &DoubleLeftArrow;</a>
                <!--<ul class="submenu submenu-left dropdown-menu">
                    <li><a class="dropdown-link" href="ledger-form">کھاتہ فارم</a></li>
                    <?php /*echo $li_dropdown_divider; */?>

                </ul>-->
            </li>
            <?php echo $li_dropdown_divider; ?>
            <li>
                <a class="dropdown-item" href="#"> ٹوٹل کیٹیگیری کھاتہ&DoubleLeftArrow;</a>
                <ul class="submenu submenu-left dropdown-menu">
                    <li><a class="dropdown-link" href="balance-all-categories">ٹوٹل کیٹیگیری بیلنس</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="ledger-all-categories">آل کیٹیگیری کھاتہ</a></li>
                    <?php echo $li_dropdown_divider; ?>
                </ul>
            </li>
            <?php echo $li_dropdown_divider; ?>

        </ul>
    </li>
    <li class="d-none nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">امپورٹ</a>
        <ul class="dropdown-menu dropdown-menu-right">
            <li>
                <a class="dropdown-item" href="#"> لوڈنگ انٹری &DoubleLeftArrow;</a>
                <ul class="submenu submenu-left dropdown-menu">
                    <li><a class="dropdown-link" href="imp-truck-loading">لوکل ٹرک لوڈنگ</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="imp-truck-loading-back-from-ut">ٹرانزٹ واپسی ٹرک لوڈنگ</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="import-maal-entry">مال انٹری فارم</a></li>
                    <?php echo $li_dropdown_divider; ?>

                    <li><a class="dropdown-link" href="imp-maal-transfer-form">مال انٹری فارم کو ٹرانسفر</a></li>
                    <?php //echo $li_dropdown_divider; ?>
                    <!--<li><a class="dropdown-link" href="#.">ٹرک ٹرانسفرفارم</a></li>
                    <?php /*echo $li_dropdown_divider; */ ?>
                    <li><a class="dropdown-link" href="#.">کسٹم ڈکلئیرنگ فارم</a></li>
                    --><?php /*echo $li_dropdown_divider; */ ?>
                </ul>
            </li>
            <?php echo $li_dropdown_divider; ?>
            <li>
                <a class="dropdown-item" href="#"> کرایہ سمری&DoubleLeftArrow;</a>
                <ul class="submenu submenu-left dropdown-menu">
                    <li><a class="dropdown-link" href="imp-kiraya-summary">کرایہ سمری</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <!--<li><a class="dropdown-link" href="#.">کرایہ چیکینگ</a></li>-->
                    <?php //echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="imp-kiraya-godam-received">گودام پہنچ انٹری</a></li>
                </ul>
            </li>
            <?php echo $li_dropdown_divider; ?>
            <li>
                <a class="dropdown-item" href="#"> جنرل سمری&DoubleLeftArrow;</a>
                <ul class="submenu submenu-left dropdown-menu">
                    <!--<li><a class="dropdown-link" href="#.">جنرل سمری</a></li>
                    <?php /*echo $li_dropdown_divider; */ ?>
                    <li><a class="dropdown-link" href="#.">اضافی خرچہ انٹری</a></li>
                    <?php /*echo $li_dropdown_divider; */ ?>
                    <li><a class="dropdown-link" href="#.">ایڈوانس خرچہ انٹری</a></li>-->
                </ul>
            </li>
            <?php echo $li_dropdown_divider; ?>
            <li>
                <a class="dropdown-item" href="#"> بیوپاری سمری&DoubleLeftArrow;</a>
                <ul class="submenu submenu-left dropdown-menu">
                    <li><a class="dropdown-link" href="imp-beopari-summary">بیوپاری سمری کلو</a></li>
                    <?php /*echo $li_dropdown_divider; */ ?><!--
                    <li><a class="dropdown-link" href="#.">سمری کمپنی خرچہ</a></li>
                    <?php /*echo $li_dropdown_divider; */ ?>
                    <li><a class="dropdown-link" href="#.">اضافی خرچہ</a></li>
                    <?php /*echo $li_dropdown_divider; */ ?>
                    <li><a class="dropdown-link" href="#.">بیوپاری ٹوٹل</a></li>
                    --><?php /*echo $li_dropdown_divider; */ ?>
                </ul>
            </li>
            <?php echo $li_dropdown_divider; ?>
            <li>
                <a class="dropdown-item" href="#"> کمپنی کمیشن&DoubleLeftArrow;</a>
                <ul class="submenu submenu-left dropdown-menu">
                    <li><a class="dropdown-link" href="imp-comp-beopari-comm">بیوپاری سمری کمیشن بل</a></li>
                    <?php /*echo $li_dropdown_divider; */ ?><!--
                    <li><a class="dropdown-link" href="#.">پرچون بل</a></li>-->
                </ul>
            </li>
            <?php echo $li_dropdown_divider; ?>
            <li>
                <a class="dropdown-item" href="#"> گودام مزدوری بل&DoubleLeftArrow;</a>
                <ul class="submenu submenu-left dropdown-menu">
                    <li><a class="dropdown-link" href="imp-godam-mazdoori-bill">گودام مزدوری بل</a></li>
                    <?php //echo $li_dropdown_divider; ?>
                </ul>
            </li>
            <?php echo $li_dropdown_divider; ?>
            <li><a class="dropdown-item" href="imp-packing">پیکنگ</a></li>
            <?php echo $li_dropdown_divider; ?>
            <li>
                <a class="dropdown-item" href="#"> بیوپاری گیٹ پاس انٹری&DoubleLeftArrow;</a>
                <ul class="submenu submenu-left dropdown-menu">
                    <li><a class="dropdown-link" href="imp-gate-pass-entry">نیا گیٹ پاس انٹری</a></li>
                    <?php //echo $li_dropdown_divider; ?>
                    <!--<li><a class="dropdown-link" href="#.">گیٹ پاس چیکنگ</a></li>-->
                </ul>
            </li>
        </ul>
    </li>
    <li class="d-none nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">ایکسپورٹ</a>
        <ul class="dropdown-menu dropdown-menu-right">
            <li>
                <a class="dropdown-item" href="#"> لوڈنگ انٹری &DoubleLeftArrow;</a>
                <ul class="submenu submenu-left dropdown-menu">
                    <li><a class="dropdown-link" href="#.">ٹرک لوڈنگ انٹری</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="#.">لوڈنگ چیک</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="#.">کسٹم سے کلئیرنگ فارم</a></li>
                    <?php echo $li_dropdown_divider; ?>
                </ul>
            </li>
            <?php echo $li_dropdown_divider; ?>
            <li>
                <a class="dropdown-item" href="#"> کرایہ انٹری&DoubleLeftArrow;</a>
                <ul class="submenu submenu-left dropdown-menu">
                    <li><a class="dropdown-link" href="#.">کرایہ فارم</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="#.">کرایہ چیکینگ فارم</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="#.">ٹرانسفر کرایہ فارم</a></li>
                    <?php echo $li_dropdown_divider; ?>
                </ul>
            </li>
            <?php echo $li_dropdown_divider; ?>
            <li>
                <a class="dropdown-item" href="#"> کمپنی بل انٹری&DoubleLeftArrow;</a>
                <ul class="submenu submenu-left dropdown-menu">
                    <li><a class="dropdown-link" href="#.">کمپنی بل انٹری فارم</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="#.">کلو فارم</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="#.">کمیشن فارم</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="#.">چیک فارم</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="#.">جنرل فارم</a></li>
                    <?php echo $li_dropdown_divider; ?>
                </ul>
            </li>
            <?php echo $li_dropdown_divider; ?>
        </ul>
    </li>
    <li class="d-none nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">اپ ٹرانزٹ</a>
        <ul class="dropdown-menu dropdown-menu-right">
            <li>
                <a class="dropdown-item" href="#"> لوڈنگ انٹری &DoubleLeftArrow;</a>
                <ul class="submenu submenu-left dropdown-menu">
                    <li><a class="dropdown-link" href="ut-bail-entries">بیل انٹری</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="ut-surrender-bails">سلنڈر بیل انٹری</a></li>
                    <?php echo $li_dropdown_divider; ?>
                </ul>
            </li>
            <?php echo $li_dropdown_divider; ?>
            <li>
                <a class="dropdown-item" href="#"> کلئیرنس انٹری &DoubleLeftArrow;</a>
                <ul class="submenu submenu-left dropdown-menu">
                    <?php if (is_clearance_roznamcha_allowed(KARACHI, $userId, KARACHI)) {
                        echo '<li><a class="dropdown-link" href="ut-import-custom-karachi"> امپورٹ کسٹم کلئیرنس کراچی </a></li>';
                        echo '<li><hr class="dropdown-divider" ></li>';
                    }
                    if (is_clearance_roznamcha_allowed(CHAMAN, $userId, CHAMAN)) {
                        echo '<li><a class="dropdown-link" href="ut-export-custom-chaman">ایکسپورٹ کسٹم کلئیرنس چمن </a></li>';
                        echo '<li><hr class="dropdown-divider" ></li>';
                    }
                    if (is_clearance_roznamcha_allowed(BORDER, $userId, BORDER)) {
                        echo '<li><a class="dropdown-link" href="ut-afghan-border">افغان بارڈر کلئیرنس انٹری</a></li>';
                        echo '<li><hr class="dropdown-divider" ></li>';
                    }
                    if (is_clearance_roznamcha_allowed(QANDHAR, $userId, QANDHAR)) {
                        echo '<li><a class="dropdown-link" href="ut-qandhar-custom">قندھار کلئیرنس انٹری</a></li>';
                        echo '<li><hr class="dropdown-divider" ></li>';
                    } ?>
                </ul>
            </li>
            <!--<li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="ut-expense-karachi-to-border">کراچی سے بارڈر خرچہ ٹرانسفر فارم </a></li>-->
            <?php echo $li_dropdown_divider; ?>
            <li><a class="dropdown-item" href="ut-godam-empty">خالی کرنے گودام </a></li>
            <?php echo $li_dropdown_divider; ?>
            <li><a class="dropdown-item" href="ut-commission-border-bill">کمیشن بارڈر بل </a></li>
            <?php echo $li_dropdown_divider; ?>
            <li>
                <a class="dropdown-item" href="#"> خرچہ بل چیکنگ &DoubleLeftArrow;</a>
                <ul class="submenu submenu-left dropdown-menu">
                    <li><a class="dropdown-link" href="ut-expense-karachi-bill">کراچی خرچہ بل</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="ut-expense-chaman-bill">چمن خرچہ بل</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="ut-expense-border-bill">بارڈر خرچہ بل</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="ut-expense-qandhar-bill">قندھار خرچہ بل</a></li>
                </ul>
            </li>
            <?php echo $li_dropdown_divider; ?>
        </ul>
    </li>
    <li class="d-none nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">ڈاؤن ٹرانزٹ</a>
        <ul class="dropdown-menu dropdown-menu-right">
            <li>
                <a class="dropdown-item" href="#"> لوڈنگ انٹری &DoubleLeftArrow;</a>
                <ul class="submenu submenu-left dropdown-menu">
                    <li><a class="dropdown-link" href="dt-truck-loading">ٹرک لوڈنگ</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="dt-docs-entry">ڈاکومنٹس انٹری</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="dt-maal-entry">مال انٹری فارم</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="dt-maal-transfer-form">مال انٹری فارم کو ٹرانسفر</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link text-muted" href="#.">ٹرک ٹرانسفرفارم</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-link text-muted" href="#.">کسٹم ڈکلئیرنگ فارم</a></li>
                </ul>
            </li>
            <?php echo $li_dropdown_divider; ?>
            <li>
                <a class="dropdown-item" href="#"> کرایہ سمری&DoubleLeftArrow;</a>
                <ul class="submenu submenu-left dropdown-menu">
                    <li><a class="dropdown-link" href="dt-kiraya-summary">کرایہ سمری</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link text-muted" href="#.">کرایہ چیکینگ</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="dt-kiraya-godam-received">گودام پہنچ انٹری</a></li>
                </ul>
            </li>
            <?php echo $li_dropdown_divider; ?>
            <li>
                <a class="dropdown-item" href="#"> بیوپاری سمری&DoubleLeftArrow;</a>
                <ul class="submenu submenu-left dropdown-menu">
                    <li><a class="dropdown-link" href="dt-beopari-summary">بیوپاری سمری</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link text-muted" href="#.">سمری کمپنی خرچہ</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link text-muted" href="#.">اضافی خرچہ</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link text-muted" href="#.">بیوپاری ٹوٹل</a></li>
                </ul>
            </li>
            <?php echo $li_dropdown_divider; ?>
            <li>
                <a class="dropdown-item" href="#"> کمپنی کمیشن&DoubleLeftArrow;</a>
                <ul class="submenu submenu-left dropdown-menu">
                    <li><a class="dropdown-link" href="dt-comp-beopari-comm">بیوپاری کمیشن بل</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link text-muted" href="#.">پرچون بل</a></li>
                </ul>
            </li>
            <?php echo $li_dropdown_divider; ?>
            <li>
                <a class="dropdown-item" href="#"> گودام مزدوری بل&DoubleLeftArrow;</a>
                <ul class="submenu submenu-left dropdown-menu">
                    <li><a class="dropdown-link" href="dt-godam-mazdoori-bill">گودام مزدوری بل</a></li>
                    <?php //echo $li_dropdown_divider; ?>
                    <!--<li><a class="dropdown-link" href="#.">بعد میں کرنا ہے</a></li>-->
                </ul>
            </li>
            <?php echo $li_dropdown_divider; ?>
            <li>
                <a class="dropdown-item disabled" href="#"> بیوپاری گیٹ پاس انٹری&DoubleLeftArrow;</a>
                <ul class="submenu submenu-left dropdown-menu">
                    <li><a class="dropdown-link text-muted" href="#.">نیا گیٹ پاس انٹری</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link text-muted" href="#.">گیٹ پاس چیکنگ</a></li>
                </ul>
            </li>
        </ul>
    </li>
    <li class="d-none nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">مکمل سمری</a>
        <ul class="dropdown-menu dropdown-menu-right">
            <li>
                <a class="dropdown-item" href="#"> امپورٹ &DoubleLeftArrow;</a>
                <ul class="submenu submenu-left dropdown-menu">
                    <li><a class="dropdown-link" href="import-maal-entry-final">مال انٹری فارم مکمل</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="imp-maal-transfer-form-final">مال انٹری فارم ٹرانسفر مکمل</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="imp-kiraya-summary-final">کرایہ سمری مکمل</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="imp-godam-mazdoori-bill-final">گودام مزدوری بل مکمل</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="imp-beopari-summary-final">بیوپاری سمری مکمل</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="imp-kiraya-godam-received-final">گودام پہنچ مکمل</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="imp-gate-pass-entry-final">گیٹ پاس انٹری مکمل</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link text-muted" disabled href="#">کسٹم انٹری مکمل</a></li>
                    <?php echo $li_dropdown_divider; ?>
                </ul>
            </li>
            <?php echo $li_dropdown_divider; ?>
            <li>
                <a class="dropdown-item" href="#"> ڈاؤن ٹرانزٹ &DoubleLeftArrow;</a>
                <ul class="submenu submenu-left dropdown-menu">
                    <li><a class="dropdown-link" href="dt-maal-entry-final">مال انٹری فارم  مکمل</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="dt-beopari-summary-final">بیوپاری سمری مکمل</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="dt-kiraya-summary-final">بیوپاری کرایہ سمری مکمل</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="dt-godam-mazdoori-bill-final">گودام مزدوری بل مکمل</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="dt-kiraya-godam-received-final">گودام پہنچ مکمل</a></li>
                    <?php echo $li_dropdown_divider; ?>

                </ul>
            </li>
        </ul>
    </li>


    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">خریدوفروخت</a>
        <ul class="dropdown-menu dropdown-menu-right">
            <li>
                <a class="dropdown-item" href="#"> خریداری &DoubleLeftArrow;</a>
                <ul class="submenu submenu-left dropdown-menu">
                    <li><a class="dropdown-link" href="buys">خریداری فارم تفصیل</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="buys-extra-exp">اضافی خرچہ</a></li>
                </ul>
            </li>
            <li>
                <a class="dropdown-item" href="#"> فروشی &DoubleLeftArrow;</a>
                <ul class="submenu submenu-left dropdown-menu">
                    <li><a class="dropdown-link" href="sells">فروشی فارم تفصیل</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="sells-broker-commission">بروکر کمیشن فارم</a></li>
                </ul>
            </li>
        </ul>
    </li>
</ul>
<ul class="nav page-navigation d-lg-none d-block">
    <!--<li class="nav-item">
        <a class="nav-link" href="#">
            <span class="menu-title">اکاؤنٹ</span>
            <i class="link-arrow"></i>
        </a>
        <div class="submenu">
            <ul class="submenu-item">
                <li class="nav-item"><a class="nav-link" href="users">نیا یوزر</a></li>
                <li class="nav-item"><a class="nav-link" href="khaata">نیا کھاتہ</a></li>
                <li class="nav-item"><a class="nav-link" href="categories">نیا کیٹیگری</a></li>
                <li class="nav-item"><a class="nav-link" href="branches">نیا برانچ</a></li>
                <li class="nav-item"><a class="nav-link" href="clearing-agents">کلئیرنگ ایجنٹ</a></li>
                <li class="nav-item"><a class="nav-link" href="brokers">بروکرز</a></li>
                <li class="nav-item"><a class="nav-link" href="staffs">ملازم</a></li>
                <li class="nav-item"><a class="nav-link" href="banks">بینک</a></li>
                <li class="nav-item"><a class="nav-link" href="truck-loadings">ٹرک لوڈنگ</a></li>
                <li class="nav-item">
                    <a class="nav-link" href="#"> امپورٹر / ایکسپورٹر &DoubleLeftArrow;</a>
                    <ul class="submenu submenu-item">
                        <li class="nav-item"><a class="nav-link" href="importers">امپورٹر</a></li>

                        <li class="nav-item"><a class="nav-link" href="exporters">ایکسپورٹر</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"> گودام&DoubleLeftArrow;</a>
                    <ul class="submenu submenu-item">
                        <li class="nav-item"><a class="nav-link" href="godam-empty-forms">گودام خالی کرنے کاانٹری</a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="godam-loading-forms">گودام لوڈنگ کرنے کاانٹری</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"> بھیجنے / وصول کرنے والا&DoubleLeftArrow;</a>
                    <ul class="submenu submenu-item">
                        <li class="nav-item"><a class="nav-link" href="senders">مال بھیجنے والا</a></li>
                        <li class="nav-item"><a class="nav-link" href="receivers">مال وصول کرنے والا</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </li>-->
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">اکاؤنٹ</a>
        <ul class="dropdown-menu dropdown-menu-right">
            <li><a class="dropdown-item" href="users">نیا یوزر</a></li>
            <?php echo $li_dropdown_divider; ?>
            <li><a class="dropdown-item" href="khaata">نیا کھاتہ</a></li>
            <?php echo $li_dropdown_divider; ?>
            <li><a class="dropdown-item" href="categories">نیا کیٹیگری</a></li>
            <?php echo $li_dropdown_divider; ?>
            <li><a class="dropdown-item" href="branches">نیا برانچ</a></li>
            <?php echo $li_dropdown_divider; ?>
        </ul>
    </li>
    <?php if (Administrator()) { ?>
        <li class="nav-item dropdown d-none">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"> ایڈمن روزنامچہ</a>
            <ul class="dropdown-menu dropdown-menu-right">
                <li><a class="dropdown-item" href="roznamcha-karobar-admin">کاروبار روزنامچہ</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="roznamcha-bank-admin">بینک روزنامچہ</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="roznamcha-bill-admin">بل روزنامچہ</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="roznamcha-general-admin">جنرل روزنامچہ</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
            </ul>
        </li>
    <?php } ?>
    <li class="nav-item"><a class="nav-link" href="roznamcha"> روزنامچہ</a></li>
    <li class="nav-item dropdown d-none">
        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"> روزنامچہ</a>
        <ul class="dropdown-menu dropdown-menu-right">
            <li><a class="dropdown-item" href="#"> روزنامچہ برانچ &DoubleLeftArrow;</a>
                <ul class="submenu submenu-left dropdown-menu">
                    <li><a class="dropdown-link" href="roznamcha-karobar">کاروبار روزنامچہ</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="roznamcha-bank">بینک روزنامچہ</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="roznamcha-bill">بل روزنامچہ</a></li>
                </ul>
            </li>
            <?php echo $li_dropdown_divider; ?>
            <li><a class="dropdown-item" href="#"> روزنامچہ کلئیرنس &DoubleLeftArrow;</a>
                <ul class="submenu submenu-left dropdown-menu">
                    <?php if (is_clearance_roznamcha_allowed(KARACHI, $userId)) {
                        echo '<li><a class="dropdown-link" href="roznamcha-karachi">کراچی روزنامچہ</a></li>';
                        echo '<li><hr class="dropdown-divider"></li>';
                    }
                    if (is_clearance_roznamcha_allowed(CHAMAN, $userId)) {
                        echo '<li><a class="dropdown-link" href="roznamcha-chaman">چمن روزنامچہ</a></li>';
                        echo '<li><hr class="dropdown-divider"></li>';
                    }
                    if (is_clearance_roznamcha_allowed(QANDHAR, $userId)) {
                        echo '<li><a class="dropdown-link" href="roznamcha-qandhar">قندھار روزنامچہ</a></li>';
                        echo '<li><hr class="dropdown-divider"></li>';
                    }
                    if (is_clearance_roznamcha_allowed(BORDER, $userId)) {
                        echo '<li><a class="dropdown-link" href="roznamcha-border">بارڈر روزنامچہ</a></li>';
                        echo '<li><hr class="dropdown-divider"></li>';
                    } ?>
                </ul>
            </li>
            <?php echo $li_dropdown_divider; ?>
            <li><a class="dropdown-item text-danger" href="roznamcha-general"> جنرل روزنامچہ</a></li>
            <?php echo $li_dropdown_divider; ?>
            <li>
                <a class="dropdown-item" href="#"> بل &DoubleLeftArrow;</a>
                <ul class="submenu submenu-left dropdown-menu">
                    <li><a class="dropdown-link" href="afghani-truck-kiraya">افغانی ٹرک کرایہ</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="import-kharcha">امپورٹ کسٹم خرچہ</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="dt-custom-kharcha">ڈاون ٹرانزٹ کسٹم خرچہ</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="office-exp">آفس خرچہ</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="home-exp">گھرکاخرچہ</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="munshi-exp">منشی خرچہ فارم</a></li>
                </ul>
            </li>
            <?php echo $li_dropdown_divider; ?>

        </ul>
    </li>

    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">خریدوفروخت</a>
        <ul class="dropdown-menu dropdown-menu-right">
            <li>
                <a class="dropdown-item" href="#"> خریداری &DoubleLeftArrow;</a>
                <ul class="submenu submenu-left dropdown-menu">
                    <li><a class="dropdown-link" href="buys">خریداری فارم تفصیل</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="buys-extra-exp">اضافی خرچہ</a></li>
                </ul>
            </li>
            <li>
                <a class="dropdown-item" href="#"> فروشی &DoubleLeftArrow;</a>
                <ul class="submenu submenu-left dropdown-menu">
                    <li><a class="dropdown-link" href="sells">فروشی فارم تفصیل</a></li>
                    <?php echo $li_dropdown_divider; ?>
                    <li><a class="dropdown-link" href="sells-broker-commission">بروکر کمیشن فارم</a></li>
                </ul>
            </li>
        </ul>
    </li>
</ul>

