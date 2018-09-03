<?php

class Licence {
  public static function getDateLicence()
  {
    //Récupère l'année encours
    $oDateLicence = Doctrine::getTable('tbl_saison')->findOneBy('is_outstanding', true);
    if ($oDateLicence) {
      return $oDateLicence->getYearLicence();
    } else {
      return null;
    }
/*    if (date('d') >= 1 && date('m') >= 7) {
      $startDate = date('Y');
      $endDate   = date('Y')+1;
    } else {
      $startDate = date('Y')-1;
      $endDate   = date('Y');
    }
    $sDate = (string) $startDate.'/'.(string) $endDate;

    return $sDate;*/
  }
  public static function getSaison()
  {
    //Récupère l'année encours
    $oDateLicence = Doctrine::getTable('tbl_saison')->findOneBy('is_outstanding', true);
    if ($oDateLicence) {
      return $oDateLicence;
    }
  }
  public static function getDateEndSaison($sSaison)
  {
    $oDateLicence = Doctrine::getTable('tbl_saison')->findOneBy('year_licence', $sSaison);
    if ($oDateLicence) {
      $sDay   = str_pad($oDateLicence->getDayEnd(), 2, "0", STR_PAD_LEFT);
      $sMonth = str_pad($oDateLicence->getMonthEnd(), 2, "0", STR_PAD_LEFT);
    } else {
      $sDay   = '30';
      $sMonth = '06';
    }
    $sYear = substr($sSaison, 5);
    return $sDay.'/'.$sMonth.'/'.$sYear;

  }

  public static function getDateStartSaison($sSaison)
  {
    $oDateLicence = Doctrine::getTable('tbl_saison')->findOneBy('year_licence', $sSaison);
    if ($oDateLicence) {
      $sDay   = str_pad($oDateLicence->getDayBegin(), 2, "0", STR_PAD_LEFT);
      $sMonth = str_pad($oDateLicence->getMonthBegin(), 2, "0", STR_PAD_LEFT);
    } else {
      $sDay   = '01';
      $sMonth = '07';
    }
    $sYear = substr($sSaison, 0, 4);
    return $sDay.'/'.$sMonth.'/'.$sYear;

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
    $sLicence = "Numéro de licence;sexe;nom;prénom;nom club;num club;renouvellement;annuler;type licence;categorie;international;race nordique;cnil;date medical;date validation;année de la licence;date anniversaire;adresse;lieu-dit;cp;ville;pays;tel;gsm;email"."\n";
    foreach ($oLicences as $oLicence) {
      $oProfil         = $oLicence->getTblProfil();
      $oAddress        = $oProfil->getTblAddress();
      $oCP             = $oAddress->getTblCodepostaux();
      $oClub           = $oLicence->getTblClub();
      $oTypeLicence    = $oLicence->getTblTypelicence();
      $oCategory       = $oLicence->getTblCategory();
      $sLicence .= $oLicence->getNum().$sSep;
      $sLicence .= $oProfil->getSexe().$sSep;
      $sLicence .= strtoupper($oProfil->getLastName()).$sSep;
      $sLicence .= strtoupper($oProfil->getFirstName()).$sSep;
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
      if ($oCP) {
        $sLicence .= $oCP->getCodePostaux().$sSep;
        $sLicence .= $oCP->getVille().$sSep;
      } else {
        $sLicence .= $oAddress->getCpForeign().$sSep;
        $sLicence .= $oAddress->getCityForeign().$sSep;
      }
      if ($oAddress->getCountry()) {
        $sLicence .= sfContext::getInstance()->getI18N()->getCountry($oAddress->getCountry()).$sSep;
      } else {
        $sLicence .= 'France'.$sSep;
      }
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

  public static function getNumBordereau()
  {
    //Création de num commande
    $oMax = Doctrine::getTable('tbl_bordereau')->getMaxNumCmd();
    $nNum = 1;
    if ($oMax) {
       $nNum = $oMax[0]['nummax']+$nNum;
    }
    $sNumCommande = date('Ym').'-'.$nNum;
    $oBordereau = Doctrine::getTable('tbl_bordereau')->findOneBy('num', $sNumCommande);
    while ($oBordereau) {
      $nNum++;
      $sNumCommande = date('Ym').'-'.$nNum;
      $oBordereau = Doctrine::getTable('tbl_bordereau')->findOneBy('num', $sNumCommande);
    }
    return $sNumCommande;
  }

  public static function endSaison()
  {
    $oDateLicence = Doctrine::getTable('tbl_saison')->findOneBy('is_outstanding', true);
    if ($oDateLicence) {
      $aYears = explode('/', $oDateLicence->getYearLicence());
      $year = date('Y');
      if ($year == $aYears[0]) {
        if (date('m') > $oDateLicence->getMonthBegin()) {
          return false;
        } elseif (date('m') == $oDateLicence->getMonthBegin() && date('d') >= $oDateLicence->getDayBegin()) {
          return false;
        } else {
          return true;
        }
      } else {
        if (date('m') == $oDateLicence->getMonthEnd()) {
          return false;
        } elseif (date('m') < $oDateLicence->getMonthEnd() && date('d') <= $oDateLicence->getDayEnd()) {
          return false;
        } else {
          return true;
        }
      }
    } else {
      return true;
    }
  }

  public static function getParam($sCode, $bDescription=true)
  {
    $oParam = Doctrine::getTable('tbl_params')->findOneBy('code', $sCode);
    if ($oParam) {
      if ($bDescription) {
        return $oParam->getDescription();
      } else {
        return $oParam->getLib();
      }
    }
    return '';
  }
}
