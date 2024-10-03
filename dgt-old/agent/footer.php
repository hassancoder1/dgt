</div>
</div>
<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <script>document.write(new Date().getFullYear())</script> &copy; DGT L.L.C
            </div>
        </div>
    </div>
</footer>
</div>
</div>

<div class="rightbar-overlay"></div>

<!--<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasActivity" aria-labelledby="offcanvasActivityLabel">
    <div class="offcanvas-header border-bottom">
        <h5 id="offcanvasActivityLabel">Offcanvas right</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        ...
    </div>
</div>-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- JAVASCRIPT -->
<script src="../assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/libs/metismenujs/metismenujs.min.js"></script>
<script src="../assets/libs/simplebar/simplebar.min.js"></script>
<script src="../assets/libs/eva-icons/eva.min.js"></script>

<script src="../assets/libs/choices.js/public/assets/scripts/choices.min.js"></script>
<script src="../assets/js/pages/form-advanced.init.js"></script>
<script src="../assets/js/app.js"></script>
<script src="../assets/libs/dropify/dist/dropify.min.js"></script>
<script>
    $('.dropify').dropify();
</script>
<script src="../assets/js/virtual-select.min.js"></script>
<script type="text/javascript">
    VirtualSelect.init({
        ele: '.v-select',
        optionsCount: 7,
        autoSelectFirstOption: false,
        placeholder: 'Choose',
        hideSelectedOptions:false,
        multiple: true,
        dropboxWidth: '300px',
        showSelectedOptionsFirst: false
    });
    VirtualSelect.init({
        ele: '.v-select-sm',
        placeholder: 'Choose',
        // showValueAsTags: true,
        optionHeight: '30px',
        showSelectedOptionsFirst: true,
        // allowNewOption: true,
        // hasOptionDescription: true,
        search: true
    });
</script>
<script>
    $("#tableFilter,.inputFilter").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        $("table tbody tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
    $('body').on('keyup keypress', function (e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode == 113 || keyCode == 'ControlLeft') {
            $('#tableFilter').focus();
            $('.inputFilter').focus();
        }
    });
    function enableButton(button_id) {
        $("#" + button_id).prop('disabled', false);
    }

    function disableButton(button_id) {
        $("#" + button_id).prop('disabled', true);
    }
</script>
<script>
    function smoothScrollToBottom(duration) {
        $("html, body").animate(
            {
                scrollTop: $(document).height()
            },
            duration
        );
    }
</script>
<script>jQuery(document).ready(function($) {
        $(".clickable-row").click(function() {
            window.location = $(this).data("href");
        });
    });</script>
<script>
    (function ($) {
        $.fn.inputFilter = function (callback, errMsg) {
            return this.on("input keydown keyup mousedown mouseup select contextmenu drop focusout", function (e) {
                if (callback(this.value)) {
                    // Accepted value
                    if (["keydown", "mousedown", "focusout"].indexOf(e.type) >= 0) {
                        $(this).removeClass("input-error");
                        this.setCustomValidity("");
                    }
                    this.oldValue = this.value;
                    this.oldSelectionStart = this.selectionStart;
                    this.oldSelectionEnd = this.selectionEnd;
                } else if (this.hasOwnProperty("oldValue")) {
                    // Rejected value - restore the previous one
                    $(this).addClass("input-error");
                    this.setCustomValidity(errMsg);
                    this.reportValidity();
                    this.value = this.oldValue;
                    this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
                } else {
                    // Rejected value - nothing to restore
                    this.value = "";
                }
            });
        };
    }(jQuery));

    $(".currency").inputFilter(function (value) {
        return /^-?\d*[.,]?\d{0,9}$/.test(value);
    }, "Currency value only");
    $(".numberOnly").inputFilter(function (value) {
        return /^\d*$/.test(value);
    }, "Numbers only");
</script>
</body>
</html>
