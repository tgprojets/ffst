<?php foreach($oPaymentLic as $oPaiement): ?>
    <?php include_partial('licence/displayBordereau', array('oElement' => $oPaiement)) ?>
<?php endforeach; ?>

<?php foreach($oPaymentClub as $oPaiement): ?>
    <?php include_partial('licence/displayBordereau', array('oElement' => $oPaiement)) ?>
<?php endforeach; ?>

<?php foreach($oAvoirLic as $oAvoir): ?>
    <?php include_partial('licence/displayBordereau', array('oElement' => $oAvoir)) ?>
<?php endforeach; ?>

<?php foreach($oAvoirClub as $oAvoir): ?>
    <?php include_partial('licence/displayBordereau', array('oElement' => $oAvoir)) ?>
<?php endforeach; ?>
