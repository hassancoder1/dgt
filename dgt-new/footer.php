</div>
</div>

<script src="assets/js/jquery-3.7.1.min.js"></script>
<script src="assets/bs/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/virtual-select.min.js"></script>
<script type="text/javascript">
    VirtualSelect.init({
        ele: '.v-select',
        optionsCount: 7,
        autoSelectFirstOption: false,
        placeholder: 'Choose',
        hideSelectedOptions: false,
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
<script>
    jQuery(document).ready(function ($) {
        $(".clickable-row").click(function () {
            window.location = $(this).data("href");
        });
    });</script>
<script>
    const toastLiveExample = document.getElementById('liveToast')

    if (toastLiveExample) {
        const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample)
        toastBootstrap.show();
    }
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
</script>
<script>
    /*$(document).ready(function(){
        $('.dropdown-submenu a.test').on("click", function(e){
            $(this).next('ul').toggle();
            e.stopPropagation();
            e.preventDefault();
        });
    });*/
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.dropdown-submenu .dropdown-toggle').forEach(function (element) {
            element.addEventListener('click', function (e) {
                var nextEl = this.nextElementSibling;
                if (nextEl && nextEl.classList.contains('dropdown-menu')) {
                    e.preventDefault();
                    if (nextEl.style.display == 'block') {
                        nextEl.style.display = 'none';
                    } else {
                        nextEl.style.display = 'block';
                    }
                }
            });
        });
    });


</script>
<script>
    (function ($) {
        $.fn.inputFilter = function (callback, errMsg) {
            return this.on("input keydown keyup mousedown mouseup select contextmenu drop focusout", function (e) {
                if (callback(this.value)) {
                    // Accepted value
                    if (["keydown", "mousedown", "focusout"].indexOf(e.type) >= 0) {
                        $(this).removeClass("is-invalid");
                        this.setCustomValidity("");
                    }
                    this.oldValue = this.value;
                    this.oldSelectionStart = this.selectionStart;
                    this.oldSelectionEnd = this.selectionEnd;
                } else if (this.hasOwnProperty("oldValue")) {
                    // Rejected value - restore the previous one
                    $(this).addClass("is-invalid");
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
    }, "INVALID");
    $(".numberOnly").inputFilter(function (value) {
        return /^\d*$/.test(value);
    }, "INVALID");
</script>
</body>
</html>