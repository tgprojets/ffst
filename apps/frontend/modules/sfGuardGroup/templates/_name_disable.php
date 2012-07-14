<?php $nId = $form['id']->getValue(); ?>
<?php $oGroup = Doctrine::getTable('sfGuardGroup')->find($nId) ?>
<div class="sf_admin_form_row">
    <div>
        <label>Libellé</label>
        <span class="value_form">
            <?php echo $oGroup->getName() ?>
        </span>
    </div>
</div>
