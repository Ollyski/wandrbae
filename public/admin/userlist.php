<?php require_once('../../private/initialize.php'); ?>
<?php require_admin_login(); ?>
<?php

$users = User::find_all();

?>
<?php $page_title = 'User List'; ?>
<?php include(SHARED_PATH . '/member_header.php'); ?>

<div id="main">
  <div class="userlist">
    <section>
      <h1>User List</h1>

      <div class="actions">
        <a class="action" href="<?php echo url_for('/admin/new_user.php'); ?>">Add User</a>
      </div>

      <table class="container">
        <thead>
          <tr>
            <th><h1>User ID</h1></th>
            <th><h1>First name</h1></th>
            <th><h1>Last name</h1></th>
            <th><h1>Email</h1></th>
            <th><h1>Username</h1></th>
            <th><h1>Role</h1></th>
            <th><h1>Actions</h1></th>
          </tr>
        </thead>
        <tbody>
          <?php if(empty($users)) { ?>
            <tr>
              <td colspan="7">No users found.</td>
            </tr>
          <?php } else { ?>
            <?php foreach($users as $user) { ?>
              <tr>
                <td><?php echo h($user->user_id ?? ''); ?></td>
                <td><?php echo h($user->first_name ?? ''); ?></td>
                <td><?php echo h($user->last_name ?? ''); ?></td>
                <td><?php echo h($user->email ?? ''); ?></td>
                <td><?php echo h($user->username ?? ''); ?></td>
                <td><?php echo h($user->get_role_name() ?? ''); ?></td>
                <td>
                  <a class="table-action" href="<?php echo url_for('/admin/show_user.php?id=' . h(u($user->user_id ?? ''))); ?>">View</a>
                  <a class="table-action" href="<?php echo url_for('/admin/edit_user.php?id=' . h(u($user->user_id ?? ''))); ?>">Edit</a>
                  <a class="table-action" href="<?php echo url_for('/admin/delete_user.php?id=' . h(u($user->user_id ?? ''))); ?>">Delete</a>
                </td>
              </tr>
            <?php } ?>
          <?php } ?>
        </tbody>
      </table>
    </section>
  </div>
</div>

<?php include(SHARED_PATH . '/public_footer.php'); ?>