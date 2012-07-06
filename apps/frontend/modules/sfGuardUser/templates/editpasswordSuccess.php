<?php use_stylesheet('formulaire_connexion.css') ?>
<div class="noname_connexion">
<h1><?php echo __('Edit your password') ?></h1>

<form action="<?php echo url_for('sfGuardUser/editPassword', $oUser); ?><?php echo '?id='.$oUser->getId() ?>" method="post" id="myform" height="250px">
    <fieldset>

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

        <div class="noname_center">
            <?php echo button_to(__('Cancel'), '@homepage', array('class' => 'icone_cancel noname_button')) ?>
            <input type="submit" class='icone_valider noname_button' value=<?php echo __("Validate") ?> />
        </div>
    </fieldset>
</form>
</div>