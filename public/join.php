<?php
require_once('../private/initialize.php');
include_header();
if(is_post_request()) {
  
  // Verify Turnstile token
  $token = $_POST['cf-turnstile-response'] ?? '';
  $secret_key = '0x4AAAAAABVONKt3qXcbUyVRFLbmp7h7kmI';
  
  $data = [
      'secret' => $secret_key,
      'response' => $token
  ];
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'https://challenges.cloudflare.com/turnstile/v0/siteverify');
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $result = curl_exec($ch);
  curl_close($ch);
  
  $result_json = json_decode($result, true);
  
  // Only proceed if Turnstile verification passed
  if (isset($result_json['success']) && $result_json['success'] === true) {
    $args = $_POST['user'];
    
    $args['user_role_id'] = 1; 
    $args['is_user'] = 1;    
    
    // Create the Member object
    $user = new User($args);
    
    // Debug - Uncomment these lines if you want to see what's happening
    // echo "<pre>";
    // echo "Member properties before save:\n";
    // print_r($user);
    // echo "</pre>";
    // exit();
    
    $result = $user->save();
  
    if($result === true) {
      $new_id = $user->user_id;
      // Redirect to show page
      $_SESSION['message'] = 'Your account was created successfully!';
      redirect_to(url_for('/show.php?id=' . $new_id));
    }
  } else {
    // Turnstile verification failed
    $args = $_POST['user'] ?? [];
    $user = new User($args);
    $user->errors[] = "Human verification failed. Please try again.";
  }

} else {
  // Display the blank form
  $user = new User;
}
?>

<?php $page_title = 'Join Wandrbae'; ?>

<main role="main">
  <section>
    <div class="user new">
      <h1>Join Wandrbae</h1>
      <p>Create an account to join our community!</p>

      <?php echo display_errors($user->errors); ?>

      <form action="<?php echo url_for('/join.php'); ?>" method="post">

        <?php include('user_form_fields.php'); ?>
        
        <!-- Cloudflare Turnstile CAPTCHA -->
        <div class="cf-turnstile" data-sitekey="0x4AAAAAABVONCywlGcDFwyW"></div>

        <div id="operations">
          <input type="submit" class="btn" value="Sign Up" />
        </div>
      </form>

    </div>
  </section>
</main>

<?php include(SHARED_PATH . '/public_footer.php'); ?>