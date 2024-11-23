<a class="btn btn-success pt-1 btn-sm position-absolute" style="top: -30px; right: 44%; z-index: 9; margin: auto"
   data-bs-toggle="collapse" href="#collapseExample" role="button"
   aria-expanded="false" aria-controls="collapseExample"
   data-tooltip="سلنڈر بیل تفصیل کو بند کریں یا اوپن کریں"
   data-tooltip-position="bottom">
    <i class="fa fa-plus-square"></i> &nbsp;
    <i class="fa fa-minus-circle"></i>
    سلنڈر بیل تفصیل
</a>
<div class="collapse" id="collapseExample">
    <div class="card">
        <h3 class="urdu-2 bg-primary bg-opacity-50 text-white text-center">بیل انٹری کی تفصیل</h3>
        <div class="px-1 pt-0 pb-2">
            <?php include("ut-bail-entry-inc.php"); ?>
        </div>
    </div>
    <div class="card mt-1">
        <h3 class="urdu-2 bg-primary bg-opacity-50 text-white text-center">سلنڈر بیل انٹری کی تفصیل</h3>
        <div class="px-1 pt-0 pb-2">
            <?php include("ut-bail-surrender-inc.php"); ?>
        </div>
    </div>
</div>