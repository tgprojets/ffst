
<?php
$nTotal = 0;
$aTotal = array();
foreach ($pager->getResults() as $i => $tbl_payment) {
    $nTotal += $tbl_payment->getAmount();
    $aTotal[$tbl_payment->getTblTypepayment()->getLib()] = (array_key_exists($tbl_payment->getTblTypepayment()->getLib(), $aTotal)?$aTotal[$tbl_payment->getTblTypepayment()->getLib()]:0)+$tbl_payment->getAmount();

}
?>
<div class="total-regulation">
    <h1>Total de la page en cours</h1>
    <hr>
    <table class="table">
        <tr>
            <td>Montant total</td>
            <td>
                <?php echo $nTotal ?> €
            </td>
        </tr>
        <?php foreach ($aTotal as $key => $value): ?>
            <tr>
                <td><?php echo ($key == "")?"Aucun type":$key ?></td>
                <td><?php echo $value ?> €</td>
            </tr>
        <?php endforeach ?>
    </table>
</div>
