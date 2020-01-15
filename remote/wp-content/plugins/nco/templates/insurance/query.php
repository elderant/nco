<?php //nco_insurance_activation_handler();?>
<div class="insurance-form-section">
  <form action="" method="post" class="wpcf7-form nco-form query-form">
    <div class="search-container input-container full-width">
      <label for="first_name">
        <!-- <span class="asterisk">*</span> -->
        <?php _e('Buscar Código','nco'); ?>
      </label>
      <input type="text" name="query_code" value="" id="query_code" tabindex="10" />
    </div>
    <div class="form-actions">
      <input type="submit" name="wp-submit" value="<?php _e('Buscar'); ?>" class="code-query-button" tabindex="20" />
    </div>
  </form>
</div>
<div class="insurance-results-section"></div>