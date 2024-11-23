<ul class="nav navbar-nav_ navbar-right float-end nav-main">
    <li class="nav-item ">
        <a class="nav-link pe-1 py-0 mt-1" href="user-add?id=<?php echo $userId; ?>">
            <span class="me-1"><?php echo $userData['username']; ?></span>
            <?php if (!empty($userData['image'])) {
                echo '<img class="wd-35 ht-35 " src="' . $userData['image'] . '" alt="profile">';
            } else {
                echo '<img src="assets/images/others/logo-placeholder.png" alt="profile" class="wd-35 ht-35 ">';
            } ?>
        </a>
    </li>
    <li class="nav-item ">
        <a href="logout" class="nav-link px-0 py-1 h-100 btn btn-danger">لاگ آؤٹ <i class="icon-md flip-x"
                                                                                    data-feather="log-out"></i></a>
    </li>
</ul>
<ul class="nav me-auto nav-main ">
    <li class="nav-item">
        <a href="./" class="nav-link text-warning"><span></span><?php echo $BS['sitename']; ?></a>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"> روزنامچہ</a>
        <ul class="dropdown-menu dropdown-menu-right">
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
                    if (is_clearance_roznamcha_allowed(BORDER, $userId)) {
                        echo '<li><a class="dropdown-link" href="roznamcha-border">بارڈر روزنامچہ</a></li>';
                        echo '<li><hr class="dropdown-divider"></li>';
                    }
                    if (is_clearance_roznamcha_allowed(QANDHAR, $userId)) {
                        echo '<li><a class="dropdown-link" href="roznamcha-qandhar">قندھار روزنامچہ</a></li>';
                        echo '<li><hr class="dropdown-divider"></li>';
                    } ?>
                </ul>
            </li>
            <li>
                <hr class="dropdown-divider">
            </li>
        </ul>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">اپ ٹرانزٹ ریکارڈ</a>
        <ul class="dropdown-menu dropdown-menu-right">
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
                    if (is_clearance_roznamcha_allowed(QANDHAR, $userId, QANDHAR)) {
                        echo '<li><a class="dropdown-link" href="ut-qandhar-custom">قندھار کلئیرنس انٹری</a></li>';
                        echo '<li><hr class="dropdown-divider" ></li>';
                    }
                    if (is_clearance_roznamcha_allowed(BORDER, $userId, BORDER)) {
                        echo '<li><a class="dropdown-link" href="ut-afghan-border">افغان بارڈر کلئیرنس انٹری</a></li>';
                        echo '<li><hr class="dropdown-divider" ></li>';
                    } ?>
                </ul>
            </li>
            <li>
                <hr class="dropdown-divider">
            </li>
            <li>
                <a class="dropdown-item" href="#"> خرچہ بل چیکنگ &DoubleLeftArrow;</a>
                <ul class="submenu submenu-left dropdown-menu">
                    <?php if (is_clearance_roznamcha_allowed(KARACHI, $userId, KARACHI)) {
                        echo '<li><a class="dropdown-link" href="ut-expense-karachi-bill">کراچی خرچہ بل</a></li>';
                        echo '<li><hr class="dropdown-divider" ></li>';
                    }
                    if (is_clearance_roznamcha_allowed(CHAMAN, $userId, CHAMAN)) {
                        echo '<li><a class="dropdown-link" href="ut-expense-chaman-bill">چمن خرچہ بل</a></li>';
                        echo '<li><hr class="dropdown-divider" ></li>';
                    }
                    if (is_clearance_roznamcha_allowed(QANDHAR, $userId, QANDHAR)) {
                        echo '<li><a class="dropdown-link" href="ut-expense-qandhar-bill">قندھار خرچہ بل</a></li>';
                        echo '<li><hr class="dropdown-divider" ></li>';
                    }
                    if (is_clearance_roznamcha_allowed(BORDER, $userId, BORDER)) {
                        echo '<li><a class="dropdown-link" href="ut-expense-border-bill">بارڈر خرچہ بل</a></li>';
                        echo '<li><hr class="dropdown-divider" ></li>';
                    } ?>
                </ul>
            </li>
            <li>
                <hr class="dropdown-divider">
            </li>
            <!--<li><a class="dropdown-item" href="ut-expense-karachi-to-border">کراچی سے بارڈر خرچہ ٹرانسفر فارم </a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <a class="dropdown-item" href="#"> کراچی سے بارڈر خرچہ انٹری &DoubleLeftArrow;</a>
                <ul class="submenu submenu-left dropdown-menu">
                    <li><a class="dropdown-link" href="ut-expense-karachi-bill">کراچی خرچہ بل</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-link" href="">چمن خرچہ بل</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-link" href="">بارڈر خرچہ بل</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-link" href="">قندھار خرچہ بل</a></li>
                    <li><hr class="dropdown-divider"></li>
                </ul>
            </li>
            <li><hr class="dropdown-divider"></li>-->
        </ul>
    </li>

</ul>

