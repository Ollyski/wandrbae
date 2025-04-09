<!doctype html>

<html lang="en">

<head>
  <title>WandrBae<?php if (isset($page_title)) {
                    echo ' - ' . h($page_title);
                  } ?></title>
  <meta charset="utf-8">
  <link rel="stylesheet" media="all" href="../stylesheets/members.css" />
  <link rel="stylesheet" media="all" href="../stylesheets/ride-cards.css" />
  <script src="<?php echo url_for('/js/route_map.js'); ?>"></script>
</head>

<body>
  <header>
    <h1>Welcome Bae!</h1>
    <p>Good to see you again. Here are some upcoming rides!</p>
  </header>

  <navigation>
    <ul>
      <?php if ($session->is_logged_in()) {
        $user_id = $_SESSION['admin_id'] ?? null;
        $current_user = $user_id ? Admin::find_by_id($user_id) : null;
        $user_name = $current_user ? $current_user->full_name() : 'Bae';
      ?>
        <li><a href="<?php echo url_for('/admin/logout.php'); ?>" class="logout-button">Logout <?php echo h($user_name); ?></a></li>
      <?php } ?>
    </ul>
    <ul>
        <li><a href="<?php echo url_for('/index.php'); ?>">Home</a></li>
        <li><a href="<?php echo url_for('/members/routes/index.php'); ?>">Routes</a></li>
        <li><a href="<?php echo url_for('/ride.php'); ?>">Ride</a></li>
        <li><a href="<?php echo url_for('/about.php'); ?>">About</a></li>
        <?php if (!$session->is_logged_in()) { ?>
          <li><a href="<?php echo url_for('/join.php'); ?>">Join Us</a></li>
        <?php } ?>
        <li><a href="<?php echo url_for('/contact.php'); ?>">Contact Us</a></li>
      </ul>
    
  </navigation>
</body>

</html>