<footer>
  &copy; <?php echo date('Y'); ?> WandrBae
</footer>

</body>
</html>
<?php if(isset($show_map) && $show_map === true): ?>
  <!-- Leaflet JS -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <!-- Custom map JavaScript -->
  <script src="<?php echo url_for('/javascripts/route_map.js'); ?>"></script>
<?php endif; ?>
<?php
  db_disconnect($db);
?>
