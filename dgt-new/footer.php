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
    $("#tableFilter,.inputFilter").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("table tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
    $('body').on('keyup keypress', function(e) {
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
        $("html, body").animate({
                scrollTop: $(document).height()
            },
            duration
        );
    }
</script>
<script>
    jQuery(document).ready(function($) {
        $(".clickable-row").click(function() {
            window.location = $(this).data("href");
        });
    });
</script>
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
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.dropdown-submenu .dropdown-toggle').forEach(function(element) {
            element.addEventListener('click', function(e) {
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
    (function($) {
        $.fn.inputFilter = function(callback, errMsg) {
            return this.on("input keydown keyup mousedown mouseup select contextmenu drop focusout", function(e) {
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

    $(".currency").inputFilter(function(value) {
        return /^-?\d*[.,]?\d{0,9}$/.test(value);
    }, "INVALID");
    $(".numberOnly").inputFilter(function(value) {
        return /^\d*$/.test(value);
    }, "INVALID");
</script>


<!-- +++=============== FOR PRINT ==============+++ -->
<div class="position-fixed top-0 start-0 w-100 h-100 d-none justify-content-center align-items-center" style="background: rgba(25, 26, 25, 0.4); z-index: 60;" id="processingScreen">
    <div class="spinner-border text-white" style="width: 5rem; height: 5rem;" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
<script>
    function getQueryParameter(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    }
    function currentFormattedDateTime() {
        const now = new Date();
        const formattedDateTime = `${String(now.getDate()).padStart(2, '0')}-${String(now.getMonth() + 1).padStart(2, '0')}-${now.getFullYear()} ${
            String(now.getHours() % 12 || 12).padStart(2, '0')
        }:${String(now.getMinutes()).padStart(2, '0')}:${String(now.getSeconds()).padStart(2, '0')} ${now.getHours() >= 12 ? 'PM' : 'AM'}`;
        return formattedDateTime;
    }
    function openAndPrint(url) {
        const newWindow = window.open(
            url,
            '_blank',
            'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=' + screen.width + ',height=' + screen.height
        );
        newWindow.onload = () => {
            newWindow.print();
        };
    }

    function getFileThrough(fileType, url) {
        $('#processingScreen').toggleClass('d-none d-flex');
        let formattedFileName = url
            .split('?')[0] // Remove query parameters and their values
            .replace(/^print\//, '')
            .replace(/-main|-print$/, '')
            .trim();
        let formattedName = formattedFileName
            .replace(/-/g, ' ')
            .split(' ')
            .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
            .join(' ');

        $.ajax({
            url: `${window.location.protocol}//${window.location.host}/ajax/generateFile.php`,
            type: 'post',
            data: {
                filetype: fileType,
                pageURL: url
            },
            success: function(response) {
                $('#processingScreen').toggleClass('d-none d-flex');
                try {
                    const result = JSON.parse(response);
                    if (result.fileURL) {
                        const fileURL = result.fileURL;
                        if (fileType === 'pdf' || fileType === 'word') {
                            fetch(fileURL)
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error(`HTTP error! Status: ${response.status}`);
                                    }
                                    return response.blob();
                                })
                                .then(blob => {
                                    const currentTime = Date.now();
                                    const fileExtension = fileType === 'pdf' ? 'pdf' : 'docx';
                                    const fileName = `Print-${formattedFileName}${currentTime}.${fileExtension}`;
                                    const downloadLink = document.createElement('a');
                                    const objectURL = URL.createObjectURL(blob);
                                    downloadLink.href = objectURL;
                                    downloadLink.download = fileName;
                                    document.body.appendChild(downloadLink);
                                    downloadLink.click();
                                    URL.revokeObjectURL(objectURL);
                                    document.body.removeChild(downloadLink);
                                })
                                .catch(error => {
                                    console.error('Error downloading file:', error);
                                    alert('Failed to download the file.');
                                });
                        } else if (fileType === 'whatsapp') {
                            const whatsappURL = `https://wa.me/?text=Your+file+${encodeURIComponent(formattedName)}+is+ready!+Download+it+here:+${encodeURIComponent(fileURL)}`;
                            window.open(whatsappURL, '_blank');
                        } else if (fileType === 'email') {
                            const emailURL = `mailto:?subject=Your+Requested+File+-+${encodeURIComponent(formattedName)}&body=Hello,%0A%0AYour+file+${encodeURIComponent(formattedName)}+is+ready+for+download!%0A%0AAccess+it+here:+${encodeURIComponent(fileURL)}`;
                            window.open(emailURL, '_blank');
                        }

                    } else {
                        alert('Failed to retrieve the file URL.');
                        console.log(result.error);
                    }
                } catch (e) {
                    console.error('Error parsing response:', e);
                    alert('Invalid response format received from the server.');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Hide the processing screen
                $('#processingScreen').toggleClass('d-none d-flex');

                console.error("AJAX Error: ", textStatus, errorThrown);
                alert('An error occurred while processing your request. Please refresh and try again.');
            }
        });
    }
</script>
</body>

</html>