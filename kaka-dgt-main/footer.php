</div>
<!--<footer class="footer border-top">
    <div class="container text-center pt-2 pb-1 small">
        <p class="text-muted mb-1 mb-md-0">Proudly powered by <?php /*echo date('Y'); */?> <a href="https://upsoltech.com/"
                                                                                          target="_blank">UPSOL TECH</a>.
        </p>
    </div>
</footer>-->
</div>
</div>

<div class="bottom-button d-print-none">
    <span class="badge2 badge bg-dark" ><?php echo $branchName; ?></span>
    <span class="badge1" style="font-size: 1rem"><?php echo userRole($userData['role']); ?></span>
</div>
<!-- core:js -->
<script src="assets/vendors/core/core.js"></script>
<!-- endinject -->

<!-- Plugin js for this page -->
<script src="assets/vendors/jquery-validation/jquery.validate.min.js"></script>
<script src="assets/vendors/bootstrap-maxlength/bootstrap-maxlength.min.js"></script>
<script src="assets/vendors/inputmask/jquery.inputmask.min.js"></script>
<script src="assets/vendors/select2/select2.min.js"></script>
<script src="assets/vendors/typeahead.js/typeahead.bundle.min.js"></script>
<script src="assets/vendors/jquery-tags-input/jquery.tagsinput.min.js"></script>
<script src="assets/vendors/dropzone/dropzone.min.js"></script>
<script src="assets/vendors/dropify/dist/dropify.min.js"></script>
<script src="assets/vendors/pickr/pickr.min.js"></script>
<script src="assets/vendors/moment/moment.min.js"></script>
<script src="assets/vendors/flatpickr/flatpickr.min.js"></script>
<!-- End plugin js for this page -->
<!-- Plugin js for this page -->
<!--<script src="assets/vendors/apexcharts/apexcharts.min.js"></script>-->
<!-- End plugin js for this page -->
<script src="assets/tooltip/tooltip.min.js"></script>
<script src="assets/js/virtual-select.min.js"></script>
<script type="text/javascript">
    VirtualSelect.init({
        ele: '.virtual-select',
        placeholder: 'انتخاب کریں',
        searchPlaceholderText: 'تلاش کریں',
        search: true,
        //optionsCount: 5,
        //required: false,
        noOptionsText: 'ڈیٹابیس میں رزلٹ نہیں',
        noSearchResultsTex: 'کوئی رزلٹ نہیں'
    });

</script>
<!-- inject:js -->
<script src="assets/vendors/feather-icons/feather.min.js"></script>
<script src="assets/js/template.js"></script>
<!-- endinject -->

<!-- Custom js for this page -->
<script src="assets/js/dashboard-light.js"></script>
<!-- End custom js for this page -->
<!-- Custom js for this page -->
<!--<script src="assets/js/form-validation.js"></script>-->
<script src="assets/js/bootstrap-maxlength.js"></script>
<script src="assets/js/inputmask.js"></script>
<script src="assets/js/select2.js"></script>
<script src="assets/js/typeahead.js"></script>
<script src="assets/js/tags-input.js"></script>
<script src="assets/js/dropzone.js"></script>
<script src="assets/js/dropify.js"></script>
<script src="assets/js/pickr.js"></script>
<script src="assets/js/flatpickr.js"></script>
<!-- End custom js for this page -->
<script>
    // Restricts input for each element in the set of matched elements to the given inputFilter.
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
    }, "کرنسی اماؤنٹ اندراج کریں");
    $(".numberOnly").inputFilter(function (value) {
        return /^\d*$/.test(value);
    }, "نمبر اندراج کریں");
</script>
<script>
    var indx = [49,50,51,52,53,54,55,56,57,48,65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 78, 79, 80, 81, 82, 83, 84, 85, 86, 87, 88, 89, 90,
        190,
        32, 232,
        265, 266, 267, 268, 269, 270, 271, 272, 273, 274, 275, 276, 277, 278, 279, 280, 281, 282, 283, 284, 285, 286, 287, 288, 289, 290, 291, 292, 293];
    var maps = ['1','2','3','4','5','6','7','8','9','0', 'ا', 'ب', 'چ', 'د', 'ع', 'ف', 'گ', 'ح', 'ی', 'ج', 'ک', 'ل', 'م', 'ن', 'ہ', 'پ', 'ق', 'ر', 'س', 'ت', 'ء', 'ط', 'و', 'ش', 'ے', 'ز',
        '۔',
        ' ', ' ',
        'آ', '', 'ث', 'ڈ', 'ٰٰ', '', 'غ', 'ھ', 'ِ', 'ض', 'خ', '', '', 'ں', 'ۃ', 'ُ', 'ْ', 'ڑ', 'ص', 'ٹ', 'ئ', 'ظ', 'ّ', 'ژ', 'َ', 'ذ'];

    var result = [];
    indx.forEach(function (key, i) {
        result[key] = maps[i];
    });
    var value = $(".input-urdu").val();
    //console.log(value);
    //var value = '';
    var shiftDown = false;
    var ctrlDown = false;
    var shiftKey = 16;
    var ctrlKey = 17;

    $(document).on('focus', ".input-urdu", function (e) {
        value = $(this).val();
    });
    $(document).keydown(function (e) {
        if (e.keyCode == shiftKey) {
            shiftDown = true;
        }
        if (e.keyCode == ctrlKey) {
            ctrlDown = true;
        }
    }).keyup(function (e) {
        if (e.keyCode == shiftKey) {
            shiftDown = false;
        }
        if (e.keyCode == ctrlKey) {
            ctrlDown = false;
        }

    });

    $(document).on('keyup', ".input-urdu", function (e) {
        var keyCode = e.keyCode;
        //console.log(keyCode);
        if (keyCode === 8) {
            if (value != '') {
                value = value.slice(0, -1);
                $(this).val(value);
            }
        }
        if (keyCode === 9) {
            if ($(this).val() === '') {
                value = '';
            } else {
                value = $(this).val();
            }
            $(this).val(value);
        }
        if (ctrlDown === true && keyCode === 65) {
            $(this).select();
        }
        else if (ctrlDown === true && keyCode === 82) {

        } else {
            if (indx.indexOf(keyCode) !== -1) {
                if (shiftDown === true) {
                    var char = keyCode + 200;
                } else {
                    var char = keyCode;
                }
                value = value + result[char];
                $(this).val(value);
            }
        }
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
    function deleteRecord(e) {
        var id = $(e).attr('id');
        var url = $(e).attr('data-url');
        var tbl = $(e).attr('data-tbl');
        if (id) {
            if (confirm('Are you sure to delete?')) {
                window.location.href = 'ajax/deleteRecord.php?id=' + id + '&tbl=' + tbl + '&url=' + url;
            } else {
                //alert('Action aborted.\nPicture not deleted.');
            }
        }
    }
</script>
<script>
    function blockRecord(e) {
        var id = $(e).attr('id');
        var pk = $(e).attr('data-pk');
        var active = $(e).attr('data-active');
        var message = $(e).attr('data-message');
        var url = $(e).attr('data-url');
        var tbl = $(e).attr('data-tbl');
        if (id) {
            if (confirm('کیا آپ بلاک کرنا چاہتے ہیں؟')) {
                window.location.href = 'ajax/blockRecord.php?id=' + id + '&tbl=' + tbl + '&url=' + url + '&pk=' + pk + '&active=' + active+ '&message=' + message;
            } else {
                //alert('Action aborted.\nPicture not deleted.');
            }
        }
    }
</script>
<script>
    $(".alert-dismissible").fadeTo(5000, 8000).slideUp(1000, function(){
        $(".alert-dismissible").slideUp(1000);
        $(".alert-section").addClass('d-none');
    });
</script>
</body>
</html>
