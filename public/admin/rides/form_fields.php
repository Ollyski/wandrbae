<?php
if(!isset($ride)) {
  require_once('../../../private/initialize.php');
  $ride = new stdClass();
}
?>

<dl>
  <dt>Ride Name</dt>
  <dd><input type="text" name="ride_name" value="<?php echo h($ride->ride_name ?? ''); ?>" /></dd>
</dl>

<dl>
  <dt>Created By</dt>
  <dd>
    <select name="created_by">
      <option value=""></option>
      <?php
      // Get all users from database
      $result = find_all_users();
      
      while($user = mysqli_fetch_assoc($result)) {
      ?>
        <option value="<?php echo h($user['user_id']); ?>" 
            <?php if(($ride->created_by ?? '') == $user['user_id']) { echo 'selected'; } ?>>
          <?php echo h($user['username']); ?>
        </option>
      <?php } ?>
    </select>
  </dd>
</dl>

<dl>
  <dt>Start Time</dt>
  <dd>
    <input type="datetime-local" name="start_time" value="<?php echo h(isset($ride->start_time) ? date('Y-m-d\TH:i', strtotime($ride->start_time)) : ''); ?>" required />
  </dd>
</dl>

<dl>
  <dt>End Time</dt>
  <dd>
    <input type="datetime-local" name="end_time" value="<?php echo h(isset($ride->end_time) ? date('Y-m-d\TH:i', strtotime($ride->end_time)) : ''); ?>" required />
  </dd>
</dl>

<dl>
  <dt>Location</dt>
  <dd>
    <input type="text" name="location_name" value="<?php echo h($ride->location_name ?? ''); ?>">
  </dd>
</dl>

<dl>
  <dt>Street Address</dt>
  <dd><input type="text" name="street_address" value="<?php echo h($ride->street_address ?? ''); ?>" /></dd>
</dl>

<dl>
  <dt>City</dt>
  <dd>
    <input type="text" name="city" value="<?php echo h($ride->city ?? ''); ?>" />
  </dd>
</dl>

<dl>
  <dt>State</dt>
  <dd>
    <select name="state" required>
      <option value="">Select a state</option>
      <option value="AL" <?php if(($ride->state ?? '') == 'AL') { echo 'selected'; } ?>>Alabama</option>
      <option value="AK" <?php if(($ride->state ?? '') == 'AK') { echo 'selected'; } ?>>Alaska</option>
      <option value="AZ" <?php if(($ride->state ?? '') == 'AZ') { echo 'selected'; } ?>>Arizona</option>
      <option value="AR" <?php if(($ride->state ?? '') == 'AR') { echo 'selected'; } ?>>Arkansas</option>
      <option value="CA" <?php if(($ride->state ?? '') == 'CA') { echo 'selected'; } ?>>California</option>
      <option value="CO" <?php if(($ride->state ?? '') == 'CO') { echo 'selected'; } ?>>Colorado</option>
      <option value="CT" <?php if(($ride->state ?? '') == 'CT') { echo 'selected'; } ?>>Connecticut</option>
      <option value="DE" <?php if(($ride->state ?? '') == 'DE') { echo 'selected'; } ?>>Delaware</option>
      <option value="FL" <?php if(($ride->state ?? '') == 'FL') { echo 'selected'; } ?>>Florida</option>
      <option value="GA" <?php if(($ride->state ?? '') == 'GA') { echo 'selected'; } ?>>Georgia</option>
      <option value="HI" <?php if(($ride->state ?? '') == 'HI') { echo 'selected'; } ?>>Hawaii</option>
      <option value="ID" <?php if(($ride->state ?? '') == 'ID') { echo 'selected'; } ?>>Idaho</option>
      <option value="IL" <?php if(($ride->state ?? '') == 'IL') { echo 'selected'; } ?>>Illinois</option>
      <option value="IN" <?php if(($ride->state ?? '') == 'IN') { echo 'selected'; } ?>>Indiana</option>
      <option value="IA" <?php if(($ride->state ?? '') == 'IA') { echo 'selected'; } ?>>Iowa</option>
      <option value="KS" <?php if(($ride->state ?? '') == 'KS') { echo 'selected'; } ?>>Kansas</option>
      <option value="KY" <?php if(($ride->state ?? '') == 'KY') { echo 'selected'; } ?>>Kentucky</option>
      <option value="LA" <?php if(($ride->state ?? '') == 'LA') { echo 'selected'; } ?>>Louisiana</option>
      <option value="ME" <?php if(($ride->state ?? '') == 'ME') { echo 'selected'; } ?>>Maine</option>
      <option value="MD" <?php if(($ride->state ?? '') == 'MD') { echo 'selected'; } ?>>Maryland</option>
      <option value="MA" <?php if(($ride->state ?? '') == 'MA') { echo 'selected'; } ?>>Massachusetts</option>
      <option value="MI" <?php if(($ride->state ?? '') == 'MI') { echo 'selected'; } ?>>Michigan</option>
      <option value="MN" <?php if(($ride->state ?? '') == 'MN') { echo 'selected'; } ?>>Minnesota</option>
      <option value="MS" <?php if(($ride->state ?? '') == 'MS') { echo 'selected'; } ?>>Mississippi</option>
      <option value="MO" <?php if(($ride->state ?? '') == 'MO') { echo 'selected'; } ?>>Missouri</option>
      <option value="MT" <?php if(($ride->state ?? '') == 'MT') { echo 'selected'; } ?>>Montana</option>
      <option value="NE" <?php if(($ride->state ?? '') == 'NE') { echo 'selected'; } ?>>Nebraska</option>
      <option value="NV" <?php if(($ride->state ?? '') == 'NV') { echo 'selected'; } ?>>Nevada</option>
      <option value="NH" <?php if(($ride->state ?? '') == 'NH') { echo 'selected'; } ?>>New Hampshire</option>
      <option value="NJ" <?php if(($ride->state ?? '') == 'NJ') { echo 'selected'; } ?>>New Jersey</option>
      <option value="NM" <?php if(($ride->state ?? '') == 'NM') { echo 'selected'; } ?>>New Mexico</option>
      <option value="NY" <?php if(($ride->state ?? '') == 'NY') { echo 'selected'; } ?>>New York</option>
      <option value="NC" <?php if(($ride->state ?? '') == 'NC') { echo 'selected'; } ?>>North Carolina</option>
      <option value="ND" <?php if(($ride->state ?? '') == 'ND') { echo 'selected'; } ?>>North Dakota</option>
      <option value="OH" <?php if(($ride->state ?? '') == 'OH') { echo 'selected'; } ?>>Ohio</option>
      <option value="OK" <?php if(($ride->state ?? '') == 'OK') { echo 'selected'; } ?>>Oklahoma</option>
      <option value="OR" <?php if(($ride->state ?? '') == 'OR') { echo 'selected'; } ?>>Oregon</option>
      <option value="PA" <?php if(($ride->state ?? '') == 'PA') { echo 'selected'; } ?>>Pennsylvania</option>
      <option value="RI" <?php if(($ride->state ?? '') == 'RI') { echo 'selected'; } ?>>Rhode Island</option>
      <option value="SC" <?php if(($ride->state ?? '') == 'SC') { echo 'selected'; } ?>>South Carolina</option>
      <option value="SD" <?php if(($ride->state ?? '') == 'SD') { echo 'selected'; } ?>>South Dakota</option>
      <option value="TN" <?php if(($ride->state ?? '') == 'TN') { echo 'selected'; } ?>>Tennessee</option>
      <option value="TX" <?php if(($ride->state ?? '') == 'TX') { echo 'selected'; } ?>>Texas</option>
      <option value="UT" <?php if(($ride->state ?? '') == 'UT') { echo 'selected'; } ?>>Utah</option>
      <option value="VT" <?php if(($ride->state ?? '') == 'VT') { echo 'selected'; } ?>>Vermont</option>
      <option value="VA" <?php if(($ride->state ?? '') == 'VA') { echo 'selected'; } ?>>Virginia</option>
      <option value="WA" <?php if(($ride->state ?? '') == 'WA') { echo 'selected'; } ?>>Washington</option>
      <option value="WV" <?php if(($ride->state ?? '') == 'WV') { echo 'selected'; } ?>>West Virginia</option>
      <option value="WI" <?php if(($ride->state ?? '') == 'WI') { echo 'selected'; } ?>>Wisconsin</option>
      <option value="WY" <?php if(($ride->state ?? '') == 'WY') { echo 'selected'; } ?>>Wyoming</option>
    </select>
  </dd>
</dl>

<dl>
  <dt>Zip Code</dt>
  <dd>
    <input type="text" name="zip_code" value="<?php echo h($ride->zip_code ?? ''); ?>" />
  </dd>
</dl>