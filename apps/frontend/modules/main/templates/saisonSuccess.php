<?php use_stylesheet('formulaire_connexion.css') ?>
<script type="text/javascript">
  function closeSaison()
  {
    if (confirm('Etes vous sur de mettre fin à la saisie de la saison ?')) {
      $.ajax({
          type: "GET",
          url: '<?php echo url_for("main/CloseSaisonAjax") ?>',
          cache: false,
          dataType: "json",
          async: true,
          success: function (msg) {
            if (msg.error == false) {
              $('.message_close').html('<div class="notice">Saison fermé.</div>');
              $('.sb_connexion_login').hide();
            } else {
              $('.message_close').html('<div class="sb_error">Impossible de fermé la saison.</div>');
            }
          },
          error: function onError(data, status) {
              alert(status);
              return false;
          }
      });
    }
    return false;
  }
</script>
<div class="message_close">

</div>
<?php if ($sf_user->hasFlash('notice')): ?>
  <div class="notice">
    <?php echo $sf_user->getFlash('notice') ?>
  </div>
<?php endif; ?>
<div class="sb_connexion_login">
    <form action="<?php echo url_for('main/saison') ?>" method="post" id="myform" enctype="multipart/form-data">
      <fieldset>
        <h2>
          <?php if ($bNewSaison): ?>
            Nouvelle saison
          <?php else: ?>
            Gestion de la saison
          <?php endif; ?>
        </h2>
          <ul class='formRegister'>
            <li>
              <label><?php echo $yearLicence ?> </label>
            </li>
            <?php if ($form->hasGlobalErrors()): ?>
            <?php foreach ($form->getGlobalErrors() as $name => $error): ?>
              <li class='error'><?php echo $error ?></li>
            <?php endforeach; ?>
            <?php endif; ?>
            <?php foreach ($form as $widget): ?>
                <?php if ($widget->hasError()): ?>
                   <li class='error'><?php echo $widget->getError(); ?></li>
                <?php endif; ?>

                <?php if (!$widget->isHidden()): ?>
                     <li>
                        <span>
                           <?php echo $widget->renderLabel(); ?>
                        </span>
                        <?php echo $widget->render() ?>
                        <?php if ($widget->renderHelp()): ?>
                          <div class="noname_form_help" title="<?php echo $widget->renderHelp() ?>"></div>
                        <?php endif; ?>
                     </li>
                <?php else: ?>
                    <li style="min-height: 1px;"><?php echo $widget->render() ?></li>
                <?php endif; ?>
            <?php endforeach; ?>
          </ul>
        <div class="sb_btn_center sb_bouton_a">
            <?php if ($bNewSaison == false): ?>
              <a href="#" onClick="closeSaison()">FIN DE SAISON</a>
            <?php endif; ?>
            <input type="submit" class='noname_button' value="Enregisrer" />
        </div>
      </fieldset>
    </form>
</div>
