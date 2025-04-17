<?php
require_once('../private/initialize.php');

if(is_post_request()) {
  
  $args = $_POST['member'];
  
  // Explicitly set role and member status to match your database
  $args['user_role_id'] = 1; // Member role
  $args['is_member'] = 1;    // This is a member account
  
  // Create the Member object
  $member = new Member($args);
  
  // Debug - Uncomment these lines if you want to see what's happening
  // echo "<pre>";
  // echo "Member properties before save:\n";
  // print_r($member);
  // echo "</pre>";
  // exit();
  
  $result = $member->save();

  if($result === true) {
    $new_id = $member->user_id;
    // Redirect to show page
    $_SESSION['message'] = 'Your account was created successfully!';
    redirect_to(url_for('/show.php?id=' . $new_id));
  } else {
    // Form data has errors; validation errors will be shown below
  }

} else {
  // Display the blank form
  $member = new Member;
}
?>

<?php $page_title = 'Join Wandrbae'; ?>
<?php include(SHARED_PATH . '/public_header.php'); ?>

<div id="content">

  <div class="member new">
    <h1>Join Wandrbae</h1>
    <p>Create an account to join our community!</p>

    <?php echo display_errors($member->errors); ?>

    <form action="<?php echo url_for('/join.php'); ?>" method="post">

      <?php include('member_form_fields.php'); ?>

      <div id="operations">
        <input type="submit" value="Sign Up" />
      </div>
    </form>

  </div>

</div>

<?php include(SHARED_PATH . '/public_footer.php'); ?>