<!doctype html>

<html lang="en">
  <head>
    <title>WandrBae</title>
    <meta charset="utf-8">
    <link rel="stylesheet" media="all" href="../stylesheets/members.css" />
    <script src="<?php echo url_for('/js/route_map.js'); ?>"></script>
    
  </head>

  <body>
    <header>
      <h1>Welcome Bae!</h1>
    </header>

    <navigation>
      <ul>
        <?php if($session->is_logged_in()) { ?>
        <li><a href="<?php echo url_for('/member/index.php); ?>">index.php">Menu</a></li>
        <li><a href="<?php echo url_for('/member/logout.php); ?>">index.php">Menu</a></li>
        <?php } ?>
      </ul>
    </navigation>
