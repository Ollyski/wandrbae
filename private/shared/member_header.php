<!doctype html>

<html lang="en">

<head>
  <title>WandrBae<?php if (isset($page_title)) {
                    echo ' - ' . h($page_title);
                  } ?></title>
  <meta charset="utf-8">
  <link rel="stylesheet" media="all" href="<?php echo url_for('/stylesheets/members.css'); ?>" />
  <script src="<?php echo url_for('/js/route_map.js'); ?>"></script>
</head>

<body>
  <header id="page-header" role="banner" aria-label="document-header">
      <div class="header-content">
        <div class="header-text">
          <h1>Welcome Bae!</h1>
          <img src="<?php echo url_for('/images/test.png'); ?>" id="logo" alt="WandrBae logo" width="75" height="75">
          <p>Good to see you again!</p>
        </div>
        <div class="header-buttons">
          <?php if ($user_session->is_logged_in()) {
            $user_id = $_SESSION['user_id'] ?? null;
            $current_user = $user_id ? User::find_by_id($user_id) : null;
            $user_name = $current_user ? $current_user->full_name() : 'Bae';
          ?>
            <a href="<?php echo url_for('/admin/logout.php'); ?>" class="btn">Logout <?php echo h($user_name); ?></a>
          <?php } ?>
      </div>
    </header>
  <nav>
    <ul>
        <li><a href="<?php echo url_for('/index.php'); ?>">Home</a></li>
        <li><a href="<?php echo url_for('/members/routes/index.php'); ?>">Routes</a></li>
        <li><a href="<?php echo url_for('/ride.php'); ?>">Ride</a></li>
        <li><a href="<?php echo url_for('/about.php'); ?>">About</a></li>
        <?php if (!$user_session->is_logged_in()) { ?>
          <li><a href="<?php echo url_for('/join.php'); ?>">Join Us</a></li>
        <?php } ?>
        <li><a href="<?php echo url_for('/contact.php'); ?>">Contact Us</a></li>
      </ul>
    
    </nav>
 