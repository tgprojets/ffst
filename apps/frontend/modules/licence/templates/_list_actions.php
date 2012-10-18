<?php if (!$sf_user->isLigue()): ?>
  <?php if ($sf_user->hasCredential(array(0 => array(0 => 'account_club', 1 => 'licence'  ),))): ?>
    <?php echo $helper->linkToNew(array(  'params' =>   array(  ),  'class_suffix' => 'new',  'label' => 'New',)) ?>
  <?php endif; ?>
  <?php if ($sf_user->hasSaisie() > 0 && $sf_user->hasCredential(array(0 => array(0 => 'account_club', 1 => 'licence'  ),))): ?>
    <li class="sf_admin_action_cancel_saisie sb_bouton_a">
      <?php echo link_to(__('Annuler la saisie', array(), 'messages'), 'licence/ListCancelSaisie', array()) ?>
    </li>
    <li class="sf_admin_action_valid_saisie sb_bouton_a">
      <?php echo link_to(__('Valider la saisie', array(), 'messages'), 'licence/ListValidSaisie', array()) ?>
    </li>
    <li class="sf_admin_action_saisie sb_bouton_a">
      <?php echo link_to(__('Afficher la saisie', array(), 'messages'), 'licence/ListSaisie', array()) ?>
    </li>
  <?php elseif ($sf_user->hasToPayed() && $sf_user->isClub()) : ?>
    <li class="sf_admin_action_paypal sb_bouton_a">
      <?php echo link_to(__('Payer par PAYPAL', array(), 'messages'), 'licence/ListPaypal', array()) ?>
      <?php echo link_to(__('Payer par chèque', array(), 'messages'), 'licence/ListCheque', array()) ?>
    </li>
  <?php endif; ?>
  <?php if ($sf_user->hasCredential(array(0 => array(0 => 'stats')))): ?>
    <li class="sf_admin_action_export_data sb_bouton_a">
      <?php echo link_to(__('Exporter vers Excel', array(), 'messages'), 'licence/ListExportData', array()) ?>
    </li>
  <?php endif; ?>
<?php endif; ?>
