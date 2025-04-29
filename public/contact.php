<?php
// Initialize variables
$nameErr = $emailErr = "";
$name = $email = $subject = $message = "";
$formSubmitted = false;

// Form processing
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
    } else {
        $name = test_input($_POST["name"]);
    }
    
    // Validate email
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        // Check if email is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }
    
    // Get other form data
    $subject = test_input($_POST["subject"]);
    $message = test_input($_POST["message"]);
    
    // If no errors, send email
    if (empty($nameErr) && empty($emailErr)) {
        $to = "dev@wandrbae.com";
        $email_subject = "Contact Form Submission: " . $subject;
        $email_body = "You have received a new message from your website contact form.\n\n" .
                      "Name: $name\n" .
                      "Email: $email\n" .
                      "Subject: $subject\n" .
                      "Message:\n$message\n";
        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";
        
        // Send email
        if (mail($to, $email_subject, $email_body, $headers)) {
            $formSubmitted = true;
        }
    }
}

// Use sanitize function from your framework or define it if not available
if (!function_exists('test_input')) {
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}
?>

<?php
require_once('../private/initialize.php');
include_header();

$nameErr = $emailErr = "";
$name = $email = $subject = $message = "";
$formSubmitted = false;

// Form processing
if (is_post_request()) {
    // Validate name
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
    } else {
        $name = test_input($_POST["name"]);
    }
    
    // Validate email
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        // Check if email is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }
    
    // Get other form data
    $subject = test_input($_POST["subject"]);
    $message = test_input($_POST["message"]);
    
    // If no errors, send email
    if (empty($nameErr) && empty($emailErr)) {
        $to = "dev@wandrbae.com";
        $email_subject = "Contact Form Submission: " . $subject;
        $email_body = "You have received a new message from your website contact form.\n\n" .
                      "Name: $name\n" .
                      "Email: $email\n" .
                      "Subject: $subject\n" .
                      "Message:\n$message\n";
        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";
        
        // Send email
        if (mail($to, $email_subject, $email_body, $headers)) {
            $formSubmitted = true;
            $_SESSION['message'] = 'Your message was sent successfully!';
        }
    }
}

// Function to sanitize form data
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<?php $page_title = 'Contact Us'; ?>

<main>

  <section>
    <h1>Contact Us</h1>
    <p>We would love to hear from you! Please feel free to drop us a line about this project or any suggestions for site improvement!</p>

    <?php if ($formSubmitted): ?>
        <div class="success-message">
            <p>Thank you for your message! We will get back to you soon.</p>
        </div>
    <?php else: ?>
        <?php if (!empty($nameErr) || !empty($emailErr)): ?>
            <div class="errors">
                <ul>
                    <?php if (!empty($nameErr)): ?><li><?php echo $nameErr; ?></li><?php endif; ?>
                    <?php if (!empty($emailErr)): ?><li><?php echo $emailErr; ?></li><?php endif; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?php echo url_for('/contact.php'); ?>" method="post">
            <dl>
                <dt>Your Name (required)</dt>
                <dd><input type="text" name="name" value="<?php echo $name; ?>" /></dd>
            </dl>

            <dl>
                <dt>Your Email (required)</dt>
                <dd><input type="email" name="email" value="<?php echo $email; ?>" /></dd>
            </dl>

            <dl>
                <dt>Subject</dt>
                <dd><input type="text" name="subject" value="<?php echo $subject; ?>" /></dd>
            </dl>

            <dl>
                <dt>Your Message</dt>
                <dd>
                    <textarea name="message" rows="5" cols="50"><?php echo $message; ?></textarea>
                </dd>
            </dl>

            <div id="operations">
                <input type="submit" class="btn" value="Send Message" />
            </div>
        </form>
    <?php endif; ?>
  </section
</main>

<?php include(SHARED_PATH . '/public_footer.php'); ?>