<?php
require_once('../private/initialize.php');

$id = $_GET['id'] ?? '';

// If no ID is provided, redirect to the home page
if(empty($id)) {
  redirect_to(url_for('/index.php'));
}

$member = User::find_by_id($id);

if(!$member) {
  redirect_to(url_for('/index.php'));
}

?>

<?php $page_title = 'Welcome to Wandrbae!'; ?>
<?php include(SHARED_PATH . '/public_header.php'); ?>

<div id="content">
  <div class="member show">
    <h1>Welcome to Wandrbae, <?php echo h($member->first_name); ?>!</h1>
    
    <?php 
    if(isset($_SESSION['message'])) {
      echo "<div class=\"message success\">" . h($_SESSION['message']) . "</div>";
      unset($_SESSION['message']);
    }
    ?>
    
    <div class="attributes">
      <h2>Your Account Details</h2>
      <p>Your account has been successfully created. Here's a summary of your information:</p>
      
      <dl>
        <dt>Name</dt>
        <dd><?php echo h($member->full_name()); ?></dd>
      </dl>
      
      <dl>
        <dt>Username</dt>
        <dd><?php echo h($member->username); ?></dd>
      </dl>
      
      <dl>
        <dt>Email</dt>
        <dd><?php echo h($member->email); ?></dd>
      </dl>
    </div>
    
    <div class="next-steps">
      <h2>Next Steps</h2>
      <p>You're all set! Here's what you can do now:</p>
      
      <ul>
        <li><a href="<?php echo url_for('/login.php'); ?>">Log in to your account</a></li>
        <li><a href="<?php echo url_for('/index.php'); ?>">Explore our routes</a></li>
        <li><a href="<?php echo url_for('/about.php'); ?>">Learn more about Wandrbae</a></li>
      </ul>
    </div>
  </div>
</div>

<?php include(SHARED_PATH . '/public_footer.php'); ?>