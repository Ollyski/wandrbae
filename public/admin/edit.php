<?php

require_once('../../private/initialize.php');


if(!isset($_GET['id'])) {
  redirect_to(url_for('/admin/index.php'));
}
$id = $_GET['id'];
$admin = Admin::find_by_id($id);
if($admin == false) {
  redirect_to(url_for('/admin/index.php'));
}

if(is_post_request()) {

  $args = $_POST['admin'];
  $admin->merge_attributes($args);
  $result = $admin->save();

  if($result === true) {
    $_SESSION['message'] = 'The admin was updated successfully.';
    redirect_to(url_for('/admin/show.php?id=' . $id));
  } else {
  
  }

} else {

}

?>

<?php $page_title = 'Edit Admin'; ?>
<?php include(SHARED_PATH . '/member_header.php'); ?>

<div id="content">

  <a class="back-link" href="<?php echo url_for('/admin/index.php'); ?>">&laquo; Back to List</a>

  <div>
    <h1>Edit Admin</h1>

    <?php echo display_errors($admin->errors); ?>

    <form action="<?php echo url_for('/admin/edit.php?id=' . h(u($id))); ?>" method="post">

      <?php include('form_fields.php'); ?>

      <div id="operations">
        <input type="submit" value="Edit Admin" />
      </div>
    </form>

  </div>

</div>

<?php include(SHARED_PATH . '/member_footer.php'); ?>
