<?php
// Include required libraries
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

require 'connection.php';
// Route handling
$page = $_GET['page'] ?? 'compose';
$openHash = $_GET['open'] ?? null;
$deleteHash = $_GET['delete'] ?? null;

// Delete email
if ($deleteHash) {
    $stmt = $connect->prepare("DELETE FROM emails WHERE hash = ?");
    $stmt->bind_param("s", $deleteHash);
    $stmt->execute();
    header("Location: emails.php?page=sent-emails");
    exit();
}

// Handle form submission for sending emails
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $page === 'compose') {
    $recipients = $_POST['to'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $filePath = '';

    // Handle file upload
    if (!empty($_FILES['file']['name'])) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        $filePath = $uploadDir . basename($_FILES['file']['name']);
        move_uploaded_file($_FILES['file']['tmp_name'], $filePath);
    }

    // Generate unique hash
    $hash = md5(uniqid(rand(), true));

    // Save email to database
    $stmt = $connect->prepare("INSERT INTO emails (hash, recipients, subject, message, file_path) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $hash, implode(',', $recipients), $subject, $message, $filePath);
    $stmt->execute();

    // Send email using PHPMailer
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'your_email@example.com';
        $mail->Password = 'your_password';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('your_email@example.com', 'Your Name');
        foreach ($recipients as $recipient) {
            $mail->addAddress($recipient);
        }
        if ($filePath) $mail->addAttachment($filePath);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        $mail->send();
        echo "<div class='alert alert-success'>Email sent successfully!</div>";
    } catch (Exception $e) {
        echo "<div class='alert alert-danger'>Error sending email: {$mail->ErrorInfo}</div>";
    }
}

// Fetch emails for the sent emails page
$emails = [];
if ($page === 'sent-emails') {
    $result = $connect->query("SELECT * FROM sent_emails ORDER BY created_at DESC");
    while ($row = $result->fetch_assoc()) {
        $emails[] = $row;
    }
}

// Fetch email details for viewing
$emailDetails = null;
if ($openHash) {
    $stmt = $connect->prepare("SELECT * FROM sent_emails WHERE hash = ?");
    $stmt->bind_param("s", $openHash);
    $stmt->execute();
    $result = $stmt->get_result();
    $emailDetails = $result->fetch_assoc();
}
?>