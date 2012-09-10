<?php use_stylesheet('formulaire_connexion.css') ?>

<?php if ($sf_user->hasFlash('notice')): ?>
  <div class="notice">
    <?php echo $sf_user->getFlash('notice') ?>
  </div>
<?php endif; ?>
<div class="sb_connexion_login">
    <form action="<?php echo url_for('main/docForm') ?>" method="post" id="myform" enctype="multipart/form-data">
      <fieldset>
        <h2>Contenu du documents</h2>
          <ul class='formRegister'>
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
            <input type="submit" class='noname_button' value="Enregisrer" />
        </div>
      </fieldset>
    </form>
</div>
