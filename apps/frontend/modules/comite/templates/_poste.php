<?php
    $oProfil = $tbl_comite->getTblProfil();
    $oMandat = Doctrine::getTable('tbl_mandat')->findOlderPoste($oProfil->getId());
    if ($oMandat) {
        echo $oMandat->getFonctionActuel();
    }

?>