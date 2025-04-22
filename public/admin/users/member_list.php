<?php require_once('../../private/initialize.php'); 
require_admin_login();?>
<?php $user_set = find_all_users(); ?>

<?php include(SHARED_PATH . '/member_header.php'); ?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" media="all" href="../stylesheets/members.css" />
  <title>Wandrbae Members</title>
</head>
<body>
  <div id="content">
    <div class="members listing">
      <h1>Members</h1>
      
      <table class="list">
        <tr>
          <th>Username</th>
          <th>Name</th>
          <th>Email</th>
          <th>&nbsp;</th>
        </tr>
        
        <?php while($user = mysqli_fetch_assoc($user_set)) { ?>
          <tr>
            <td><?php echo $user['username']; ?></td>
            <td><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></td>
            <td><?php echo $user['email']; ?></td>
            <td><a href="show.php?id=<?php echo $user['user_id']; ?>">View</a></td>
          </tr>
        <?php } ?>
      </table>
      
      <?php mysqli_free_result($user_set); ?>
    </div>
  </div>
  
  <?php include(SHARED_PATH . '/public_footer.php')?>
</body>
</html>