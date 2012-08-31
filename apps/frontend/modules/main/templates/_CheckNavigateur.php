<?php if (isset($bNavigateurIncompatible)): ?>
  <?php if ($sf_params->get('action') != 'compatibility'): ?>
    <?php echo sfContext::getInstance()->getController()->redirect('main/compatibility'); ?>
  <?php endif; ?>
<?php endif; ?>
