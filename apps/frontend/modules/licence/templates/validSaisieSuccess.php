<?php foreach($oPaymentClub as $oPaiement): ?>
    <?php include_partial('licence/displayBordereau', array('oElement' => $oPaiement)) ?>
<?php endforeach; ?>

<?php foreach($oAvoirClub as $oAvoir): ?>
    <?php include_partial('licence/displayBordereau', array('oElement' => $oAvoir)) ?>
<?php endforeach; ?>

<ul>
  <li>
    <?php echo link_to('Retour à la liste', '@tbl_licence', array()) ?>
  </li>
  <?php if ($nValider): ?>
      <li class="sf_admin_action_paypal">
        <?php echo link_to(__('Payer par PAYPAL', array(), 'messages'), 'licence/ListPaypal', array()) ?>
      </li>
  <?php else: ?>
      <li class="sf_admin_action_cancel_saisie">
        <?php echo link_to(__('Annuler la saisie', array(), 'messages'), 'licence/ListCancelSaisie', array()) ?>
      </li>
      <li class="sf_admin_action_valid_saisie">
        <?php echo link_to(__('Valider la saisie', array(), 'messages'), 'licence/ListValidSaisie', array()) ?>
      </li>
  <?php endif; ?>

</ul>