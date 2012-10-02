<?php
class ExportLicence {

  private $aValues;
  private $aCodeExist;
  private $aColonne;
  private $sYearLicence;
  private $nTypeLicAttJunior;
  private $nTypeLicAtt;
  private $nTypeLicMonJunior;
  private $nTypeLicMon;
  private $nTypeLicDir;
  private $nTypeLicSport;
  private $nTypeLicPro;
  private $nClubDefault;

  private $nCateDC;
  private $nCateNP;
  private $nCateNS;
  private $nCateDB;
  private $nCate2D;
  private $nCate4D;
  private $nCate6D;
  private $nCate8D;
  private $nCateUD;

  public function __construct($sFiles)
  {
    $this->openFiles($sFiles);
    $this->generateColonne();
    $this->sYearLicence = '2010/2011';
    $this->setTypeLicence();
    $this->setCate();
    $oClub = Doctrine::getTable('tbl_club')->findOneBy('num', '999');
    $this->nClubDefault = $oClub->getId();
  }

  private function setTypeLicence()
  {
    $oLicence = Doctrine::getTable('tbl_typelicence')->findOneBy('code', 'AT2');
    $this->nTypeLicAttJunior = $oLicence->getId();
    $oLicence = Doctrine::getTable('tbl_typelicence')->findOneBy('code', 'AT4');
    $this->nTypeLicAtt = $oLicence->getId();
    $oLicence = Doctrine::getTable('tbl_typelicence')->findOneBy('code', 'MO2');
    $this->nTypeLicMonJunior = $oLicence->getId();
    $oLicence = Doctrine::getTable('tbl_typelicence')->findOneBy('code', 'MO4');
    $this->nTypeLicMon = $oLicence->getId();
    $oLicence = Doctrine::getTable('tbl_typelicence')->findOneBy('code', 'DP1');
    $this->nTypeLicDir = $oLicence->getId();
    $oLicence = Doctrine::getTable('tbl_typelicence')->findOneBy('code', 'DP2');
    $this->nTypeLicPro = $oLicence->getId();
    $oLicence = Doctrine::getTable('tbl_typelicence')->findOneBy('code', 'SP');
    $this->nTypeLicSport = $oLicence->getId();
  }

  public function setCate()
  {
    $oCate = Doctrine::getTable('tbl_category')->findOneBy('code', 'DC');
    $this->nCateDC = $oCate->getId();
    $oCate = Doctrine::getTable('tbl_category')->findOneBy('code', 'NP');
    $this->nCateNP = $oCate->getId();
    $oCate = Doctrine::getTable('tbl_category')->findOneBy('code', 'NS');
    $this->nCateNS = $oCate->getId();
    $oCate = Doctrine::getTable('tbl_category')->findOneBy('code', 'DB');
    $this->nCateDB = $oCate->getId();
    $oCate = Doctrine::getTable('tbl_category')->findOneBy('code', '2D');
    $this->nCate2D = $oCate->getId();
    $oCate = Doctrine::getTable('tbl_category')->findOneBy('code', '4D');
    $this->nCate4D = $oCate->getId();
    $oCate = Doctrine::getTable('tbl_category')->findOneBy('code', '6D');
    $this->nCate6D = $oCate->getId();
    $oCate = Doctrine::getTable('tbl_category')->findOneBy('code', '8D');
    $this->nCate8D = $oCate->getId();
    $oCate = Doctrine::getTable('tbl_category')->findOneBy('code', 'UD');
    $this->nCateUD = $oCate->getId();
  }

  private function generateColonne()
  {
    //Profil
    $this->aColonne['last_name'] = 0;
    $this->aColonne['first_name'] = 1;
    $this->aColonne['email'] = 15;
    $this->aColonne['birthday'] = 16;
    $this->aColonne['sexe'] = 21;

    //Address
    $this->aColonne['address1'] = 2;
    $this->aColonne['address2'] = 3;
    $this->aColonne['tel'] = 10;
    $this->aColonne['gsm'] = 10;

    //Code postaux
    $this->aColonne['code_postal'] = 4;
    $this->aColonne['ville'] = 6;

    //Catégorie
    $this->aColonne['category_dc'] = 25;
    $this->aColonne['category_np'] = 26;
    $this->aColonne['category_ns'] = 27;
    $this->aColonne['category_db'] = 28;
    $this->aColonne['category_2d'] = 29;
    $this->aColonne['category_4d'] = 30;
    $this->aColonne['category_6d'] = 31;
    $this->aColonne['category_8d'] = 32;
    $this->aColonne['category_ud'] = 33;

    //Licence
    $this->aColonne['date_medical'] = 35;
    $this->aColonne['date_validation'] = 36;
    $this->aColonne['num'] = 37;
    $this->aColonne['ATT'] = 38;
    $this->aColonne['INTERNATIONAL'] = 39;
    $this->aColonne['MON'] = 40;
    $this->aColonne['JUNIOR'] = 41;
    $this->aColonne['DIG'] = 42;
    $this->aColonne['SPORT'] = 43;
    $this->aColonne['PRO'] = 44;
    $this->aColonne['new'] = 46;
    $this->aColonne['cnil'] = 49;
  }

  private function openFiles($sFiles)
  {
    $fp = fopen($sFiles, 'r');
    $nCompteur = 0;
    if ($fp !== false) {
      while (!feof($fp)){
        $ligne = fgets($fp,4096);
        $liste = explode(';',$ligne);
        $nCompteurEl = 0;
        foreach($liste as $element)
        {
          $this->aValues[$nCompteur][$nCompteurEl] = $element;
          $nCompteurEl++;
        }
        echo "</tr>";
        $nCompteur++;
      }
      fclose($fp);
    }
  }

  public function createLicence()
  {
    foreach($this->aValues as $key => $value)
    {
      if (!empty($this->aValues[$key][0])) {
        $nIdAddress = $this->createAddress($key);
        $nIdProfil  = $this->createProfil($key, $nIdAddress);
        $this->createLicenceUser($key, $nIdProfil);
      }
    }
    // echo $this->sYearLicence.'#<br />';
    // echo $this->nTypeLicAttJunior.'#<br />';
    // echo $this->nTypeLicAtt.'#<br />';
    // echo $this->nTypeLicMonJunior.'#<br />';
    // echo $this->nTypeLicMon.'#<br />';
    // echo $this->nTypeLicDir.'#<br />';
    // echo $this->nTypeLicSport.'#<br />';
    // echo $this->nTypeLicPro.'#<br />';
    // echo $this->nClubDefault.'#<br />';

    // echo $this->nCateDC.'#<br />';
    // echo $this->nCateNP.'#<br />';
    // echo $this->nCateNS.'#<br />';
    // echo $this->nCateDB.'#<br />';
    // echo $this->nCate2D.'#<br />';
    // echo $this->nCate4D.'#<br />';
    // echo $this->nCate6D.'#<br />';
    // echo $this->nCate8D.'#<br />';
    // echo $this->nCateUD.'#<br />';
  }

  public function createAddress($nPosition)
  {
    //Vérifie que l'adresse existe
    $sAddress1 = $this->aValues[$nPosition][$this->aColonne['address1']];
    $sAddress2 = $this->aValues[$nPosition][$this->aColonne['address2']];
    $sTel = $this->aValues[$nPosition][$this->aColonne['tel']];
    $sGsm = $this->aValues[$nPosition][$this->aColonne['gsm']];

    if ($sAddress1 == '' && $sAddress2 == '') {
      $sAddress1 = '-';
    } elseif ($sAddress1 == '' && $sAddress2 != '') {
      $sAddress1 = $sAddress2;
      $sAddress2 = '';
    }
    //Recherche du code postal
    $nIdCode = $this->getCodePostaux($nPosition);

    $oAddress = new tbl_address();
    $oAddress->setAddress1($sAddress1)
             ->setAddress2($sAddress2)
             ->setTel($sTel)
             ->setGsm($sGsm)
             ->setIdCodepostaux($nIdCode)
             ->save();

    return $oAddress->getId();
    //return 1;
  }

  public function createProfil($nPosition, $nIdAddress)
  {
    $sLastName  = $this->aValues[$nPosition][$this->aColonne['last_name']];
    $sFirstName = $this->aValues[$nPosition][$this->aColonne['first_name']];
    $sEmail     = $this->aValues[$nPosition][$this->aColonne['email']];
    $sBirthday  = $this->formatDateTime($this->aValues[$nPosition][$this->aColonne['birthday']]);
    $sSexe      = $this->aValues[$nPosition][$this->aColonne['sexe']];
    if ($sSexe == '1') {
      $sSexe = 'H';
    } else {
      $sSexe = 'F';
    }

    $oProfil = new tbl_profil();
    $oProfil->setLastName($sLastName)
             ->setFirstName($sFirstName)
             ->setEmail($sEmail)
             ->setBirthday($sBirthday)
             ->setSexe($sSexe)
             ->setIdAddress($nIdAddress)
             ->save();

    return $oProfil->getId();
    //return 1;
  }

  public function createLicenceUser($nPosition, $nIdProfil)
  {
    //Récupérer l'identifiant du club en fonction du num de licencié
    $sNum = $this->aValues[$nPosition][$this->aColonne['num']];
    $sNumClub = substr($sNum, 0, 3);
    $oClub = Doctrine::getTable('tbl_club')->findOneBy('num', $sNumClub);
    if (!$oClub) {
     $nClub = $this->nClubDefault;
    } else {
     $nClub = $oClub->getId();
    }
      //Parametres
      $sNum           = str_replace(' ', '.', $sNum);
      $sNum           = $sNum.'.'.$this->sYearLicence;
      $isNew          = $this->formatBoolean($this->aValues[$nPosition][$this->aColonne['new']]);
      $bInt           = $this->formatBoolean($this->aValues[$nPosition][$this->aColonne['INTERNATIONAL']]);
      $bCnil          = $this->formatBooleanText($this->aValues[$nPosition][$this->aColonne['cnil']]);
      $dateMedical    = $this->formatDateTime($this->aValues[$nPosition][$this->aColonne['date_medical']]);
      $dateValidation = $this->formatDateTime($this->aValues[$nPosition][$this->aColonne['date_validation']]);
      //Type de licence
      if ($this->formatBoolean($this->aValues[$nPosition][$this->aColonne['DIG']])) {
        $nLicence = $this->nTypeLicDir;
      } elseif ($this->formatBoolean($this->aValues[$nPosition][$this->aColonne['SPORT']])) {
        $nLicence = $this->nTypeLicSport;
      } elseif ($this->formatBoolean($this->aValues[$nPosition][$this->aColonne['PRO']])) {
        $nLicence = $this->nTypeLicPro;
      } elseif ($this->formatBoolean($this->aValues[$nPosition][$this->aColonne['JUNIOR']])) {
        if ($this->formatBoolean($this->aValues[$nPosition][$this->aColonne['MON']])) {
          $nLicence = $this->nTypeLicMonJunior;
        } elseif ($this->formatBoolean($this->aValues[$nPosition][$this->aColonne['ATT']])) {
          $nLicence = $this->nTypeLicAttJunior;
        } else {
          $nLicence = $this->nTypeLicAttJunior;
        }
      } elseif ($this->formatBoolean($this->aValues[$nPosition][$this->aColonne['MON']])) {
        $nLicence = $this->nTypeLicMon;
      } elseif ($this->formatBoolean($this->aValues[$nPosition][$this->aColonne['ATT']])) {
        $nLicence = $this->nTypeLicAtt;
      }

      //Catégorie
      if ($this->formatBoolean($this->aValues[$nPosition][$this->aColonne['category_dc']])) {
        $nCate = $this->nCateDC;
      } elseif ($this->formatBoolean($this->aValues[$nPosition][$this->aColonne['category_np']])) {
        $nCate = $this->nCateNP;
      } elseif ($this->formatBoolean($this->aValues[$nPosition][$this->aColonne['category_ns']])) {
        $nCate = $this->nCateNS;
      } elseif ($this->formatBoolean($this->aValues[$nPosition][$this->aColonne['category_db']])) {
        $nCate = $this->nCateDB;
      } elseif ($this->formatBoolean($this->aValues[$nPosition][$this->aColonne['category_2d']])) {
        $nCate = $this->nCate2D;
      } elseif ($this->formatBoolean($this->aValues[$nPosition][$this->aColonne['category_4d']])) {
        $nCate = $this->nCate4D;
      } elseif ($this->formatBoolean($this->aValues[$nPosition][$this->aColonne['category_6d']])) {
        $nCate = $this->nCate6D;
      } elseif ($this->formatBoolean($this->aValues[$nPosition][$this->aColonne['category_8d']])) {
        $nCate = $this->nCate8D;
      } elseif ($this->formatBoolean($this->aValues[$nPosition][$this->aColonne['category_ud']])) {
        $nCate = $this->nCateUD;
      } else {
        $nCate = null;
      }

      $oLicence = new tbl_licence();
      $oLicence->setNum($sNum)
               ->setIsNew($isNew)
               ->setIsBrouillon(false)
               ->setInternational($bInt)
               ->setCnil($bCnil)
               ->setDateMedical($dateMedical)
               ->setDateValidation($dateValidation)
               ->setYearLicence($this->sYearLicence)
               ->setIdProfil($nIdProfil)
               ->setIdClub($nClub)
               ->setIdTypelicence($nLicence)
               ->setIdCategory($nCate)
               ->save();
  }

  public function getCodePostaux($nPosition)
  {
    $sCodePostal = $this->aValues[$nPosition][$this->aColonne['code_postal']];
    $sVille      = $this->aValues[$nPosition][$this->aColonne['ville']];

    $oCodePostaux = Doctrine::getTable('tbl_codepostaux')->findByVilleCode($sVille, $sCodePostal);
    if ($oCodePostaux) {
      return $oCodePostaux->getId();
    }
    return null;
  }

  public function getValues()
  {
    return $this->aValues;
  }

  public function getCodeExist()
  {
    return $this->aCodeExist;
  }

  private function formatDateTime($sDate)
  {
    if ($sDate != '' && strlen($sDate) == 10) {

      $aDate = explode('/', $sDate);
      $sNewDate = $aDate[2].'-'.$aDate[1].'-'.$aDate[0];
      return $sNewDate;
    }
    return null;
  }

  private function formatBoolean($sValue)
  {
    if ($sValue == 1) {
      return true;
    } else {
      return false;
    }
  }
  private function formatBooleanText($sValue)
  {
    if ($sValue == 'oui') {
      return true;
    } else {
      return false;
    }
  }
}
