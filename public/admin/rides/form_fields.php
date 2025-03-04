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
  <dd><input type="text" name="ride" value="" /></dd>
</dl>

<dl>
  <dt>Created By</dt>
  <dd>
    <select name="year">
      <option value=""></option>
    <?php $this_year = idate('Y') ?>
    <?php for($year=$this_year-20; $year <= $this_year; $year++) { ?>
      <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
    <?php } ?>
    </select>
  </dd>
</dl>

<dl> <!-- Route ID should also be auto incremented into db, not on form-->
  <dt>Route ID</dt>
  <dd>
    <select name="category">
      <option value=""></option>
    <?php foreach(Bicycle::CATEGORIES as $category) { ?>
      <option value="<?php echo $category; ?>"><?php echo $category; ?></option>
    <?php } ?>
    </select>
  </dd>
</dl>

<dl>
  <dt>Start Time</dt>
  <dd>
    <select name="gender">
      <option value=""></option>
    <?php foreach(Bicycle::GENDERS as $gender) { ?>
      <option value="<?php echo $gender; ?>"><?php echo $gender; ?></option>
    <?php } ?>
    </select>
  </dd>
</dl>

<dl>
  <dt>End Time</dt>
  <dd><input type="text" name="color" value="" /></dd>
</dl>

<dl>
  <dt>Location</dt>
  <dd>
    <select name="condition_id">
      <option value=""></option>
    <?php foreach(Bicycle::CONDITION_OPTIONS as $cond_id => $cond_name) { ?>
      <option value="<?php echo $cond_id; ?>"><?php echo $cond_name; ?></option>
    <?php } ?>
    </select>
  </dd>
</dl>

<dl>
  <dt>Street Address(kg)</dt>
  <dd><input type="text" name="weight_kg" value="" /></dd>
</dl>

<dl>
  <dt>City</dt>
  <dd>$ <input type="text" name="price" size="18" value="" /></dd>
</dl>

<dl>
  <dt>State</dt>
  <dd><textarea name="description" rows="5" cols="50"></textarea></dd>
</dl>

<dl>
  <dt>Zip Code</dt>
  <dd><textarea name="description" rows="5" cols="50"></textarea></dd>
</dl>