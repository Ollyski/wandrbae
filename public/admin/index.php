<?php require_once('../../private/initialize.php'); ?>
<?php require_admin_login(); 
include_header(); ?>
<?php

$user = User::find_all();

?>
<?php $page_title = 'Admins'; ?>

<div id="main">
  <div>
    <section>
      <h1>Hello Route Scout!</h1>
      <p>What would you like to see?</p>

      <a class="btn" href="<?php echo url_for('/admin/userlist.php'); ?>">User List</a>

      <a class="btn" href="<?php echo url_for('/admin/rides/index.php'); ?>">Active Rides</a>
    
    </section>
  </div>

</div>

<?php include(SHARED_PATH . '/public_footer.php'); ?>
