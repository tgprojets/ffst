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