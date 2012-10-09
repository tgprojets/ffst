<?php $nId = $form['id']->getValue(); ?>
<?php $oParam = Doctrine::getTable('tbl_params')->find($nId) ?>
<?php if ($oParam): ?>
  <div class="sf_admin_form_row">
    <label><?php echo $oParam->getCode() ?></label>
  </div>
<?php endif; ?>
