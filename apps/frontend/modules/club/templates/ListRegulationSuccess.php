<?php use_stylesheet('admin.css') ?>
<div id="sf_admin_container" style="color:#fff;">
<?php if ($oPaymentClub->count() == 0): ?>
    <h2>Aucun règlement encours</h2>
<?php else: ?>
<?php use_stylesheet('admin.css') ?>
<?php use_stylesheet('paypal.css') ?>
    <h1>Reste à régler</h1>
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
<?php endif; ?>
</div>
