<?php
  if(!isset($page_title)) { $page_title = 'WandrBae'; }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Wandrbae<?php echo h($page_title); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" media="all" href="<?php echo url_for('/stylesheets/public.css'); ?>" />
  </head>
  <body>
    <header id="page-header" role="banner" aria-label="document-header">
      <div>
        <h1>Wandrbae</h1>
        <p>Wander with us, bae.</p>
        <p>PUBLIC HEADER ACTIVE</p>
        <div class="header-buttons">
          <a href="#" class="btn">Join Us</a>
          <a href="#" class="btn">Log In</a>
        </div>
      </div>
    </header>
    <nav role="navigation">
      <ul>
        <li><a href="<?php echo url_for('/public/members/rides/index.php'); ?>">Rides</a></li>
        <li><a href="#">About</a></li>
        <li><a href="#">Join Us</a></li>
        <li><a href="#">Contact Us</a></li>
      </ul>
    </nav>
  