<?php include("header.php"); ?>
    <h4 class="mb-3">پروفائل</h4>
    <div class="row">
        <div class="col-md-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-10">
                            <form>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="input-group mb-3">
                                            <label class="input-group-text" id="username">یوزرنام</label>
                                            <input type="text" class="form-control" placeholder="یوزرنام"
                                                   value="<?php echo $userData['username']; ?>" name="username"
                                                   autofocus aria-label="یوزرنام" aria-describedby="username">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="input-group mb-3">
                                            <label class="input-group-text" id="pass">پاسورڈ</label>
                                            <input type="password" class="form-control" placeholder="پاسورڈ"
                                                   value="<?php echo $userData['pass']; ?>" name="pass"
                                                   aria-label="پاسورڈ" aria-describedby="pass">
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round" class="feather feather-check-square btn-icon-prepend">
                                        <polyline points="9 11 12 14 22 4"></polyline>
                                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                                    </svg>
                                    پروفائل محفوظ کریں
                                </button>
                            </form>
                        </div>
                        <div class="col-md-2">
                            <form action="ajax/uploadUserPicture.php" method="post" id="userPicUpload"
                                  enctype="multipart/form-data">
                                <label for="fileUpload">
                                    <?php if (!empty($userData['image'])) {
                                        echo '<img class="img-fluid img-thumbnail" src="' . $userData['image'] . '" alt="profile">';
                                    } else {
                                        echo '<img src="assets/images/others/logo-placeholder.png" alt="profile" class="img-fluid img-thumbnail">';
                                    } ?>
                                    <input type="file" id="fileUpload" name="fileUpload" style="display:none">
                                </label>
                                <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
                                <input type="hidden" name="url" value="profile.php">
                            </form>
                            <script>
                                document.getElementById("fileUpload").onchange = function () {
                                    document.getElementById("userPicUpload").submit();
                                }
                            </script>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
<?php include("footer.php"); ?>