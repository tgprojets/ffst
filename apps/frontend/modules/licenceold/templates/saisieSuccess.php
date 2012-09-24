<?php use_stylesheet('admin.css') ?>
<div id="sf_admin_container" style="color:#fff;">
    <h1>Votre saisie</h1>
  <div class="sf_admin_list">
    <table cellspacing="0">
      <thead>
        <tr>
          <th class="sf_admin_text">Propriété</th>
          <th class="sf_admin_text">Intitulé</th>
          <th class="sf_admin_text">Montant</th>
          <th class="sf_admin_text">Avoir</th>
        </tr>
      </thead>
    <?php foreach($oPaymentClub as $oPaiement): ?>
        <?php include_partial('licence/displayBordereau', array('oElement' => $oPaiement, 'bAvoir' => false)) ?>
    <?php endforeach; ?>

    <?php foreach($oAvoirClub as $oAvoir): ?>
        <?php include_partial('licence/displayBordereau', array('oElement' => $oAvoir, 'bAvoir' => true)) ?>
    <?php endforeach; ?>
    </table>
  </div>
<ul  class="sf_admin_actions">
  <li class="sb_bouton_a">
    <?php echo link_to('Retour à la liste', '@tbl_licence', array()) ?>
  </li>
  <?php if ($nValider): ?>
      <li class="sf_admin_action_paypal sb_bouton_a">
        <?php echo link_to(__('Payer par PAYPAL', array(), 'messages'), 'licence/ListPaypal', array('class' => 'sb_bouton_a')) ?>
      </li>
  <?php else: ?>
      <li class="sf_admin_action_cancel_saisie sb_bouton_a">
        <?php echo link_to(__('Annuler la saisie', array(), 'messages'), 'licence/ListCancelSaisie', array()) ?>
      </li>
      <li class="sf_admin_action_valid_saisie sb_bouton_a">
        <?php echo link_to(__('Valider la saisie', array(), 'messages'), 'licence/ListValidSaisie', array()) ?>
      </li>
  <?php endif; ?>

</ul>
</div>
