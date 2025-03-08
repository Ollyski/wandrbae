<?php
if(!isset($ride)) {
  redirect_to(url_for('/members/rides/index.php'));
}
?>

<dl>
  <dt>Ride Name</dt>
  <dd><input type="text" name="ride_name" value="<?php echo h($ride->ride_name ?? ''); ?>" /></dd>
</dl>

<dl>
  <dt>Created By</dt>
  <dd>
    <select name="username">
      <option value=""></option>
      <?php 
      // user retrieval method
      $users = []; // Replace with actual user data retrieval
      foreach($users as $user) { 
      ?>
        <option value="<?php echo h($user->username); ?>" <?php if(($ride->username ?? '') == $user->username) { echo 'selected'; } ?>><?php echo h($user->username); ?></option>
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
    <select name="location_name">
      <option value=""></option>
      <?php 
      // Make sure Ride::LOCATION is defined in Ride class
      if(defined('Ride::LOCATION')) {
        foreach(Ride::LOCATION as $location_value => $location_name) { 
      ?>
        <option value="<?php echo h($location_value); ?>" <?php if(($ride->location_name ?? '') == $location_value) { echo 'selected'; } ?>><?php echo h($location_name); ?></option>
      <?php 
        }
      }
      ?>
    </select>
  </dd>
</dl>

<dl>
  <dt>Street Address</dt>
  <dd><input type="text" name="street_address" value="<?php echo h($ride->street_address ?? ''); ?>" /></dd>
</dl>

<dl>
  <dt>City</dt>
  <dd><input type="text" name="city" value="<?php echo h($ride->city ?? ''); ?>" /></dd>
</dl>

<dl>
  <dt>State</dt>
  <dd>
    <select name="state">
      <option value=""></option>
      <?php
      $states = [
        'AL' => 'Alabama', 'AK' => 'Alaska', 'AZ' => 'Arizona', 'AR' => 'Arkansas',
        'CA' => 'California', 'CO' => 'Colorado', 'CT' => 'Connecticut', 'DE' => 'Delaware',
        'FL' => 'Florida', 'GA' => 'Georgia', 'HI' => 'Hawaii', 'ID' => 'Idaho',
        'IL' => 'Illinois', 'IN' => 'Indiana', 'IA' => 'Iowa', 'KS' => 'Kansas',
        'KY' => 'Kentucky', 'LA' => 'Louisiana', 'ME' => 'Maine', 'MD' => 'Maryland',
        'MA' => 'Massachusetts', 'MI' => 'Michigan', 'MN' => 'Minnesota', 'MS' => 'Mississippi',
        'MO' => 'Missouri', 'MT' => 'Montana', 'NE' => 'Nebraska', 'NV' => 'Nevada',
        'NH' => 'New Hampshire', 'NJ' => 'New Jersey', 'NM' => 'New Mexico', 'NY' => 'New York',
        'NC' => 'North Carolina', 'ND' => 'North Dakota', 'OH' => 'Ohio', 'OK' => 'Oklahoma',
        'OR' => 'Oregon', 'PA' => 'Pennsylvania', 'RI' => 'Rhode Island', 'SC' => 'South Carolina',
        'SD' => 'South Dakota', 'TN' => 'Tennessee', 'TX' => 'Texas', 'UT' => 'Utah',
        'VT' => 'Vermont', 'VA' => 'Virginia', 'WA' => 'Washington', 'WV' => 'West Virginia',
        'WI' => 'Wisconsin', 'WY' => 'Wyoming', 'DC' => 'District of Columbia'
      ];
      
      foreach($states as $code => $state_name) { 
      ?>
        <option value="<?php echo h($code); ?>" <?php if(($ride->state ?? '') == $code) { echo 'selected'; } ?>><?php echo h($state_name); ?></option>
      <?php } ?>
    </select>
  </dd>
</dl>

<dl>
  <dt>Zip Code</dt>
  <dd><input type="text" name="zip_code" size="5" maxlength="5" value="<?php echo h($ride->zip_code ?? ''); ?>" /></dd>