<?php
    $db_hostname = "127.0.0.1";
    $db_username = "root";
    $db_password = "";
    $db_name = "tour";

    $conn = new mysqli($db_hostname, $db_username, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Connection Failed: " . $conn->connect_error);
    }

    // Get POST data safely
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Prepare and bind the statement
    $stmt = $conn->prepare("INSERT INTO contact (Name, Email, Phone, Subject, Message) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sssss", $name, $email, $phone, $subject, $message);

    if ($stmt->execute()) {
        echo "We will contact you soon.";

        // Send email to Shashi Kant
        $to = "shashikantkhaje@gmail.com";
        $email_subject = "New Contact Form Submission";
        $email_body = "You have received a new message from your website contact form:\n\n" .
                      "Name: $name\n" .
                      "Email: $email\n" .
                      "Phone: $phone\n" .
                      "Subject: $subject\n" .
                      "Message: $message";

        $headers = "From: noreply@yourwebsite.com\r\n" .
                   "Reply-To: $email\r\n" .
                   "X-Mailer: PHP/" . phpversion();

        if (mail($to, $email_subject, $email_body, $headers)) {
            echo " Email has been sent to admin.";
        } else {
            echo " Error: Email could not be sent.";
        }
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
?>
