<?php require_once('../../private/initialize.php'); ?>
<?php require_login(); ?>
<?php

// Using the correct column name role_name directly from the user table
$db = db_connect();
$sql = "SELECT * FROM user ORDER BY user_id ASC";
$result = mysqli_query($db, $sql);
$users = [];
if($result) {
  while($user = mysqli_fetch_assoc($result)) {
    $users[] = $user;
  }
  mysqli_free_result($result);
}

?>
<?php $page_title = 'User List'; ?>
<?php include(SHARED_PATH . '/member_header.php'); ?>

<div id="main">
  <div>
    <section>
      <h1>User List</h1>

      <div class="actions">
        <a class="action" href="<?php echo url_for('/admin/new_user.php'); ?>">Add User</a>
      </div>

      <table class="list">
        <tr>
          <th>User ID</th>
          <th>First name</th>
          <th>Last name</th>
          <th>Email</th>
          <th>Username</th>
          <th>Role</th>
          <th>&nbsp;</th>
          <th>&nbsp;</th>
          <th>&nbsp;</th>
        </tr>

        <?php if(empty($users)) { ?>
          <tr>
            <td colspan="9">No users found.</td>
          </tr>
        <?php } else { ?>
          <?php foreach($users as $user) { ?>
            <tr>
              <td><?php echo h($user['user_id'] ?? ''); ?></td>
              <td><?php echo h($user['first_name'] ?? ''); ?></td>
              <td><?php echo h($user['last_name'] ?? ''); ?></td>
              <td><?php echo h($user['email'] ?? ''); ?></td>
              <td><?php echo h($user['username'] ?? ''); ?></td>
              <td><?php echo h($user['role_name'] ?? ''); ?></td>
              <td><a class="action" href="<?php echo url_for('/admin/show_user.php?id=' . h(u($user['user_id'] ?? ''))); ?>">View</a></td>
              <td><a class="action" href="<?php echo url_for('/admin/edit_user.php?id=' . h(u($user['user_id'] ?? ''))); ?>">Edit</a></td>
              <td><a class="action" href="<?php echo url_for('/admin/delete_user.php?id=' . h(u($user['user_id'] ?? ''))); ?>">Delete</a></td>
            </tr>
          <?php } ?>
        <?php } ?>
      </table>
    </section>
  </div>

</div>

<?php include(SHARED_PATH . '/public_footer.php'); ?>