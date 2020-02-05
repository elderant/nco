<?php //nco_insurance_activation_handler();?>
<div class="insurance-form-section">
  <form action="" method="post" class="wpcf7-form nco-form query-form">
    <div class="search-container input-container full-width">
      <label for="first_name">
        <!-- <span class="asterisk">*</span> -->
        <?php _e('Buscar Seguro','nco'); ?>
      </label>
      <input type="text" name="query_str" value="" id="query_str" tabindex="10" />
    </div>
    <div class="field-container input-container full-width">
      <label for="query_field"></label>
      <select name="query_field" value="" id="query_field" tabindex="20">
        <option value="codigo">Código de activación</option>
        <option value="serie">Número de serie</option>
        <option value="email">Email</option>
      </select>
    </div>
    <div class="form-actions">
      <input type="submit" name="wp-submit" value="<?php _e('Buscar'); ?>" class="code-query-button" tabindex="50" />
    </div>
  </form>
</div>
<div class="insurance-results-section"></div>
<div class="modal-root transparent hidden">
  <div class="modal-overlay"></div>
  <div class="modal-dialog">
    <header class="modal-header uppercase">
      <span class="modal-title"><?php _e('Verificación', 'nco');?></span>
      <span class="close-container"><button class="close-button">X</button></span>
    </header>
    <div class="modal-body">
      <form action="" method="post" class="wpcf7-form nco-form verification-form">
        <div class="field-container input-container full-width">
          <label for="claim_password">
            <?php _e('Contraseña','nco'); ?>
          </label>
          <input type="password" name="claim_password" value="" id="claim_password" tabindex="110"/>
        </div>
        <div class="form-actions">
          <input type="submit" name="wp-submit" value="<?php _e('Confirmar', 'nco'); ?>" class="password-validation-button" tabindex="150" />
          <input type="hidden" name="post-id" value="">
        </div>
      </form>
    </div>
  </div>
</div>