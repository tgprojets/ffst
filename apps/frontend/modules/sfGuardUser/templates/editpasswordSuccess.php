<?php use_stylesheet('formulaire_connexion.css') ?>
<script type="text/javascript">
    $(document).ready(function() {
        $('#generate_password').click(function() {
            if ($('#modifpassword_password').size()) {
                $.ajax({
                    type: "POST",
                    url:"<?php echo url_for('@generate_password') ?>",
                    dataType: "json",
                    async: false,
                    success: function(sData) {
                        if (sData.password != undefined) {
                            $('#modifpassword_password').val(sData.password);
                        } else {
                            alert('Error server');
                        }
                    },
                    error: function (sData) {
                       alert('Error server');
                    }
                });
            }
            return false;
        });
    });
</script>


<div class="sb_connexion_login">

<form action="<?php echo url_for('sfGuardUser/editPassword', $oUser); ?><?php echo '?id='.$oUser->getId() ?>" method="post" id="myform" height="250px">
    <fieldset>
    <h2>Utilisateur <?php echo $oUser->getName() ?></h2>

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
            <?php echo link_to('Annuler', '@sf_guard_user') ?>
            <a href="#" id="generate_password">Generer un mot de passe</a>
            <input type="submit" class='icone_valider noname_button' value="Enregistrer" />
        </div>
    </fieldset>
</form>
</div>