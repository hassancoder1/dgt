<?php
require 'header.php';
$page = $_GET['page'] ?? 'compose';
$pdo = new PDO('mysql:host=localhost;dbname=' . $dbname, $username, $password);
?>
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    :root {
        --primary-color: #2563eb;
        --border-color: #e5e7eb;
        --hover-color: #f3f4f6;
    }

    .special-emails {
        margin-top: 0 !important;
    }

    .brand-logo {
        color: #2563eb;
        font-size: 1.25rem;
        text-decoration: none;
        font-weight: 600;
    }

    .brand-logo:hover {
        color: #1d4ed8;
    }

    .special-emails .top-bar {
        margin-top: -70px;
    }

    .nav-pills .nav-link {
        color: #4b5563;
        border-radius: 8px;
        padding: 0.75rem 1.25rem;
        margin: 0.25rem 0;
        transition: all 0.2s;
        background-color: #fff;
    }

    .nav-pills .nav-link:hover {
        background-color: var(--hover-color);
    }

    .nav-pills .nav-link.active {
        background-color: var(--primary-color);
        color: white;
    }

    .top-bar {
        border-bottom: 1px solid var(--border-color);
        padding: 1rem 0;
        background-color: white;
    }

    .profile-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .profile-info img {
        border-radius: 50%;
        width: 40px;
        height: 40px;
        object-fit: cover;
    }

    .dropdown-menu {
        border-radius: 8px;
        width: 200px;
    }

    .btn-logout {
        color: #d9534f;
        text-align: center;
        font-weight: bold;
        cursor: pointer;
    }

    .btn-logout:hover {
        text-decoration: underline;
    }

    .text-white-on-hover:hover {
        color: #ffffff !important;
    }
</style>

<div class="top-bar mb-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <a href="emails" class="brand-logo">
                <i class="fas fa-envelope-open-text me-2"></i>
                DGT E-Mails
            </a>
            <div> <?php
                    if (isset($_SESSION['alert'])) {
                        [$alertType, $Message] = [$_SESSION['alert'], $_SESSION['message']];
                        unset($_SESSION['alert'], $_SESSION['message']);
                        if ($alertType === 'success') {
                            echo '<span class="fw-bold text-success text-center myalert">' . $Message . '</span>';
                        } elseif ($alertType === 'failed') {
                            echo '<span class="fw-bold text-danger text-center myalert">' . $Message . '</span>';
                        }
                    }
                    ?></div>
            <div class="profile-info pointer dropdown">
                <img src="<?= $_SESSION['image'] ?? 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png'; ?>" alt="Profile" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="dropdown-menu border dropdown-menu-end">
                    <div class="px-3 py-2">
                        <p class="mb-0"><strong><?= $_SESSION['name']; ?></strong></p>
                        <small class="text-muted">@<?= $_SESSION['role']; ?></small>
                    </div>
                    <hr class="my-1">
                    <a href="logout.php" class="dropdown-item btn-logout">Logout</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-3">
            <div class="nav flex-column nav-pills">
                <a class="border nav-link<?= $page === 'compose' ? ' active' : ''; ?>" href="emails?page=compose">
                    <i class="fas fa-pen-to-square me-2"></i>Compose
                </a>
                <a class="border nav-link<?= $page === 'sent-emails' ? ' active' : ''; ?>" href="emails?page=sent-emails">
                    <i class="fas fa-paper-plane me-2"></i>Sent
                </a>
                <a class="border nav-link<?= $page === 'inbox' ? ' active' : ''; ?>" href="emails?page=inbox">
                    <i class="fas fa-inbox me-2"></i>Inbox
                </a>
                <a class="border nav-link" href="/">
                    <i class="fas fa-arrow-left me-2"></i>Home
                </a>
            </div>
        </div>

        <div class="col-md-9">
            <div class="email-container">
                <?php if ($page === 'compose') { ?>
                    <!-- Compose Email Section -->
                    <div class="compose-section">
                        <h4 class="mb-3">Compose New Email</h4>
                        <form method="post" enctype="multipart/form-data">
                            <div class="mb-2">
                                <label class="form-label">To</label>
                                <div class="tag-input-container form-control" style="min-height: 40px; display: flex; align-items: center; flex-wrap: wrap;">
                                    <input type="text" id="emailInput" class="tag-input" placeholder="Add email and press enter" style="flex: 1; border: none; outline: none;" />
                                </div>
                                <input type="hidden" name="to" id="emailInputHidden" />
                            </div>

                            <div class="mb-2">
                                <label class="form-label">Subject</label>
                                <input type="text" class="form-control" name="subject" required>
                            </div>

                            <div class="mb-2">
                                <label class="form-label">Message</label>
                                <div class="quill-editor rounded-top bg-white">
                                    <div id="editor" class="rounded-bottom" style="height: 100px;">
                                        <?php
                                        if (isset($_GET['file-url']) && isset($_GET['file-name']) && isset($_GET['page-name'])) {
                                            $fileURL = htmlspecialchars($_GET['file-url']);
                                            $fileName = htmlspecialchars($_GET['file-name']);
                                            $pageName = htmlspecialchars($_GET['page-name']);
                                            echo '<br><br>';
                                            echo '<strong>Page Name:</strong> ' . $pageName . '<br>';
                                            echo '<a href="' . $fileURL . '" target="_blank" class="text-primary">' . $fileName . '.pdf</a>';
                                        }
                                        ?>
                                    </div>
                                </div>
                                <textarea name="message" style="display:none;"></textarea>
                            </div>

                            <div class="mb-2">
                                <label class="form-label">Add New Attachment</label>
                                <input type="file" class="form-control" name="file[]" multiple>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-sm btn-outline-danger me-3 px-3 py-2 rounded border-0 text-danger shadow-sm hover-shadow-lg transition-all ease-in-out duration-300 text-white-on-hover">
                                    <i class="fas fa-trash-alt me-2"></i> Discard
                                </button>

                                <button type="submit" name="sendEmail" class="btn btn-sm btn-primary px-3 py-2 rounded shadow-sm hover-shadow-lg transition-all ease-in-out duration-300">
                                    <i class="fas fa-paper-plane me-2"></i> Send
                                </button>
                            </div>
                        </form>

                        <style>
                            .tag {
                                display: inline-flex;
                                align-items: center;
                                border: 1px solid #007bff;
                                /* border-color: #007bff; */
                                /* color: white; */
                                padding: 0 8px;
                                border-radius: 4px;
                                margin: 2px;
                            }

                            .tag-close {
                                margin-left: 5px;
                                cursor: pointer;
                                font-weight: bold;
                            }
                        </style>

                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                        <script>
                            $(document).ready(function() {
                                const $tagInput = $('#emailInput');
                                const $tagContainer = $('.tag-input-container');
                                const $hiddenInput = $('#emailInputHidden');
                                let emails = [];

                                // Add email as a tag
                                function addTag(email) {
                                    if (email && validateEmail(email)) {
                                        if (!emails.includes(email)) {
                                            emails.push(email);
                                            const tagHTML = `<span class="tag">${email}<span class="tag-close" data-email="${email}">&times;</span></span>`;
                                            $tagContainer.prepend(tagHTML);
                                            updateHiddenInput();
                                        }
                                        $tagInput.val('');
                                    }
                                }

                                // Remove email
                                $tagContainer.on('click', '.tag-close', function() {
                                    const email = $(this).data('email');
                                    emails = emails.filter(e => e !== email);
                                    $(this).parent('.tag').remove();
                                    updateHiddenInput();
                                });

                                // Update hidden input
                                function updateHiddenInput() {
                                    $hiddenInput.val(emails.join('~'));
                                }

                                // Validate email
                                function validateEmail(email) {
                                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                                    return emailRegex.test(email);
                                }

                                // Handle input events
                                $tagInput.on('keydown', function(e) {
                                    if (e.key === 'Enter' || e.key === ',') {
                                        e.preventDefault();
                                        const email = $tagInput.val().trim();
                                        addTag(email);
                                    } else if (e.key === 'Backspace' && $tagInput.val() === '') {
                                        emails.pop();
                                        $tagContainer.find('.tag').last().remove();
                                        updateHiddenInput();
                                    }
                                });
                            });
                        </script>

                    </div>
                <?php } elseif ($page === 'sent-emails') { ?>
                    <div class="sent-emails-section">
                        <h4 class="mb-4">Sent Emails</h4>
                        <div class="email-list">
                            <?php
                            $stmt = $pdo->query("SELECT * FROM sent_emails ORDER by created_at DESC");
                            if ($stmt->rowCount() > 0) {
                                while ($email = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    $hash = $email['hash'];
                                    $recipients = htmlspecialchars($email['recipients']);
                                    $subject = htmlspecialchars($email['subject']);

                                    // Decode and strip HTML tags from the message
                                    $message = strip_tags(htmlspecialchars_decode($email['message']));
                                    $createdAt = new DateTime($email['created_at']);
                                    $currentDate = new DateTime();
                                    $formattedTime = $createdAt->format('g:i a');

                                    // Determine if the date is today, yesterday, or earlier
                                    if ($createdAt->format('Y-m-d') === $currentDate->format('Y-m-d')) {
                                        $sentDate = "Today, $formattedTime";
                                    } elseif ($createdAt->format('Y-m-d') === $currentDate->modify('-1 day')->format('Y-m-d')) {
                                        $sentDate = "Yesterday, $formattedTime";
                                    } else {
                                        $sentDate = htmlspecialchars($createdAt->format('F j, Y, g:i a'));
                                    }

                                    // Truncate subject and message to a single line with "..."
                                    $truncatedSubject = mb_strimwidth($subject, 0, 50, '...');
                                    $truncatedMessage = mb_strimwidth($message, 0, 70, '...');

                                    echo "
                                    <div class='email-item bg-white border mb-2 px-3 py-2 rounded'>
                                        <div class='d-flex justify-content-between align-items-center'>
                                            <div>
                                                <div class='recipient-tag fw-bold text-primary'>{$recipients}</div>
                                                <h6 class='mt-1 mb-1 text-dark'>{$truncatedSubject}</h6>
                                                <p class='text-muted mb-0' style='font-size: 0.9rem;'>{$truncatedMessage}</p>
                                            </div>
                                            <div class='text-end'>
                                                <small class='text-muted' style='font-size: 0.8rem;'>{$sentDate}</small>
                                                <div class='mt-1'>
                                                    <a href='?page=view&id=$hash' class='btn btn-sm btn-outline-primary me-1' title='View'>
                                                        <i class='fa fa-envelope-open-text'></i>
                                                    </a>
                                                    <button class='btn btn-sm btn-outline-danger delete-email-btn' data-hash='$hash' title='Delete'>
                                                        <i class='fas fa-trash-alt'></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>";
                                }
                            } else {
                                // Display "No Emails Found" UI
                                echo "
            <div class='inbox-section bg-white'>
                <div class='text-center py-5'>
                    <i class='fas fa-inbox fa-3x text-muted mb-3'></i>
                    <h4>No Sent Emails Found!</h4>
                    <p class='text-muted'>You haven't sent any emails yet.</p>
                </div>
            </div>";
                            }
                            ?>
                        </div>
                    </div>

                    <!-- Confirmation Modal -->
                    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to delete this email? This action cannot be undone.
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <a href="#" id="confirmDeleteButton" class="btn btn-danger">Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        // Handle delete button click
                        document.querySelectorAll('.delete-email-btn').forEach(button => {
                            button.addEventListener('click', function() {
                                const hash = this.dataset.hash;
                                const deleteUrl = `?deleteEmail=${hash}`;
                                document.getElementById('confirmDeleteButton').setAttribute('href', deleteUrl);
                                const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
                                modal.show();
                            });
                        });
                    </script>
                    <?php } elseif ($page === 'view') {
                    // Fetch and display email details based on the hash
                    $hash = $_GET['id'] ?? '';
                    $stmt = $pdo->prepare("SELECT * FROM sent_emails WHERE hash = ?");
                    $stmt->execute([$hash]);
                    $email = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($email) { ?>
                        <div class="container mt-4">
                            <div class="email-view p-4 bg-white rounded shadow-sm">
                                <div class="email-view-header mb-4">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h4 class="mb-2"><?= htmlspecialchars($email['subject']) ?></h4>
                                            <div class="recipient-tag mb-2 text-muted">
                                                To: <?= htmlspecialchars($email['recipients']) ?>
                                            </div>
                                            <small class="text-muted">Sent <?= date('F j, Y, g:i a', strtotime($email['created_at'])) ?></small>
                                        </div>
                                        <div>
                                            <button class="btn btn-outline-danger" title="Delete Email" onclick="confirmDelete('<?= $hash ?>')">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="email-view-content">
                                    <div class="message-content mb-4">
                                        <?= nl2br(htmlspecialchars_decode($email['message'])) ?>
                                    </div>

                                    <?php if (!empty($email['attachments'])): ?>
                                        <div class="email-attachments mb-3">
                                            <h6>Attachments:</h6>
                                            <ul class="list-unstyled">
                                                <?php
                                                $attachments = json_decode($email['attachments'], true);
                                                foreach ($attachments as $attachment): ?>
                                                    <li>
                                                        <a href="uploads/<?= htmlspecialchars($attachment) ?>" download>
                                                            <i class="fas fa-paperclip me-2"></i><?= htmlspecialchars($attachment) ?>
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <script>
                            function confirmDelete(hash) {
                                if (confirm('Are you sure you want to delete this email?')) {
                                    window.location.href = '?deleteEmail=' + hash;
                                }
                            }
                        </script>
                    <?php } else { ?>
                        <div class="container mt-4 text-center">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i> Email not found.
                            </div>
                        </div>
                    <?php }
                } elseif ($page === 'inbox') { ?>
                    <h4 class="mb-4">Inbox</h4>
                    <!-- Inbox Section -->
                    <div class="inbox-section bg-white">
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h4>Coming Soon!</h4>
                            <p class="text-muted">We're working on bringing you the inbox feature.</p>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    $(document).ready(function() {
        setTimeout(function() {
            $('.myalert').addClass('d-none');
        }, 3000)

        var quill = new Quill('#editor', {
            theme: 'snow',
            placeholder: 'Compose your email...',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'],
                    ['link'],
                    ['clean']
                ]
            }
        });

        $('form').on('submit', function() {
            $('textarea[name=message]').val(quill.root.innerHTML);
        });
    });
</script>

<?php
include 'footer.php';
if (isset($_POST['sendEmail'])) {
    $to = explode('~', $_POST['to']); // Recipient(s), separated by "~"
    $subject = $_POST['subject'];    // Email subject
    $message = $_POST['message'];    // Email body
    $attachments = $_FILES['file']; // File attachments

    // Generate a unique hash for the email
    $hash = hash('sha256', uniqid('', true));

    // Create the "mail-uploads" directory if it doesn't exist
    $uploadDir = __DIR__ . '/mail-uploads';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Process and save attachments
    $savedAttachments = [];
    if (isset($attachments['error']) && is_array($attachments['error'])) {
        for ($i = 0; $i < count($attachments['error']); $i++) {
            if ($attachments['error'][$i] === UPLOAD_ERR_OK) {
                $originalName = $attachments['name'][$i];
                $tempPath = $attachments['tmp_name'][$i];
                $uniqueFileName = uniqid('file_', true) . '_' . basename($originalName);

                $destinationPath = $uploadDir . '/' . $uniqueFileName;
                if (move_uploaded_file($tempPath, $destinationPath)) {
                    $savedAttachments[] = $uniqueFileName;
                }
            }
        }
    }

    // Convert saved file paths to JSON
    $attachmentsJSON = json_encode($savedAttachments);

    // Store email data in the database
    try {
        $stmt = $pdo->prepare("INSERT INTO sent_emails (hash, recipients, subject, message, attachments, created_at) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $hash,
            implode(', ', $to),
            $subject,
            $message,
            $attachmentsJSON, // Save attachment file names
            date('Y-m-d H:i:s')
        ]);
    } catch (Exception $e) {
        error_log('Error inserting email into the database: ' . $e->getMessage());
    }

    // Send the email
    $headers = "From: DAC.DGT.LLC <no-reply-dac@dac.llc>\r\n";
    $headers .= "Reply-To: DAC.DGT.LLC <no-reply-dac@dgt.llc>\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"boundary\"\r\n";

    $emailBody = "--boundary\r\n";
    $emailBody .= "Content-Type: text/html; charset=UTF-8\r\n";
    $emailBody .= "Content-Transfer-Encoding: base64\r\n\r\n";
    $emailBody .= chunk_split(base64_encode($message));

    // Add attachments to the email
    foreach ($savedAttachments as $fileName) {
        $filePath = $uploadDir . '/' . $fileName;
        if (file_exists($filePath)) {
            $fileContent = chunk_split(base64_encode(file_get_contents($filePath)));
            $emailBody .= "--boundary\r\n";
            $emailBody .= "Content-Type: application/octet-stream; name=\"" . basename($fileName) . "\"\r\n";
            $emailBody .= "Content-Transfer-Encoding: base64\r\n";
            $emailBody .= "Content-Disposition: attachment; filename=\"" . basename($fileName) . "\"\r\n\r\n";
            $emailBody .= $fileContent;
        }
    }
    $emailBody .= "--boundary--";

    $emailSent = true;
    foreach ($to as $recipient) {
        if (!mail($recipient, $subject, $emailBody, $headers)) {
            $emailSent = false;
            break;
        }
    }

    // Set session alerts based on email status
    if ($emailSent) {
        $_SESSION['alert'] = 'success';
        $_SESSION['message'] = 'E-Mail Sent Successfully!';
    } else {
        $_SESSION['alert'] = 'failed';
        $_SESSION['message'] = '!ERROR - While Sending Your E-Mail, Please Try Again Later!';
    }

    echo '<script>window.location.href="emails?page=sent-emails"</script>';
}



// Handle email deletion
if (isset($_GET['deleteEmail'])) {
    $hash = $_GET['deleteEmail'];
    $stmt = $pdo->prepare("DELETE FROM sent_emails WHERE hash = ?");
    $result = $stmt->execute([$hash]);
    if ($result) {
        $_SESSION['alert'] = 'success';
        $_SESSION['message'] = 'E-Mail Deleted Successfully!';
    } else {
        $_SESSION['alert'] = 'failed';
        $_SESSION['message'] = '!ERROR - While Deleting Your E-Mail, Please Try Again Later!';
    }
    echo '<script>window.location.href="emails?page=sent-emails"</script>';
}
?>