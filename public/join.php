<?php
require_once('../private/initialize.php');

if(is_post_request()) {
  
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
  } else {
    
  }

} else {
  // Display the blank form
  $user = new User;
}
?>

<?php $page_title = 'Join Wandrbae'; ?>
<?php include(SHARED_PATH . '/public_header.php'); ?>

<div id="content">

  <div class="user new">
    <h1>Join Wandrbae</h1>
    <p>Create an account to join our community!</p>

    <?php echo display_errors($user->errors); ?>

    <form action="<?php echo url_for('/join.php'); ?>" method="post">

      <?php include('user_form_fields.php'); ?>

      <div id="operations">
        <input type="submit" value="Sign Up" />
      </div>
    </form>

  </div>

</div>

<?php include(SHARED_PATH . '/public_footer.php'); ?>