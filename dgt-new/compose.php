<?php 
$page_title = 'Compose Email';
include("header.php"); 
?>
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">

<div class="fixed-top">
    <?php require_once('nav-links.php'); ?>
    <div class="bg-light shadow p-2 border-bottom border-warning d-flex gap-0 align-items-center justify-content-between mb-md-2">
        <div class="fs-5 text-uppercase"><?php echo $page_title; ?></div>
        <div></div>
    </div>
</div>

<?php
if (isset($_POST['sendMail'])) {
    global $connect;

    // Get form data
    $recipients = $_POST['to']; // This will be an array
    $subject = $_POST['subject'];
    $message = $_POST['message']; // This will now include the rich text content from Quill

    // Handle file upload
    $uploadDir = 'mail-uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true); // Create uploads directory if it doesn't exist
    }

    $file = $_FILES['file'];
    $filePath = $uploadDir . basename($file['name']);
    $fileUploadSuccess = move_uploaded_file($file['tmp_name'], $filePath);

    if ($fileUploadSuccess) {
        // Insert email data into the database
        $stmt = $connect->prepare("INSERT INTO sent_emails (recipients, subject, message, file_path) VALUES (?, ?, ?, ?)");
        $recipientsString = implode(',', $recipients);
        $stmt->bind_param("ssss", $recipientsString, $subject, $message, $filePath);

        if ($stmt->execute()) {
            // Prepare to send the email with attachment
            $headers = "From: no-reply@dgt.llc\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: multipart/mixed; boundary=\"boundary1\"\r\n";

            foreach ($recipients as $to) {
                $body = "--boundary1\r\n";
                $body .= "Content-Type: text/html; charset=UTF-8\r\n";
                $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
                $body .= $message . "\r\n\r\n";  // Now sends rich text content

                if (file_exists($filePath)) {
                    $fileContent = chunk_split(base64_encode(file_get_contents($filePath)));
                    $body .= "--boundary1\r\n";
                    $body .= "Content-Type: application/octet-stream; name=\"" . basename($filePath) . "\"\r\n";
                    $body .= "Content-Transfer-Encoding: base64\r\n";
                    $body .= "Content-Disposition: attachment; filename=\"" . basename($filePath) . "\"\r\n\r\n";
                    $body .= $fileContent . "\r\n\r\n";
                }

                $body .= "--boundary1--"; // End boundary
                mail($to, $subject, $body, $headers);
            }

            echo "<div class='alert alert-success'>Email sent successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error saving data: " . $stmt->error . "</div>";
        }

        $stmt->close();
    } else {
        echo "<div class='alert alert-danger'>Error uploading file.</div>";
    }

    $connect->close();
}
?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body p-0">
                <div class="row">
                    <form method="post" enctype="multipart/form-data" onsubmit="prepareMessage()">
                        <div class="col-lg-12 table-form">
                            <div class="p-3 pb-0 pt-4">
                                <div class="row mb-3">
                                    <label for="to" class="col-md col-form-label">To:</label>
                                    <div class="col-md-11">
                                        <input type="email" class="form-control" name="to[]" id="to" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="subject" class="col-md col-form-label">Subject</label>
                                    <div class="col-md-11">
                                        <input class="form-control" type="text" id="subject" name="subject" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="file" class="col-md col-form-label">Attach File</label>
                                    <div class="col-md-11">
                                        <input type="file" class="form-control" id="file" name="file" accept=".pdf,.doc,.docx,.txt">
                                        <div class="form-text">You can upload PDF, Word, or text files.</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="px-3 row">
                                <div class="col-md-12">
                                    <label class="form-label visually-hidden" for="emaileditor">Descriptions</label>
                                    <div id="emaileditor" class="form-control" style="min-height: 200px;"></div>
                                    <textarea name="message" id="message" style="display: none;"></textarea> <!-- Hidden field to store Quill content -->
                                </div>
                                <div class="col-md-12 mt-5 pt-3">
                                    <button class="btn btn-primary me-1 mb-1" name="sendMail" type="submit">Send</button>
                                    <button class="btn btn-secondary me-1 mb-1" type="reset">Cancel</button>
                                </div>
                            </div>
                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("footer.php"); ?>
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

<script>
    $("#emails").addClass('active');
    $("#compose").addClass('active');

    const quill = new Quill('#emaileditor', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{'header': [1, 2, 3, 4, 5, 6, false]}],
                [{'size': ['small', false, 'large', 'huge']}],
                [{'font': []}],
                ['bold', 'italic', 'underline', 'strike'],
                [{'color': []}, {'background': []}],
                [{'align': []}],
                [{'list': 'ordered'}, {'list': 'bullet'}, {'list': 'check'}],
                ['blockquote', 'code-block'],
                [{'script': 'sub'}, {'script': 'super'}],
                [{'indent': '-1'}, {'indent': '+1'}],
                [{'direction': 'rtl'}],
                ['link', 'image', 'video', 'formula'],
                ['clean']
            ]
        }
    });

    function prepareMessage() {
        // Get the HTML content from the Quill editor and store it in the hidden textarea
        const message = document.querySelector('textarea[name=message]');
        message.value = quill.root.innerHTML;
    }
</script>
