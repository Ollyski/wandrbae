<?php
require_once('../private/initialize.php');
include_header();

$nameErr = $emailErr = $recaptchaErr = "";
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
    
    // Verify reCAPTCHA
    $recaptcha_success = false;
    if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
        $secret = '6LfcSCgrAAAAAGJmXjO2PgarTEmfRN2J07gYRmnq';
        
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
        
        $responseData = json_decode($verifyResponse);
        
        if($responseData->success) {
            $recaptcha_success = true;
        } else {
            $recaptchaErr = "Please verify that you are not a robot";
        }
    } else {
        $recaptchaErr = "Please verify that you are not a robot";
    }
    
    $subject = test_input($_POST["subject"]);
    $message = test_input($_POST["message"]);
    
    if (empty($nameErr) && empty($emailErr) && empty($recaptchaErr)) {
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

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<?php $page_title = 'Contact Us'; ?>

<main>

  <section class="intro">
    <h2>Contact Us</h2>
    <p>We would love to hear from you! Please feel free to drop us a line about this project or any suggestions for site improvement!</p>
    <small>Fields marked with an * are required</small>

    <?php if ($formSubmitted): ?>
        <div class="success-message">
            <p>Thank you for your message! We will get back to you soon.</p>
        </div>
    <?php else: ?>
        <?php if (!empty($nameErr) || !empty($emailErr) || !empty($recaptchaErr)): ?>
            <div class="errors">
                <ul>
                    <?php if (!empty($nameErr)): ?><li><?php echo $nameErr; ?></li><?php endif; ?>
                    <?php if (!empty($emailErr)): ?><li><?php echo $emailErr; ?></li><?php endif; ?>
                    <?php if (!empty($recaptchaErr)): ?><li><?php echo $recaptchaErr; ?></li><?php endif; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?php echo url_for('/contact.php'); ?>" method="post">
            <dl>
                <dt>Your Name*</dt>
                <dd><input type="text" name="name" value="<?php echo $name; ?>" /></dd>
            </dl>

            <dl>
                <dt>Your Email*</dt>
                <dd><input type="email" name="email" value="<?php echo $email; ?>" /></dd>
            </dl>

            <dl>
                <dt>Subject*</dt>
                <dd><input type="text" name="subject" value="<?php echo $subject; ?>" /></dd>
            </dl>

            <dl>
                <dt>Your Message*</dt>
                <dd>
                    <textarea name="message" rows="5" cols="50"><?php echo $message; ?></textarea>
                </dd>
            </dl>
            
            <div class="g-recaptcha" data-sitekey="6LfcSCgrAAAAANTVSaKeZA3KyoQIp53pCLAsE8T9"></div>
            <br>

            <div id="operations">
                <input type="submit" class="btn" value="Send Message" />
            </div>
        </form>
    <?php endif; ?>
  </section>
</main>

<?php include(SHARED_PATH . '/public_footer.php'); ?>