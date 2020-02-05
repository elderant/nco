<?php global $title;?>
<div class="wrap">
  <h1 class="wp-heading-inline"><?php echo $title;?></h1>
  <hr class="wp-header-end">
  <?php nco_update_password_handler();?>
  <form id="shop-pass-form" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post">
    <input type="hidden" name="action" value="update_claim_password">
    <input type="hidden" name="name" value="<?php echo $nco_admin_args['name'];?>">
    <div id="poststuff">
      <div id="post-body" class="metabox-holder columns-2">
        <div id="post-body-content">
          <div class="input-div">
            <div class="input-wrap">
              <input type="text" name="claim_password_name" id="name" size="30" placeholder="Nombre">
            </div>
          </div>
          <div class="input-div">
            <div class="input-wrap">
              <input type="text" name="claim_password_base" id="code" size="30" placeholder="Código">
            </div>
          </div>
          <input type="submit" value="Guardar" class="button button-primary button-large">
        </div>
      </div>
    </div>
  </form>
</div>