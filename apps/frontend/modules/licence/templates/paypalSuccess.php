<table>
<?php foreach($oPaymentLic as $oPaiement): ?>
    <tr>
        <td><?php echo $oPaiement->getLib() ?></td>
        <td><?php echo $oPaiement->getAmount() ?> €</td>
    </tr>
<?php endforeach; ?>
</table>
<table>
<?php foreach($oPaymentClub as $oPaiement): ?>
    <tr>
        <td><?php echo $oPaiement->getLib() ?></td>
        <td><?php echo $oPaiement->getAmount() ?> €</td>
    </tr>
<?php endforeach; ?>
</table>
<table>
<?php foreach($oAvoirLic as $oPaiement): ?>
    <tr>
        <td><?php echo $oPaiement->getLib() ?></td>
        <td><?php echo $oPaiement->getAmount() ?> €</td>
    </tr>
<?php endforeach; ?>
</table>
<table>
<?php foreach($oAvoirClub as $oPaiement): ?>
    <tr>
        <td><?php echo $oPaiement->getLib() ?></td>
        <td><?php echo $oPaiement->getAmount() ?> €</td>
    </tr>
<?php endforeach; ?>
</table>
<div>
    <label for=""><?php echo $nAmountTotal ?> €</label>
</div>
<form name="payment_paypal" id="payment_paypal" action="<?php echo url_for(sfConfig::get('app_paypal_url')) ?>" method="post">
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