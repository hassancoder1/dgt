<?php include("header.php"); ?>
    <div class="page-content">
        <?php if (isset($_SESSION['response'])) {
            echo $_SESSION['response'];
            unset($_SESSION['response']);
        } ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="text-center"><h1 class="urdu mb-md-3">بسم اللہ اینڈ برادرز</h1></div>
            </div>
        </div>
        <div class="row g-3 justify-content-center">
            <?php if (Administrator()) {
                $list = array(
                    //array('text' => 'کھاتہ تفصیل', 'link' => 'ledger-form', 'count' => ''),
                    array('text' => 'یوزرز', 'link' => 'users', 'count' => getNumRows('users')),
                    array('text' => 'کھاتے', 'link' => 'khaata', 'count' => getNumRows('khaata')),
                    array('text' => 'روزنامچہ', 'link' => 'roznamcha', 'count' => getNumRows('roznamchaas')),
                    array('text' => 'خریداری فارم', 'link' => 'buys', 'count' => getNumRows('buys')),
                    array('text' => 'فروشی فارم', 'link' => 'sells', 'count' => getNumRows('buys_sold')),
                    /*array('text' => 'ایڈمن کاروبار روزنامچہ', 'link' => 'roznamcha-karobar-admin', 'count' => ''),
                    array('text' => 'ایڈمن بینک روزنامچہ', 'link' => 'roznamcha-bank-admin', 'count' => ''),
                    array('text' => 'ایڈمن چیک روزنامچہ', 'link' => 'roznamcha-bank-cheque-admin', 'count' => ''),
                    array('text' => 'ایڈمن بل روزنامچہ', 'link' => 'roznamcha-bill-admin', 'count' => ''),
                    array('text' => 'ایڈمن بل کرنسی روزنامچہ', 'link' => 'roznamcha-bill-currency-admin', 'count' => ''),
                    array('text' => 'ایڈمن جنرل روزنامچہ', 'link' => 'roznamcha-general-admin', 'count' => getNumRows('roznamchaas')),*/
                );
                foreach ($list as $item) { ?>
                    <div class="col-md-2 col-6 stretch-card">
                        <div class="card shadow ">
                            <a href="<?php echo $item['link']; ?>">
                                <div class="card-body py-3">
                                    <div
                                        class="<?php echo $item['count'] > 0 ? 'd-flex justify-content-between align-items-center' : ''; ?>">
                                        <h5 class="mb-0 text-dark urdu"><?php echo $item['text']; ?></h5>
                                        <?php echo $item['count'] > 0 ? '<h3 class="mb-0 text-success">' . $item['count'] . '</h3>' : '<h3 class="mb-0"></h3>'; ?>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php }
            } ?>
        </div>
    </div>
<?php include("footer.php"); ?>