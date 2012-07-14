<?php $nId = $form['id']->getValue(); ?>
<?php $oPermission = Doctrine::getTable('sfGuardPermission')->find($nId) ?>
<div class="sf_admin_form_row">
    <div>
        <label>Libellé</label>
        <span class="value_form">
            <?php echo $oPermission->getName() ?>
        </span>
    </div>
</div>