<tr>
    <td>
        <?php
            if ($oElement->getTblClub())
            {
              echo $oElement->getTblClub();
            }
            if ($oElement->getTblLicence())
            {
              echo ' '.$oElement->getTblLicence();
            }
            if ($oElement->getTblProfil())
            {
              echo ' '.$oElement->getTblProfil();
            }
            if ($oElement->getTblLigue())
            {
              echo ' '.$oElement->getTblLigue();
            }
        ?>
    </td>
    <td>
        <span><?php echo $oElement->getLib() ?></span>
    </td>
    <td>
        <span><?php echo $oElement->getAmount() ?> € </span>
    </td>
    <td>
        <?php if ($bAvoir): ?>
            <img src="/images/valide.png" />
        <?php endif; ?>
    </td>
</tr>
