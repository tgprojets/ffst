<?php
if ($tbl_payment->getTblClub())
{
  echo $tbl_payment->getTblClub();
}
if ($tbl_payment->getTblLicence())
{
  echo ' '.$tbl_payment->getTblLicence();
}
if ($tbl_payment->getTblLigue())
{
  echo ' '.$tbl_payment->getTblLigue();
}
?>
