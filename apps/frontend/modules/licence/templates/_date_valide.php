<?php $nId = $form['id']->getValue(); ?>
<?php $oLicence = Doctrine::getTable('tbl_licence')->find($nId) ?>
<div class="sf_admin_form_row">
  <label for="">Date validation</label>
  <div class="text_show"><?php echo format_date($oLicence->getDateValidation(), 'dd MMMM yyyy') ?></div>
</div>
