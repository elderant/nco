<?php $password_obj = $nco_admin_args['password_obj'];?>
<div class="wrap">
  <h1><?php _e('Contraseñas de verificación','nco');?></h1>
  <a class="page-title-action" href="<?php echo get_admin_url(null, '/admin.php?page=nco_add_claim_password');?>"><?php _e('Agregar Contraseña','nco');?></a>
  <div id="poststuff">
    <div id="post-body" class="metabox-holder columns-2">
      <div id="post-body-content">
        <div class="meta-box-sortables ui-sortable">
          <form method="post">
            <?php
              if(!empty($password_obj)){
                $password_obj -> prepare_items();
                $password_obj -> display(); 
              }
            ?>
          </form>
        </div>
      </div>
    </div>
    <br class="clear">
  </div>
</div>