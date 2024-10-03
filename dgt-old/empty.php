<?php $page_title = 'Empty';
include("header.php"); ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div>
                    <?php echo searchInput('a', 'form-control-sm'); ?>
                </div>
                <div class="d-flex">
                    <?php echo addNew('#-add', '', 'btn-sm'); ?>
                </div>
            </div>
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php include("footer.php"); ?>