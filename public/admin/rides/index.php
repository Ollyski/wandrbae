<?php require_once('../../../private/initialize.php'); ?>
<?php require_admin_login(); 
include_header(); ?>
<?php

$rides = Ride::find_all();

?>
<?php $page_title = 'Ride List'; ?>

<div id="main">
  <div class="userlist">
    <section>
      <h1>Ride List</h1>

      <div class="actions">
        <a class="action" href="<?php echo url_for('/admin/rides/new.php'); ?>">Add Ride</a>
      </div>

      <table class="container">
        <thead>
          <tr>
            <th><h1>Ride ID</h1></th>
            <th><h1>Ride Name</h1></th>
            <th><h1>Created By</h1></th>
            <th><h1>Starts At</h1></th>
            <th><h1>Ends At</h1></th>
            <th><h1>Where</h1></th>
            <th><h1>Location</h1></th>
            <th><h1>Actions</h1></th>
          </tr>
        </thead>
        <tbody>
          <?php if(empty($rides)) { ?>
            <tr>
              <td colspan="8">No rides found.</td>
            </tr>
          <?php } else { ?>
            <?php foreach($rides as $ride) { ?>
              <tr>
                <td><?php echo h($ride->ride_id ?? ''); ?></td>
                <td><?php echo h($ride->ride_name ?? ''); ?></td>
                <td><?php echo h($ride->username ?? ''); ?></td>
                <td><?php echo h($ride->start_time ?? ''); ?></td>
                <td><?php echo h($ride->end_time ?? ''); ?></td>
                <td><?php echo h($ride->location_name ?? ''); ?></td>
                <td><?php echo h($ride->street_address ?? ''); ?></td>
                <td>
                  <a class="table-action" href="<?php echo url_for('/admin/rides/detail.php?id=' . h(u($ride->ride_id ?? ''))); ?>">View</a>
                  <a class="table-action" href="<?php echo url_for('/admin/rides/edit.php?id=' . h(u($ride->ride_id ?? ''))); ?>">Edit</a>
                  <a class="table-action" href="<?php echo url_for('/admin/rides/delete.php?id=' . h(u($ride->ride_id ?? ''))); ?>">Delete</a>
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