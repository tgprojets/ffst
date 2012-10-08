<body>
<img src="<?php echo $cid ?>" alt="Image" />
<br />
<h2><?php echo $oClub->getName() ?></h2>
Confirmation de votre règlements
<br />
<br />
<h3>Liste des règlements</h3>
<table width="60%" border="0">
    <thead >
        <tr bgcolor="#083a99" style="color:#fff;">
            <th>Libellé</th>
            <th>Montant</th>
        </tr>
    </thead>
<?php foreach ($aRegulations as $aRegulation): ?>
    <tr>
        <td align="left"><?php echo $aRegulation->getLib() ?></td>
        <td align="center"><?php echo $aRegulation->getAmount() ?> €</td>
    </tr>
<?php endforeach; ?>
</table>
<br />
___________________________<br />
FFST <br />
www.laffst.com <br />
email: ffst@free.fr
</body>