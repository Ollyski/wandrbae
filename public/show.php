<?php
require_once('../private/initialize.php');
include_header();

$id = $_GET['id'] ?? '';

// If no ID is provided, redirect to the home page
if (empty($id)) {
  redirect_to(url_for('/index.php'));
}

$member = User::find_by_id($id);

if (!$member) {
  redirect_to(url_for('/index.php'));
}

?>

<?php $page_title = 'Welcome to Wandrbae!'; ?>

<main>
  <section>
    <?php
    if (isset($_SESSION['message'])) {
      echo "<div class=\"success-message\">" . h($_SESSION['message']) . "</div>";
      unset($_SESSION['message']);
    }
    ?>

    <div class="intro">
      <h2>Welcome to Wandrbae, <?php echo h($member->first_name); ?>!</h2>
      <p>Your account has been successfully created. Here's a summary of your information:</p>
    </div>

    <div class="rides-container">
      <div class="ride-card">
        <div class="ride-header">
          <h3 class="ride-name">Account Information</h3>
        </div>
        <div class="ride-info">
          <dl>
            <dt>Name</dt>
            <dd><?php echo h($member->full_name()); ?></dd>
          </dl>

          <dl>
            <dt>Username</dt>
            <dd><?php echo h($member->username); ?></dd>
          </dl>

          <dl>
            <dt>Email</dt>
            <dd><?php echo h($member->email); ?></dd>
          </dl>
        </div>
      </div>
    </div>

    <div class="intro" style="margin-top: 2rem;">
      <h2>Next Steps</h2>
      <p>You're all set! Here's what you can do now:</p>
    </div>

    <div class="rides-container">
      <div class="ride-card">
        <div class="ride-header">
          <h3 class="ride-name">Log In</h3>
        </div>
        <div class="ride-info">
          <p>Access your new account and start exploring Wandrbae!</p>
        </div>
        <div class="ride-footer">
          <a href="<?php echo url_for('/login.php'); ?>" class="btn">Log In Now</a>
        </div>
      </div>

      <div class="ride-card">
        <div class="ride-header">
          <h3 class="ride-name">Explore Routes</h3>
        </div>
        <div class="ride-info">
          <p>Discover exciting routes and adventures waiting for you!</p>
        </div>
        <div class="ride-footer">
          <a href="<?php echo url_for('/index.php'); ?>" class="btn">Browse Routes</a>
        </div>
      </div>

      <div class="ride-card">
        <div class="ride-header">
          <h3 class="ride-name">About Wandrbae</h3>
        </div>
        <div class="ride-info">
          <p>Learn more about our platform and community!</p>
        </div>
        <div class="ride-footer">
          <a href="<?php echo url_for('/about.php'); ?>" class="btn">Learn More</a>
        </div>
      </div>
    </div>
    
  </section>
</main>

<?php include(SHARED_PATH . '/public_footer.php'); ?>