<?php //nco_insurance_activation_handler();?>
<?php $error = get_transient( 'nco_activation_error' ); ?>
<?php $success = get_transient( 'nco_activation_success' ); ?>
<div class="insurance-form-section">
  <?php if(true == $success) : ?>
    <hr/>
    <h3><?php _e('Su codigo a sido activado exitosamente.', 'nco');?></h3>
    <h5><?php _e('Gracias por utilizar nuestros servicios', 'nco');?></h5>
    <?php delete_transient( 'nco_activation_success' ); ?>
  <?php else : ?>
    <form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post" class="wpcf7-form nco-form">
      <div class="first-name-container input-container full-width">
        <label for="insurance_first_name">
          <!-- <span class="asterisk">*</span> -->
          <?php _e('Nombre','nco'); ?>
        </label>
        <input type="text" name="insurance_first_name" value="" id="first-name" tabindex="10" />
        <?php if ( isset($error['insurance_first_name'] ) ) : ?>
          <label id="insurance-first-name-error-server" class="error" for="insurance_first_name">
            <?php echo $error['insurance_first_name'];?>
          </label>
        <?php endif;?>
      </div>
      <div class="last-name-container input-container full-width">
        <label for="insurance_last_name">
          <!-- <span class="asterisk">*</span> -->
          <?php _e('Apellido','nco'); ?>
        </label>
        <input type="text" name="insurance_last_name" value="" id="last_name" tabindex="20" />
        <?php if ( isset($error['insurance_last_name'] ) ) : ?>
          <label id="insurance-last-name-error-server" class="error" for="insurance_last_name">
            <?php echo $error['insurance_last_name'];?>
          </label>
        <?php endif;?>
      </div>
      <div class="email-container input-container full-width">
        <label for="insurance_email">
          <!-- <span class="asterisk">*</span> -->
          <?php _e('Email', 'nco'); ?>
        </label>
        <input type="email" name="insurance_email" value="" id="insurance_email" tabindex="30" />
        <?php if ( isset($error['insurance_email'] ) ) : ?>
          <label id="insurance-email-error-server" class="error" for="insurance_email">
            <?php echo $error['insurance_email'];?>
          </label>
        <?php endif;?>
      </div>
      <div class="shop-container input-container full-width">
        <label for="insurance_shop">
          <!-- <span class="asterisk">*</span> -->
          <?php _e('Tienda de Compra', 'nco'); ?>
        </label>
        <select name="insurance_shop" tabindex="40">
          <option value="">---</option>
          <option value="iShop Colombia">iShop Colombia</option>
          <option value="iShop Panamá">iShop Panamá</option>
          <option value="iShop Perú">iShop Perú</option>
          <option value="iShop El Salvador">iShop El Salvador</option>
          <option value="iShop Costa Rica">iShop Costa Rica</option>
          <option value="iShop Nicaragua">iShop Nicaragua</option>
          <option value="iShop Honduras">iShop Honduras</option>
        </select>
        <?php if ( isset($error['insurance_shop'] ) ) : ?>
          <label id="insurance-shop-error-server" class="error" for="insurance_shop">
            <?php echo $error['insurance_shop'];?>
          </label>
        <?php endif;?>
      </div>
      <div class="invoice-number-container input-container full-width">
        <label for="insurance_invoice_number">
          <!-- <span class="asterisk">*</span> -->
          <?php _e('Número de Factura', 'nco'); ?>
        </label>
        <input type="number" name="insurance_invoice_number" value="" id="insurance_invoice_number" tabindex="50" />
        <?php if ( isset($error['insurance_invoice_number'] ) ) : ?>
          <label id="insurance-invoice-number-error-server" class="error" for="insurance_invoice_number">
            <?php echo $error['insurance_invoice_number'];?>
          </label>
        <?php endif;?>
      </div>
      <div class="device-container input-container full-width">
        <label for="insurance_device">
          <!-- <span class="asterisk">*</span> -->
          <?php _e('Dispositivo', 'nco'); ?>
        </label>
        <select name="insurance_device" tabindex="60">
          <option value="">---</option>
          <option value="Apple Watch">Apple Watch</option>
          <option value="iPhone">iPhone</option>
          <option value="iPad">iPad</option>
          <option value="MacBook">MacBook</option>
        </select>
        <?php if ( isset($error['insurance_device'] ) ) : ?>
          <label id="insurance-device-error-server" class="error" for="insurance_device">
            <?php echo $error['insurance_device'];?>
          </label>
        <?php endif;?>
      </div>
      <div class="serial-container input-container full-width">
        <label for="insurance_insurance_serial">
          <!-- <span class="asterisk">*</span> -->
          <?php _e('Número de Serie', 'nco'); ?>
        </label>
        <input type="number" name="insurance_insurance_serial" value="" id="insurance_serial" tabindex="70" />
        <?php if ( isset($error['insurance_insurance_serial'] ) ) : ?>
          <label id="insurance-insurance-serial-error-server" class="error" for="insurance_insurance_serial">
            <?php echo $error['insurance_insurance_serial'];?>
          </label>
        <?php endif;?>
      </div>
      <div class="code-container input-container full-width">
        <label for="insurance_activation_code">
          <!-- <span class="asterisk">*</span> -->
          <?php _e('Código de Activación', 'nco'); ?>
        </label>
        <input type="text" name="insurance_activation_code" value="" id="insurance_activation_code" tabindex="80" />
        <?php if ( isset($error['insurance_activation_code'] ) ) : ?>
          <label id="insurance_activation_code-error-server" class="error" for="insurance_activation_code">
            <?php echo $error['insurance_activation_code'];?>
          </label>
        <?php endif;?>
      </div>
      <div style="display: none;">
        <?php 
          if(false === $error) {
            delete_transient( 'nco_activation_error' ); 
          }
        ?>
        <input type="hidden" name="action" value="nco_insurance_activation">
      </div>
      <div class="form-actions">
        <input type="submit" name="wp-submit" value="<?php _e('Registrar'); ?>" class="code-submit" tabindex="100" />
        <!-- <input type="hidden" name="redirect_to" value="<?php //echo esc_attr($_SERVER['REQUEST_URI']); ?>?action=user_registered" /> -->
      </div>
    </form>
  <?php endif;?>
</div>

