<?php
$page_title = 'Read Email';
include("header.php"); 
global $connect;

// Check if `id` is set in the URL
if (isset($_GET['id'])) {
    $email_id = intval($_GET['id']);

    // Fetch the email from the database
    $query = "SELECT subject, message, recipients, created_at, file_path FROM sent_emails WHERE id = ?";
    $stmt = $connect->prepare($query);
    $stmt->bind_param("i", $email_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $email = $result->fetch_assoc();
    } else {
        echo "<div class='alert alert-danger'>Email not found.</div>";
        exit;
    }
} else {
    echo "<div class='alert alert-danger'>No email selected.</div>";
    exit;
}
?>

<style>
    .email-content {
        padding: 30px;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    .email-content .subject {
        font-weight: bold;
        font-size: 1.5rem;
        margin-bottom: 15px;
    }

    .email-content .to-email {
        font-size: 1rem;
        color: #555;
        margin-bottom: 10px;
    }

    .email-content .message-body {
        font-size: 1rem;
        color: #333;
        margin-bottom: 15px;
    }

    .email-content .date {
        font-size: 0.9rem;
        color: #888;
        margin-bottom: 20px;
    }

    .email-content .attachment {
        font-size: 1rem;
        margin-top: 20px;
    }

    .email-content .attachment i {
        margin-right: 10px;
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
        <div class="email-content">
            <div class="subject"><?= htmlspecialchars($email['subject']); ?></div>
            <div class="to-email">To: <?= htmlspecialchars($email['recipients']); ?></div>
            <div class="message-body"><?= $email['message']; ?></div>
            <div class="date">Sent on: <?= date('d M Y, h:i A', strtotime($email['created_at'])); ?></div>
            
            <?php if ($email['file_path']): ?>
                <div class="attachment">
                    <i class="fas fa-paperclip"></i> Attachment: 
                    <a href="<?= $email['file_path']; ?>" download="<?= str_replace('mail-uploads/', '', $email['file_path']); ?>">
                        <?= str_replace('mail-uploads/', '', $email['file_path']); ?>
                    </a>
                </div>
            <?php endif; ?>

            <a href="/inbox" class="btn mt-3 btn-secondary">Go Back</a>
        </div>
    </div>
</div>

<?php include("footer.php"); ?>
<script>$("#emails").addClass('active');</script>
