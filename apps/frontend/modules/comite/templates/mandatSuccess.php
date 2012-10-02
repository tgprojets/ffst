<?php use_stylesheet('admin.css') ?>
<?php use_stylesheet('formulaire_connexion.css') ?>
<div id="sf_admin_container" style="color:#fff;">
    <h1><?php echo $oProfil->getName() ?></h1>
    <?php if ($oMandats->count() > 0): ?>
    <h1>Liste des mandats</h1>
    <div class="sf_admin_list">
        <table  cellspacing="0">
            <thead>
                <tr>
                    <th class="sf_admin_text">Début</th>
                    <th class="sf_admin_text">Fin</th>
                    <th class="sf_admin_text">Poste</th>
                    <th class="sf_admin_text">Actions</th>
                </tr>
            </thead>
        <?php foreach($oMandats as $oMandat): ?>
            <tr class="sf_admin_row">
                <td>
                    <?php echo format_date($oMandat->getDateBegin(), 'dd MMMM yyyy') ?>
                </td>
                <td>
                    <?php echo format_date($oMandat->getDateEnd(), 'dd MMMM yyyy') ?>
                </td>
                <td>
                    <?php echo $oMandat->getFonctionActuel() ?>
                </td>
                <td>
                    <ul class="sf_admin_td_actions">
                        <li class="sf_admin_action_delete">
                            <?php echo link_to('Supprimer', 'comite/deleteMandat?id='.$oMandat->getId().'&id_comite='.$oComite->getId()) ?>
                        </li>
                    </ul>
                </td>
            </tr>
        <?php endforeach; ?>
        </table>
        <br /><br />
    </div>
    <?php endif; ?>
</div>
<form action="<?php echo url_for('comite/mandat') ?>" method="post" id="myform" enctype="multipart/form-data">
  <fieldset>
    <h2>
      Nouveau mandat
    </h2>
      <ul class='formRegister'>
        <input type="hidden" id="id_comite" name="id_comite" value="<?php echo $oComite->getId() ?>" />
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
        <?php echo link_to('Retour à la liste', '@tbl_comite') ?>
        <input type="submit" class='noname_button' value="Enregisrer" />
    </div>
  </fieldset>
</form>
