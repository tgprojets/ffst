<?php

class Licence {
  public static function getDateLicence()
  {
    if (date('d') >= 1 && date('m') >= 7) {
      $startDate = date('Y');
      $endDate   = date('Y')+1;
    } else {
      $startDate = date('Y')-1;
      $endDate   = date('Y');
    }
    $sDate = (string) $startDate.'/'.(string) $endDate;

    return $sDate;
  }

  public static function getDateMajor($sType, $sDate)
  {
    $oInitParam = new InitParam();
    $sValue = $oInitParam->getValueByKey('Date validation', 'date_'.$sType.'_'.$sDate);
    if ($sValue)
    {
      return $sValue;
    } else {
      return sfConfig::get('app_majordate_'.$sType.'_'.$sDate);
    }
    return null;
  }

  public static function getStartYearLicence()
  {
    if ((int) date('m') >= 7 && (int) date('d') >= 1) {
      return date('Y');
    } else {
      return date('Y', strtotime('-1 year'));
    }

  }

  public static function getEndYearLicence()
  {
    if ((int) date('m') >= 7 && (int) date('d') >= 1) {
      return date('Y', strtotime('+1 year'));
    } else {
      return date('Y');
    }
  }

  public static function exportData($oLicences)
  {
    $sSep = ";";
    $sLicence = "Numéro de licence;sexe;nom & prénom;nom club;num club;renouvellement;annuler;type licence;categorie;international;race nordique;cnil;date medical;date validation;année de la licence;date anniversaire;adresse;lieu-dit;cp;ville;tel;gsm;email"."\n";
    foreach ($oLicences as $oLicence) {
      $oProfil         = $oLicence->getTblProfil();
      $oAddress        = $oProfil->getTblAddress();
      $oCP             = $oAddress->getTblCodepostaux();
      $oClub           = $oLicence->getTblClub();
      $oTypeLicence    = $oLicence->getTblTypelicence();
      $oCategory       = $oLicence->getTblCategory();
      $sLicence .= $oLicence->getNum().$sSep;
      $sLicence .= $oProfil->getSexe().$sSep;
      $sLicence .= $oProfil->getName().$sSep;
      $sLicence .= $oClub->getName().$sSep;
      $sLicence .= (string) $oClub->getNum().$sSep;
      $sLicence .= Licence::getBoolean($oLicence->getIsNew(), true).$sSep;
      $sLicence .= Licence::getBoolean($oLicence->getIsCancel()).$sSep;
      $sLicence .= $oTypeLicence->getLib().$sSep;
      $sLicence .= $oCategory->getLib().$sSep;
      $sLicence .= Licence::getBoolean($oLicence->getInternational()).$sSep;
      $sLicence .= Licence::getBoolean($oLicence->getRaceNordique()).$sSep;
      $sLicence .= Licence::getBoolean($oLicence->getCnil()).$sSep;
      $sLicence .= $oLicence->getDateMedical().$sSep;
      $sLicence .= $oLicence->getDateValidation().$sSep;
      $sLicence .= $oLicence->getYearLicence().$sSep;
      $sLicence .= $oProfil->getBirthday().$sSep;
      $sLicence .= $oAddress->getAddress1().$sSep;
      $sLicence .= $oAddress->getAddress2().$sSep;
      $sLicence .= $oCP->getCodePostaux().$sSep;
      $sLicence .= $oCP->getVille().$sSep;
      $sLicence .= $oAddress->getTel().$sSep;
      $sLicence .= $oAddress->getGsm().$sSep;
      $sLicence .= $oProfil->getEmail().$sSep;
      $sLicence .= "\n";
    }
    return $sLicence;

  }

  private static function getBoolean($bValue, $bInvers=false) {
    if ($bValue) {
      $sValue="X";
    } else {
      $sValue="";
    }
    if ($bInvers) {
      if ($bValue) {
        $sValue="";
      } else {
        $sValue="X";
      }
    }
    return $sValue;
  }
}