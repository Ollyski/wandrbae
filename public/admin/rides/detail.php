<?php require_once('../../../private/initialize.php'); 
require_admin_login();
include_header();

if(!isset($_GET['id'])) {
  redirect_to(url_for('/admin/rides/index.php'));
}
$id = $_GET['id'];

// Get the specific ride
$ride = Ride::find_by_id($id);
if($ride == false) {
  redirect_to(url_for('/admin/rides/index.php'));
}

$page_title = 'View Ride: ' . h($ride->ride_name);
?>

<div id="main">
  <div id="page">
    <div>
      <a class="btn" href="<?php echo url_for('/admin/rides/index.php'); ?>">&laquo; Back to List</a>
    </div>
    
    <div class="ride detail">
      <h1>Ride Details: <?php echo h($ride->ride_name); ?></h1>
      
      <div class="attributes">
        <dl>
          <dt>Name</dt>
          <dd><?php echo h($ride->ride_name); ?></dd>
        </dl>
        <dl>
          <dt>Created By</dt>
          <dd><?php echo h($ride->username); ?></dd>
        </dl>
        <dl>
          <dt>Start Time</dt>
          <dd><?php echo h($ride->start_time); ?></dd>
        </dl>
        <dl>
          <dt>End Time</dt>
          <dd><?php echo h($ride->end_time); ?></dd>
        </dl>
        <dl>
          <dt>Location Name</dt>
          <dd><?php echo h($ride->location_name); ?></dd>
        </dl>
        <dl>
          <dt>Address</dt>
          <dd>
            <?php echo h($ride->street_address); ?><br>
            <?php echo h($ride->city); ?>, <?php echo h($ride->state); ?> <?php echo h($ride->zip_code); ?>
          </dd>
        </dl>
      </div>
      
      <div id="operations">
        <a class="btn" href="<?php echo url_for('/admin/rides/edit.php?id=' . h(u($ride->ride_id))); ?>">Edit</a>
        <a class="btn" href="<?php echo url_for('/admin/rides/delete.php?id=' . h(u($ride->ride_id))); ?>">Delete</a>
      </div>
    </div>
  </div>
</div>

<?php include(SHARED_PATH . '/public_footer.php'); ?>