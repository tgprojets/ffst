<?php use_stylesheet('formulaire_params.css') ?>
<div class="sb_connexion_login">

    <?php if ($sf_user->hasFlash('notice')): ?>
      <div class="notice">
        <?php echo $sf_user->getFlash('notice') ?>
      </div>
    <?php endif; ?>

<form action="<?php echo url_for('params/majorDate') ?>" method="post" id="myform" height="250px">
    <fieldset>
    <ul class='formRegister'>

      <?php if ($form->hasGlobalErrors()): ?>
          <?php foreach ($form->getGlobalErrors() as $name => $error): ?>
            <li class='error'><?php echo $error ?></li>
          <?php endforeach; ?>
      <?php endif; ?>
      <li>
          <div class="sb_bouton_a">

          </div>
      </li>
      <?php foreach ($form as $widget): ?>

      <?php if ($widget->hasError()): ?>
         <li class='error'><?php echo $widget->getError(); ?></li>
      <?php endif; ?>

      <?php if (!$widget->isHidden()) { ?>
       <li>
        <span>
          <?php echo $widget->renderLabel(); ?>
        </span>
        <?php echo $widget->render() ?>
        <?php if ($widget->renderHelp()): ?>
          <div class="noname_form_help" title="<?php echo $widget->renderHelp() ?>"></div>
        <?php endif; ?>
       </li>
       <?php } else { ?>
        <li style="height: 1px;"><?php echo $widget->render() ?></li>
       <?php } ?>
      <?php endforeach; ?>
    </ul>

        <div class="sb_btn_center sb_bouton_a">
            <?php echo link_to('Annuler', '@homepage') ?>
            <input type="submit" class='icone_valider noname_button' value="Enregistrer" />
        </div>
    </fieldset>
</form>
</div>
