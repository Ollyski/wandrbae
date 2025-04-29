<?php
if (!isset($page_title)) {
  $page_title = 'WandrBae';
}
?>

<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <title>Wandrbae<?php echo h($page_title); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" media="all" href="<?php echo url_for('/stylesheets/public.css'); ?>" />
    <script src="<?php echo url_for('/js/route_map.js'); ?>"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  </head>

  <body>
    <header id="page-header" role="banner" aria-label="document-header">
      <div class="header-content">
        <div class="header-text">
          <h1>Wandrbae</h1>
          <img src="images/logo1.png" id="logo" alt="" width="75" height="75">
          <p>Wander with us, bae</p>
        </div>
        <div class="header-buttons">
          <?php if ($user_session->is_logged_in()) { 
            $user_id = $_SESSION['admin_id'] ?? null;
            $current_user = $user_id ? User::find_by_id($user_id) : null;
            $user_name = $current_user ? $current_user->full_name() : 'Bae';
          ?>
            <span>Welcome, <?php echo h($user_name); ?>!</span>
            <a href="<?php echo url_for('/members/index.php'); ?>" class="btn">Member Area</a>
            <a href="<?php echo url_for('/admin/logout.php'); ?>" class="btn">Log Out</a>
          <?php } else { ?>
            <a href="<?php echo url_for('/join.php'); ?>" class="btn">Join Us</a>
            <a href="<?php echo url_for('/admin/login.php'); ?>" class="btn">Log In</a>
          <?php } ?>
        </div>
      </div>
    </header>
    <nav role="navigation">
      <ul>
        <li><a href="<?php echo url_for('/index.php'); ?>">Home</a></li>
        <li><a href="<?php echo url_for('/ride.php'); ?>">Ride</a></li>
        <li><a href="<?php echo url_for('/about.php'); ?>">About</a></li>
        <?php if (!$user_session->is_logged_in()) { ?>
          <li><a href="<?php echo url_for('/join.php'); ?>">Join Us</a></li>
        <?php } ?>
        <li><a href="<?php echo url_for('/contact.php'); ?>">Contact Us</a></li>
      </ul>
    </nav>
  </body>
</html>