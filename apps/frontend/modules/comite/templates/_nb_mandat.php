<?php
    $oProfil = $tbl_comite->getTblProfil();
    $oManda = Doctrine::getTable('tbl_mandat')->findBy('id_profil', $oProfil->getId());
    echo $oManda->count();
?>