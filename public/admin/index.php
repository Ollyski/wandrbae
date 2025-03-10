<?php require_once('../../private/initialize.php'); ?>

<?php

$admin = Admin::find_all();

?>
<?php $page_title = 'Admins'; ?>
<?php include(SHARED_PATH . '/member_header.php'); ?>

<div>
  <div>
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

      <?php foreach($admin as $admin) { ?>
        <tr>
          <td><?php echo h($admin->user_id); ?></td>
          <td><?php echo h($admin->first_name); ?></td>
          <td><?php echo h($admin->last_name); ?></td>
          <td><?php echo h($admin->email); ?></td>
          <td><?php echo h($admin->username); ?></td>
          <td><a class="action" href="<?php echo url_for('/admin/show.php?id=' . h(u($admin->id))); ?>">View</a></td>
          <td><a class="action" href="<?php echo url_for('/admin/edit.php?id=' . h(u($admin->id))); ?>">Edit</a></td>
          <td><a class="action" href="<?php echo url_for('/admin/delete.php?id=' . h(u($admin->id))); ?>">Delete</a></td>
    	  </tr>
      <?php } ?>
  	</table>

  </div>

</div>

<?php include(SHARED_PATH . '/member_footer.php'); ?>
