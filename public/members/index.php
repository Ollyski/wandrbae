<?php require_once('../../private/initialize.php'); ?>
<?php include(SHARED_PATH . '/member_header.php'); ?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" media="all" href="../stylesheets/members.css" />
  <title>Wandrbae </title>
</head>

<body>
  <ul>
    <li><a href="<?php echo url_for('/members/routes/index.php'); ?>">Routes</a></li>
  </ul>


  <?php include(SHARED_PATH . '/member_footer.php') ?>
</body>

</html>