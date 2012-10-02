<?php  use_stylesheet('formulaire_connexion.css') ?>

<div class="sb_connexion_login">
  <form action="<?php echo url_for('@sf_guard_signin') ?>" method="post" id="myform">
      <fieldset>
      <h2>S'authentifier</h2>
      <ul class='formRegister'>
          <?php if (isset($gsMessage)): ?>
              <div class="notice"><?php echo $gsMessage ?></div>
          <?php endif; ?>
          <?php if ($form->hasGlobalErrors()): ?>
              <?php foreach ($form->getGlobalErrors() as $name => $error): ?>
                <li class='error'><?php echo $error ?></li>
              <?php endforeach; ?>
          <?php endif; ?>
          <?php foreach ($form as $widget): ?>
              <?php if (!$widget->isHidden()): ?>
                 <li>
                    <?php if ($widget->hasError()): ?>
                        <div class="sb_error"><?php echo $widget->getError(); ?></div>
                    <?php endif; ?>
                    <span>
                        <?php echo $widget->renderLabel(); ?>
                    </span>
                    <?php echo $widget->render() ?>
                    <?php if ($widget->renderHelp()): ?>
                        <div class="sb_form_help" title="<?php echo $widget->renderHelp() ?>"></div>
                    <?php endif; ?>
                </li>
              <?php else: ?>
                <li style="height: 1px;">
                    <?php echo $widget->render() ?>
                </li>
              <?php endif; ?>
          <?php endforeach; ?>
      </ul>
      <div class="sb_btn_center sb_bouton_a">
          <input type="submit" value="Se connecter" />
      </div>
      </fieldset>
  </form>
</div>
