<?php
// prevents this code from being loaded directly in the browser
// or without first setting the necessary object
if(!isset($ride)) {
  redirect_to(url_for('/members/rides/index.php'));
}
?>

<!--<dl> Form shouldn't have ride id, it should be auto incremented in DB
  <dt>$ride_id</dt>
  <dd><input type="text" name="brand" value="" /></dd>
</dl> -->

<dl>
  <dt>Ride Name</dt>
  <dd><input type="text" name="ride_name" value="" /></dd>
</dl>

<dl>
  <dt>Created By</dt>
  <dd>
    <select name="username">
      <option value=""></option>
    <?php $this_year = idate('Y') ?>
    <?php for($year=$this_year-20; $year <= $this_year; $year++) { ?>
      <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
    <?php } ?>
    </select>
  </dd>
</dl>

<!-- Route ID should also be auto incremented into db, not on form-->

<dl> 
  <dt>Route ID</dt>
  <dd>
    <select name="route_id">
      <option value=""></option>
    <?php foreach(ride::CATEGORIES as $category) { ?>
      <option value="<?php echo $category; ?>"><?php echo $category; ?></option>
    <?php } ?>
    </select>
  </dd>
</dl>

<dl>
  <dt>Start Time</dt>
  <dd>
    <select name="start_time">
      <option value=""></option>
    <?php foreach(ride::START_TIME as $start_time) { ?>
      <option value="<?php echo $start_time; ?>"><?php echo $start_time; ?></option>
    <?php } ?>
    </select>
  </dd>
</dl>

<dl>
  <dt>End Time</dt>
  <dd><input type="text" name="end_time" value="" /></dd>
</dl>

<dl>
  <dt>Location</dt>
  <dd>
    <select name="location">
      <option value=""></option>
    <?php foreach(ride::LOCATION as $location => $location) { ?>
      <option value="<?php echo $location; ?>"><?php echo $location; ?></option>
    <?php } ?>
    </select>
  </dd>
</dl>

<dl>
  <dt>Street Address(kg)</dt>
  <dd><input type="text" name="address" value="" /></dd>
</dl>

<dl>
  <dt>City</dt>
  <dd>$ <input type="text" name="city" size="18" value="" /></dd>
</dl>

<dl>
  <dt>State</dt>
  <dd><textarea name="state" rows="5" cols="50"></textarea></dd>
</dl>

<dl>
  <dt>Zip Code</dt>
  <dd><textarea name="zipcode" rows="5" cols="50"></textarea></dd>
</dl>