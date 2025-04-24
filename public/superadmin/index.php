<?php require_once('../../private/initialize.php'); ?>
<?php require_login(); 
include_header();?>
<?php

$user = User::find_all();

?>
<?php $page_title = 'Admins'; ?>
<?php include(SHARED_PATH . '/member_header.php'); ?>

<div id="main">
  <div>
    <section>
      <h1>Admins</h1>

      <div class="actions">
        <a class="action" href="<?php echo url_for('/admin/new.php'); ?>">Add Admin</a>
      </div>

      <table class="list">
        <tr>
          <th>User ID</th>
          <th>First name</th>
          <th>Last name</th>
          <th>Email</th>
          <th>Username</th>
          <th>&nbsp;</th>
          <th>&nbsp;</th>
          <th>&nbsp;</th>
        </tr>

        <?php foreach($user as $user) { ?>
          <tr>
            <td><?php echo h($user->user_id); ?></td>
            <td><?php echo h($user->first_name); ?></td>
            <td><?php echo h($user->last_name); ?></td>
            <td><?php echo h($user->email); ?></td>
            <td><?php echo h($user->username); ?></td>
            <td><a class="action" href="<?php echo url_for('/admin/show.php?id=' . h(u($user->user_id))); ?>">View</a></td>
            <td><a class="action" href="<?php echo url_for('/admin/edit.php?id=' . h(u($user->user_id))); ?>">Edit</a></td>
            <td><a class="action" href="<?php echo url_for('/admin/delete.php?id=' . h(u($user->user_id))); ?>">Delete</a></td>
          </tr>
        <?php } ?>
      </table>
    </section>
  </div>

</div>

<?php include(SHARED_PATH . '/public_footer.php'); ?>

