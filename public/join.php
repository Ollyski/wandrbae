<?php
require_once('../private/initialize.php');
include_header();
if(is_post_request()) {
  
  // Verify reCAPTCHA
  $recaptcha_success = false;
  if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
    // Secret key from Google reCAPTCHA admin
    $secret = '6LfcSCgrAAAAAGJmXjO2PgarTEmfRN2J07gYRmnq';
    
    $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
    
    $responseData = json_decode($verifyResponse);
    
    if($responseData->success) {
      $recaptcha_success = true;
    }
  }

  if($recaptcha_success) {
    $args = $_POST['user'];
    
    $args['user_role_id'] = 1; 
    $args['is_user'] = 1;    
    
    // Create the Member object
    $user = new User($args);
    
    $result = $user->save();
  
    if($result === true) {
      $new_id = $user->user_id;
      $_SESSION['message'] = 'Your account was created successfully!';
      redirect_to(url_for('/show.php?id=' . $new_id));
    }
  } else {
    // reCAPTCHA verification failed
    if(isset($_POST['g-recaptcha-response'])) {
      $user = new User($_POST['user']);
      $user->errors[] = "Please verify that you are not a robot.";
    }
  }

} else {
  $user = new User;
}
?>

<?php $page_title = 'Join Wandrbae'; ?>

<main role="main">
  <section class="intro">
    <div class="user new">
      <h2>Join Wandrbae</h2>
      <p>Create an account to join our community!</p>
      <small> Fields marked with an * are required</small>

      <?php echo display_errors($user->errors); ?>

      <form action="<?php echo url_for('/join.php'); ?>" method="post">

        <?php include('user_form_fields.php'); ?>
        
        <div class="g-recaptcha" data-sitekey="6LfcSCgrAAAAANTVSaKeZA3KyoQIp53pCLAsE8T9"></div>
        <br>

        <div id="operations">
          <input type="submit" class="btn" value="Sign Up" />
        </div>
      </form>

    </div>
</section>
</main>

<?php include(SHARED_PATH . '/public_footer.php'); ?>