<?php require_once('../../../private/initialize.php'); ?>
<?php require_login(); ?>
<?php include(SHARED_PATH . '/member_header.php'); ?>

<div id="main">
  <div id="page">
    <div class="intro">
      <h2>Available Rides</h2>
      <p>Sign up for a ride!</p>
    </div>
    
    <?php
    // For admin view, pass isAdmin=true
    $is_admin = current_user() && (current_user()->isAdmin() || current_user()->isSuperAdmin());
    include_react_component('RideCards', [
      'isAdmin' => $is_admin,
      'apiEndpoint' => url_for('/api/rides.php')
    ]);
    ?>
    
  </div>
</div>

<?php include(SHARED_PATH . '/member_footer.php'); ?>