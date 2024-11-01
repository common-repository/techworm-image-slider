<?php settings_errors(); ?>
<form  method="post" action="options.php">
<?php settings_fields( 'tis-autoplay' );?>		
<?php do_settings_sections( 'tis_reading' );?> 
<?php submit_button();?>


</form>
<?php echo '<h3>Short Code</h3>';?>
<?php echo '<h5>Use this shortcode &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[techworm-carousal-image ]</h5>';?>