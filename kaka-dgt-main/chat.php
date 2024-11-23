<?php include("header.php");
$receiver_id = 0;
if (isset($_GET['id'])) {
    $receiver_id = mysqli_real_escape_string($connect, $_GET['id']);
} ?>
<div class="page-content">
    <div class="row chat-wrapper">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row position-relative">
                        <div class="col-lg-4 chat-aside border-end-lg">
                            <div class="aside-content">

                                <div class="aside-header">
                                    <div class="d-flex justify-content-between align-items-center pb-2 mb-2">
                                        <div class="d-flex align-items-center">
                                            <figure class="me-2 mb-0">
                                                <?php if (!empty($userData['image']) && file_exists($userData['image'])) {
                                                    echo '<img class="img-sm rounded-circle " src="' . $userData['image'] . '" alt="">';
                                                } else {
                                                    echo '<img src="assets/images/others/logo-placeholder.png" alt="profile" class="border img-sm rounded-circle">';
                                                } ?>
                                                <div class="status online"></div>
                                            </figure>
                                            <div>
                                                <h6 class="urdu"><?php echo $userData['full_name']; ?></h6>
                                                <p class="text-muted tx-13"><?php echo $userData['role']; ?></p>
                                            </div>
                                        </div>
                                        <a type="button">
                                            <i class="icon-lg text-muted pb-3px" onclick="window.location.reload();"
                                               data-feather="refresh-ccw"></i>
                                        </a>
                                    </div>
                                    <!--<form class="search-form">
                                        <div class="input-group">
                            <span class="input-group-text">
                              <i data-feather="search" class="cursor-pointer"></i>
                            </span>
                                            <input type="text" class="form-control" id="searchForm"
                                                   placeholder="Search here...">
                                        </div>
                                    </form>-->
                                </div>
                                <div class="aside-body">
                                    <div class="tab-content mt-3">
                                        <style>
                                            .chat-item.active{
                                                background: #fafafa;
                                            }
                                        </style>
                                        <div class="tab-pane fade show active" id="chats" style="min-height: 60vh !important;">
                                            <div>
                                                <ul class="list-unstyled chat-list px-1">
                                                    <?php $users = mysqli_query($connect, "SELECT * FROM users WHERE id != '$userId'");
                                                    while ($user = mysqli_fetch_assoc($users)) {
                                                        $active = $user['id'] == $receiver_id ? 'active' : '';
                                                        echo '<li class="chat-item pe-1 ' . $active . '">';
                                                        echo '<a href="chat?id=' . $user["id"] . '" class="d-flex align-items-center ">';
                                                        echo '<figure class="mb-0 me-2">';
                                                        if (!empty($user['image']) && file_exists($user['image'])) {
                                                            echo '<img class="img-xs rounded-circle " src="' . $user['image'] . '" alt="">';
                                                        } else {
                                                            echo '<img src="assets/images/others/logo-placeholder.png" alt="profile" class="border img-xs rounded-circle">';
                                                        }
                                                        echo '</figure>';

                                                        echo '<div class="d-flex justify-content-between flex-grow-1 border-bottom">';
                                                        echo '<div><p class="text-body urdu tx-12">' . $user["full_name"] . '</p><p class="text-muted tx-13 urdu-2">' . branchName($user["branch_id"]) . '</p></div>';
                                                        echo '<div class="d-flex flex-column align-items-end"><p class="text-muted tx-13 mb-1 urdu-2">' . userRole($user["role"]) . '</p></div>';
                                                        echo '';
                                                        echo '</div>';
                                                        echo '</a>';
                                                        echo '</li>';
                                                    } ?>
                                                </ul>
                                            </div>
                                        </div>
                                        <?php if (isset($_SESSION['response'])) {
                                            echo $_SESSION['response'];
                                            unset($_SESSION['response']);
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8 chat-content position-relative">
                            <?php if (isset($_GET['id']) && $_GET['id'] > 0) {
                                $chatUserQ = fetch('users', array('id' => $receiver_id));
                                $chatUser = mysqli_fetch_assoc($chatUserQ); ?>
                                <div class="chat-header border-bottom pb-2">
                                    <div class="d-flex justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <i data-feather="corner-up-left" id="backToChatList"
                                               class="icon-lg me-2 ms-n2 text-muted d-lg-none"></i>
                                            <figure class="mb-0 me-2">
                                                <?php if (!empty($chatUser['image']) && file_exists($chatUser['image'])) {
                                                    echo '<img class="img-sm rounded-circle " src="' . $chatUser['image'] . '" alt="">';
                                                } else {
                                                    echo '<img src="assets/images/others/logo-placeholder.png" alt="profile" class="border img-sm rounded-circle">';
                                                } ?>
                                                <!--<div class="status online"></div>-->
                                            </figure>
                                            <div>
                                                <p class="urdu"><?php echo $chatUser['full_name']; ?></p>
                                                <p class="text-muted tx-13 urdu"><?php echo branchName($chatUser['branch_id']); ?></p>
                                            </div>
                                        </div>
                                        <div>
                                            <input type="text" id="tableFilter" onkeyup="searchInput()"
                                                   class="form-control" placeholder="٘میسج تلاش کریں (f2)">
                                        </div>
                                        <div class="d-flex align-items-center me-n1">
                                            <a class="me-0 me-sm-3" data-bs-toggle="tooltip"
                                               href="tel://<?php echo $chatUser['phone']; ?>"
                                               data-bs-title="Call <?php echo $chatUser['phone']; ?>" type="button">
                                                <i data-feather="phone-call" class="icon-lg text-muted"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="chat-body" style="min-height: 55vh !important;">
                                    <ul class="messages" id="chat">
                                        <?php $chats = mysqli_query($connect, "SELECT * FROM chats WHERE (sender_id = '$userId' AND receiver_id = '$receiver_id') || (sender_id = '$receiver_id' AND receiver_id = '$userId')");
                                        while ($chat = mysqli_fetch_assoc($chats)) {
                                            $class = '';
                                            if ($chat['sender_id'] == $userId) {
                                                $class = 'friend';
                                            }
                                            if ($chat['receiver_id'] == $userId) {
                                                $class = 'me';
                                            }
                                            echo '<li class="message-item ' . $class . '">';
                                            //echo '<img src="https://via.placeholder.com/36x36" class="img-xs rounded-circle" alt="avatar">';
                                            echo '<div class="content"><div class="message">';
                                            if ($chat['is_file'] == 1) {
                                                echo '<a href="' . $chat['msg'] . '" target="_blank"><div class="bubble target">';
                                                echo '<img src="assets/images/attachment.png" alt="' . $chat['msg'] . '" class="img-sm"><p class="target">' . substr($chat['msg'], 11) . '</p>';
                                                echo '</div></a>';
                                            } else {
                                                echo '<div class="bubble target"><p class="">' . $chat['msg'] . '</p></div>';
                                                //echo '<p class="' . $class . '">' . $chat['msg'] . '<span class="date-time">' . date('Y-m-d H:i A', strtotime($chat['date_time'])) . '</span></p>';
                                            }
                                            echo '<span dir="ltr" class="target-">' . date('Y-m-d H:i A', strtotime($chat['date_time'])) . '</span>';
                                            echo '</div></div>';
                                            echo '</li>';
                                        } ?>
                                    </ul>
                                </div>
                                <div class="chat-footer d-flex position-absolute-bottom-0">
                                    <div class="d-none-d-md-block">
                                        <form action="ajax/sendAttachment.php" method="post"
                                              enctype="multipart/form-data"
                                              id="sendAttachment">
                                            <label class="btn border btn-icon rounded-circle me-2"
                                                   data-bs-toggle="tooltip" data-bs-title="Attach files">
                                                <i data-feather="paperclip" class="text-muted"></i>
                                                <input type="file" id="file" class="d-none mb-0" name="attachment">
                                            </label>
                                            <input type="hidden" name="sender_id" value="<?php echo $userId; ?>">
                                            <input type="hidden" name="receiver_id" value="<?php echo $receiver_id; ?>">
                                        </form>
                                        <script>
                                            document.getElementById("file").onchange = function () {
                                                document.getElementById("sendAttachment").submit();
                                            }
                                        </script>
                                    </div>
                                    <form method="post" class="search-form flex-grow-1 me-2">
                                        <div class="input-group ">
                                            <input type="hidden" name="sender_id" value="<?php echo $userId; ?>">
                                            <input type="hidden" name="receiver_id" value="<?php echo $receiver_id; ?>">
                                            <input name="msg" class="form-control bg-light rounded-pill"
                                                   placeholder="Type a message"
                                                   autofocus required autocomplete="off">
                                            <button name="sendMessage" type="submit"
                                                    class="btn btn-primary btn-icon rounded-circle"><i
                                                        data-feather="send"></i></button>
                                        </div>
                                    </form>
                                </div>
                            <?php } else {
                                echo '<h1 class="text-center mt-5 text-success">SELECT A PERSON <br>TO CHAT WITH</h1>';
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<?php if (isset($_POST['sendMessage'])) {
    $today = date('y-m-d h:i:s');
    $msg = $connect->real_escape_string($_POST['msg']);
    $sender_id = $_POST['sender_id'];
    $receiver_id = $_POST['receiver_id'];

    $msge = 'ڈیٹا بیس پرابلم۔';
    $msgType = 'danger';
    $url = 'chat?id=' . $receiver_id;
    $recordAdd = mysqli_query($connect, "INSERT INTO chats(msg, sender_id, receiver_id) VALUES ('$msg', '$sender_id','$receiver_id')");
    if ($recordAdd) {
        $msge = 'میسج سینڈ ہو گیا۔';
        $msgType = 'success';
    }
    message($msgType, $url, $msge);
}

?>
<script type="text/javascript">
    $('#chat').scrollTop($('#chat')[0].scrollHeight);
    $(document).ready(function () {
        $('#add').addClass('active');
    });
</script>

<script src="assets/js/chat.js"></script>
<script>
    function searchInput() {
        let input = document.getElementById("tableFilter");
        let filter = input.value.toLowerCase();
        let nodes = document.getElementsByClassName('target');
        //nodes.style.color = "black";
        for (i = 0; i < nodes.length; i++) {
            if (nodes[i].innerText.toLowerCase().includes(filter)) {
                nodes[i].style.display = "block";
                //nodes[i].style.color = "red";
            } else {
                nodes[i].style.display = "none";
            }
        }
    }
</script>