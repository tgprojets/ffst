<body>
<img src="<?php echo $cid ?>" alt="Image" />
<br />
<h2><?php echo $oClub->getName() ?></h2>

Confirmation de votre règlements pour le bordereau : <?php $oBordereau->getLib() ?><br />
<div>
    Num bordereau : <?php echo $oBordereau->getNum() ?>
</div>
<div>
    Montant : <?php echo $oBordereau->getAmount() ?> €
</div>
<br /><br />
<?php if ($oPayments->count() > 0): ?>
<h2>Paiements</h2>
<table width="60%" border="0">
    <thead>
        <tr bgcolor="#083a99" style="color:#fff;">
            <th>Libellé</th>
            <th>Montant</th>
        </tr>
    </thead>
<?php foreach ($oPayments as $oPayment): ?>
    <tr>
        <td align="left"><?php echo $oPayment->getLib() ?></td>
        <td align="center"><?php echo $oPayment->getAmount() ?> €</td>
    </tr>
<?php endforeach; ?>
</table>
<?php endif; ?>
<?php if ($oAvoirs->count() > 0): ?>
<h2>Avoirs</h2>
<table width="60%" border="0">
    <thead>
        <tr bgcolor="#083a99" style="color:#fff;">
            <th>Libellé</th>
            <th>Montant</th>
        </tr>
    </thead>
<?php foreach ($oAvoirs as $oAvoir): ?>
    <tr>
        <td align="left"><?php echo $oAvoir->getLib() ?></td>
        <td align="center"><?php echo $oAvoir->getAmount() ?> €</td>
    </tr>
<?php endforeach; ?>
</table>
<?php endif; ?>
<br />
___________________________<br />
FFST <br />
www.laffst.com <br />
ffst@free.fr
</body>