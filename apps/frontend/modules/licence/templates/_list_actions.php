<?php echo $helper->linkToNew(array(  'params' =>   array(  ),  'class_suffix' => 'new',  'label' => 'New',)) ?>
<?php if ($sf_user->hasSaisie() > 0 && $sf_user->hasCredential(array(  0 =>   array(    0 => 'account_club',    1 => 'account_ligue',  ),))): ?>
  <li class="sf_admin_action_cancel_saisie">
    <?php echo link_to(__('Annuler la saisie', array(), 'messages'), 'licence/ListCancelSaisie', array()) ?>
  </li>
  <li class="sf_admin_action_valid_saisie">
    <?php echo link_to(__('Valider la saisie', array(), 'messages'), 'licence/ListValidSaisie', array()) ?>
  </li>
  <li class="sf_admin_action_saisie">
    <?php echo link_to(__('Afficher la saisie', array(), 'messages'), 'licence/ListSaisie', array()) ?>
  </li>
<?php elseif ($sf_user->hasToPayed()) : ?>
  <li class="sf_admin_action_paypal">
    <?php echo link_to(__('Payer par PAYPAL', array(), 'messages'), 'licence/ListPaypal', array()) ?>
  </li>
<?php endif; ?>
