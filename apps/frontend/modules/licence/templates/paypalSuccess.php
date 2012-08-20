<?php use_stylesheet('admin.css') ?>
<div id="sf_admin_container" style="color:#fff;">
    <h1>A régler avec PAYPAL</h1>
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
      <?php foreach($oAvoirClub as $oPaiement): ?>
          <?php include_partial('licence/displayBordereau', array('oElement' => $oAvoir, 'bAvoir' => true)) ?>
      <?php endforeach; ?>
    </table>
  </div>
  <div class="sf_admin_form_row">
      <label for="">Montant total : <?php echo $nAmountTotal ?> €</label>
  </div>
  <ul  class="sf_admin_actions">
    <li class="sb_bouton_a">
      <?php echo link_to('Retour à la liste', '@tbl_licence', array()) ?>
    </li>
    <li>
      <form style="width:100px; float: left;" name="payment_paypal" id="payment_paypal" action="<?php echo url_for(sfConfig::get('app_paypal_url')) ?>" method="post">
        <input name="currency_code" type="hidden" value="EUR" />
        <input name="tax" type="hidden" value="0.00" />
        <input name="upload" type="hidden" value="1">
        <input name="cmd" type="hidden" value="_cart" />
        <input name="business" type="hidden" value="<?php echo sfConfig::get('app_paypal_business') ?>" />
        <input name="item_name" type="hidden" value="Paiement à la FFST" />
        <input name="item_number" type="hidden" value="<?php echo $oBordereau->getId() ?>" />
        <input name="quantity" type="hidden" value="1" />
        <input name="amount" type='hidden' value="<?php echo $nAmountTotal ?>"  />
        <input name="rm" type="hidden"  value="1">
        <input name="no_note" type="hidden" value="1" />
        <input name="lc" type="hidden" value="FR" />
        <input name="return" type="hidden" value="<?php echo sfConfig::get('app_paypal_return') ?>" />
        <input name="cancel_return" type="hidden" value="<?php echo sfConfig::get('app_paypal_cancel') ?>" />
        <input name="notify_url" type="hidden" value="<?php echo sfConfig::get('app_paypal_notify') ?>" />
        <input type="submit" value="Payer" />
      </form>
    </li>
  </ul>
</div>