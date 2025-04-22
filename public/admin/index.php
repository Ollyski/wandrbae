<?php require_once('../../private/initialize.php'); ?>
<?php require_admin_login(); ?>
<?php

$user = User::find_all();

?>
<?php $page_title = 'Admins'; ?>
<?php include(SHARED_PATH . '/member_header.php'); ?>

<div id="main">
  <div>
    <section>
      <h1>Admin Bae!</h1>
      <p>What would you like to see?</p>

      <a href="<?php echo url_for('/admin/userlist.php'); ?>">User List</a>

      <a href="<?php echo url_for('/admin/userlist.php'); ?>">Active Rides</a>

      <a href="<?php echo url_for('/admin/userlist.php'); ?>">Ride History</a>
    
    </section>
  </div>

</div>

<?php include(SHARED_PATH . '/public_footer.php'); ?>
