<footer>
  &copy; <?php echo date('Y'); ?> WandrBae
  <p>Public Footer Active</p>
</footer>

</body>
</html>

<?php if(isset($show_map) && $show_map === true): ?>
  <!-- Google Maps JavaScript API -->
  <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo 'AIzaSyDDVvFVJsg2uT4iKCrLxMnrQTEA_l--7n4'; ?>&libraries=places&callback=initMap" defer></script>
  <!-- Custom map JavaScript -->
  <script src="<?php echo url_for('/js/route_map.js'); ?>"></script>
<?php endif; ?>

<?php
  db_disconnect($db);
?>
