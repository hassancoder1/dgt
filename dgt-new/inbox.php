<?php 
$page_title = 'Sent Emails';
include("header.php"); 
global $connect;
// Fetch emails from the database (replace with your actual query)
$query = "SELECT id, subject, message, recipients, created_at, file_path FROM sent_emails ORDER BY created_at DESC";
$results = $connect->query($query);
?>

<style>
    .email-list-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px;
        border-bottom: 1px solid #ddd;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .email-list-item:hover {
        background-color: #f7f7f7;
    }

    .email-details {
        flex-grow: 1;
        padding-right: 15px;
    }

    .email-details .subject {
        font-weight: bold;
        color: #333;
        font-size: 1.1rem;
    }

    .email-details .to-email {
        font-size: 0.9rem;
        color: #666;
    }

    .email-details .message-preview {
        color: #999;
        font-size: 0.85rem;
        margin-top: 5px;
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
    }

    .email-meta {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        white-space: nowrap;
    }

    .email-meta .date {
        font-size: 0.85rem;
        color: #333;
    }

    .email-meta .attachment-icon {
        font-size: 0.9rem;
        color: #888;
        margin-top: 5px;
    }

    .attachment-icon i {
        font-size: 1.2rem;
    }
</style>

<div class="fixed-top">
    <?php require_once('nav-links.php'); ?>
    <div class="bg-light shadow p-2 border-bottom border-warning d-flex align-items-center justify-content-between mb-md-2">
        <div class="fs-5 text-uppercase"><?php echo $page_title; ?></div>
        <div></div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body p-0">
                <div class="email-list">

                    <?php
                    $i = 1;
                     while ($email = $results->fetch_assoc()): ?>
                        <div class="email-list-item m-2">
                            <div class="email-details">
                                <a href="read-mail?id=<?= $email['id']; ?>" class="subject"> ( <?= $i; ?> ) <?= htmlspecialchars($email['subject']); ?></a>
                                <div class="to-email">To: <?= htmlspecialchars($email['recipients']); ?></div>
                                <div class="message-preview"><?= substr($email['message'], 0, 80); ?>...</div>
                            </div>
                            <div class="email-meta">
                                <div class="date"><?= date('d M Y', strtotime($email['created_at'])); ?></div>
                                <?php if ($email['file_path']): ?>
                                    <div class="attachment-icon"><i class="fas fa-paperclip"> </i><a href="<?= $email['file_path']; ?>" download="<?= str_replace('mail-uploads/','',$email['file_path']); ?>"><?= str_replace('mail-uploads/','',$email['file_path']); ?> </a> </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php
                    $i++;
                 endwhile; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("footer.php"); ?>
<script>$("#emails").addClass('active');</script>
