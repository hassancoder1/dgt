<style>
    .dropdown-submenu {
        position: relative;
    }

    .dropdown-submenu .dropdown-menu {
        top: 0;
        left: 100%;
        margin-top: -1px;
    }
</style>
<nav class="navbar fixed-top- py-0 navbar-expand-lg bg-white border-bottom shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand" href="./">DGT</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php
                $result = mysqli_query($connect, "SELECT * FROM navbar WHERE parent_id=0 ORDER BY position");
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $row_id = $row['id'];
                        $dd = mysqli_query($connect, "SELECT * FROM `navbar` WHERE parent_id='$row_id'");
                        $hasDropdown = mysqli_num_rows($dd) > 0;
                        $dropdownClass = $hasDropdown ? 'dropdown' : '';
                        $dropdownToggleClass = $hasDropdown ? 'dropdown-toggle' : '';
                        $dropdownAttributes = $hasDropdown ? 'role="button" data-bs-toggle="dropdown" aria-expanded="false"' : '';

                        echo '<li class="nav-item ' . $dropdownClass . '">';
                        echo '<a class="nav-link poppins-semibold ' . $dropdownToggleClass . '" id="dgt' . $row['id'] . '" href="' . ($hasDropdown ? '#' : $row['url']) . '" ' . $dropdownAttributes . '>';
                        echo $row['label'];
                        echo '</a>';
                        if ($hasDropdown) {
                            generateSubMenu($row['id'], $connect);
                        }
                        echo '</li>';
                    }
                }
                ?>
            </ul>
            <div class="d-flex align-items-center gap-2">
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-warning text-uppercase dropdown-toggle"
                            data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                        <?php echo $_SESSION['role']; ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-lg-end">
                        <li><a href="navbar" class="dropdown-item">Navbar</a></li>
                        <li><a href="logout" class="dropdown-item">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>



