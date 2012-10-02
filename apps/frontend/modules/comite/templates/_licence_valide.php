<?php
  $oProfil = $tbl_comite->getTblProfil();
  if ($oProfil->getLastLicenceValide())
  {
      echo image_tag('/images/tick.png');
  }
?>
