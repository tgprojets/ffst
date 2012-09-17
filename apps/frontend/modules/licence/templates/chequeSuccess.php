<?php use_stylesheet('admin.css') ?>
<?php use_stylesheet('paypal.css') ?>
<div id="sf_admin_container" style="color:#fff;">
    <h1>A régler par chèque</h1>
  <div class="sf_admin_list">
    <table cellspacing="0">
      <thead>
        <tr>
          <th class="sf_admin_text">Propriété</th>
          <th class="sf_admin_text">Lib</th>
          <th class="sf_admin_text">Amout</th>
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
  <div class="sf_admin_form_row">
      <label for="">Montant total : <?php echo $nAmountTotal ?> €</label>
  </div>
  <ul id="paypal_action">
    <li class="marg_paypal_button sb_bouton_a">
      <?php echo link_to('Retour à la liste', '@tbl_licence', array()) ?>
    </li>
  </ul>
</div>
