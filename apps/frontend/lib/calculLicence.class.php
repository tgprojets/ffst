<?php

class CalculLicence {

    private $numLicence;
    private $age;
    private $nLicence;
    private $nTypeLicence;
    private $bInternational;
    private $bNew;
    private $bFamilly;
    private $DateSaisie;
    private $YearStart;
    private $YearEnd;
    private $nClub;
    private $nUser;
    private $oProfil;
    private $oLicence;


    public function __construct($nLicence)
    {
        $this->nLicence = $nLicence;
        $oLicence              = Doctrine::getTable('tbl_licence')->find($nLicence);
        $this->oProfil         = $oLicence->getTblProfil();
        $this->bInternational  = $oLicence->getInternational();
        $this->bNew            = $oLicence->getIsNew();
        $this->bFamilly        = $oLicence->getIsFamilly();
        $this->DateSaisie      = $oLicence->getCreatedAt();
        $this->nClub           = $oLicence->getIdClub();
        $this->nUser           = sfContext::getInstance()->getUser()->getGuardUser()->getId();
        $this->setYearLicence();
        $this->setNumLicence($oLicence->getNum());
        $this->setAge($this->oProfil->getBirthday());
        $this->setTypeLicence($oLicence->getIdTypelicence());
        $this->oLicence = $oLicence;
    }

    public function setNumLicence($nLicence)
    {
        $nLicence = (int) substr($nLicence, 5, 3);
        $this->numLicence = $nLicence;
    }

    public function getNumLicence()
    {
        return $this->numLicence;
    }

    public function setAge($Birthday)
    {
        $bornAt = new DateTime($Birthday);
        $age = $bornAt->diff(new \DateTime())->y;
        $this->age = $age;
    }

    public function getAge()
    {
        return $this->age;
    }

    public function setTypeLicence($nTypeLicence)
    {
        $oType = Doctrine::getTable('tbl_typelicence')->find($nTypeLicence);
        $this->nTypeLicence = $oType->getCode();
    }

    private function getArticle($code, $bPrice = true)
    {
        $oPrix = Doctrine::getTable('tbl_prixunit')->findOneBy('code', $code);

        if ($oPrix)
        {
            if ($bPrice)
            {
                return $oPrix->getPrix();
            } else {
                return $oPrix->getLib();
            }
        }

        return 0;
    }

    private function getPrixLicence($code, $bPrice = true)
    {
        $oPrix = Doctrine::getTable('tbl_typelicence')->findOneBy('code', $code);

        if ($oPrix)
        {
            if ($bPrice)
            {
                return $oPrix->getPrix();
            } else {
                return $oPrix->getLib();
            }
        }

        return 0;
    }

    private function getReduct($code, $bPrice = true)
    {
        $oPrix = Doctrine::getTable('tbl_tarifreduit')->findOneBy('code', $code);

        if ($oPrix)
        {
            if ($bPrice)
            {
                return $oPrix->getPrix();
            } else {
                return $oPrix->getLib();
            }
        }

        return 0;
    }

    public function calcCotisationLicence()
    {
        if ($this->numLicence == 1) {
            //Cotisation annuel
            $this->addPayment($this->getArticle('CA'), $this->getArticle('CA', false), 'tbl_club', true);

            //Cotisation 1
            $this->addPayment($this->getArticle('CT1'), $this->getArticle('CT1', false), 'tbl_club', true);
        } elseif ($this->numLicence > 10 && $this->numLicence <=30 ) {
            //Cotisation 2
            $this->addPayment($this->getArticle('CT2'), $this->getArticle('CT2', false), 'tbl_club', true);
        } elseif ($this->numLicence > 30 ) {
            //Cotisation 3
            $this->addPayment($this->getArticle('CT3'), $this->getArticle('CT3', false), 'tbl_club', true);
        }
    }

    private function setYearLicence()
    {
        if ((int) date('m') >= 7 && (int) date('d') >= 1) {
            $this->YearStart = date('Y');
            $this->YearEnd   = date('Y', strtotime('+1 year'));
        } else {
            $this->YearStart = date('Y', strtotime('-1 year'));
            $this->YearEnd   = date('Y');
        }

    }

    public function payMajorInternational()
    {
        if ($this->bInternational) {
            if (date('Y', strtotime($this->DateSaisie)) == $this->YearStart) {
                if ((int) date('m', strtotime($this->DateSaisie)) >= 11 && (int) date('d', strtotime($this->DateSaisie)) >= 15) {
                    return true;
                }
            } else {
                return true;
            }
        }

        return false;
    }

    private function payMajorRenew()
    {
        if ($this->bNew == false) {
            if (date('Y', strtotime($this->DateSaisie)) == $this->YearStart) {
                if ( (int) date('m', strtotime($this->DateSaisie)) >= 10 && (int) date('d', strtotime($this->DateSaisie)) >= 31) {
                    return true;
                }
            } else {
                return true;
            }
        }
        return false;
    }

    public function calcLicence()
    {
        //Famille
        if ($this->bFamilly) {
            $this->addAvoir($this->getReduct('FM'), $this->getReduct('FM', false));
        }
        //Mineur
        if ($this->age <= 18) {
            $this->addAvoir($this->getReduct('MIN'), $this->getReduct('MIN', false));
        }

        //Prix licence
        $this->addPayment($this->getPrixLicence($this->nTypeLicence), $this->getPrixLicence($this->nTypeLicence, false), 'tbl_licence');
        //Prix majoration
        //Renouvellement
        if ($this->payMajorRenew()) {
            $this->addPayment($this->getArticle('MDD'), $this->getArticle('MDD', false), 'tbl_licence');
        }
        //Internationalisation
        if ($this->payMajorInternational()) {
            $this->addPayment($this->getArticle('INT'), $this->getArticle('INT', false), 'tbl_licence');
        }
    }

    public function calcLicenceEdit($aValues)
    {
        //Famille
        if ($this->bFamilly == false && $aValues['id_familly']) {
            $this->addAvoir($this->getReduct('FM'), $this->getReduct('FM', false));
        }
        if ($this->bInternational == false && $aValues['international'])
        {
            $this->bInternational = true;
            if( $this->payMajorInternational()) {
                $this->addPaymentEdit($this->getArticle('INT'), $this->getArticle('INT', false), 'tbl_licence');
            }
        }

        if ($this->oLicence->getIdTypelicence() != $aValues['id_typelicence'])
        {
            $this->addPaymentEdit($this->getArticle('S1'), $this->getArticle('S1', false), 'tbl_licence');
        }

        if ($this->oProfil->getEmail() != $aValues['email'])
        {
            $this->addPaymentEdit($this->getArticle('S4'), $this->getArticle('S4', false), 'tbl_licence');
        }

        if ($this->oProfil->getTblAddress()->getIdCodepostaux() != $aValues['id_codepostaux'] ||
            $this->oProfil->getTblAddress()->getAddress1() != $aValues['address1'] ||
            $this->oProfil->getTblAddress()->getAddress2() != $aValues['address2'] ||
            $this->oProfil->getTblAddress()->getTel() != $aValues['tel'] ||
            $this->oProfil->getTblAddress()->getGsm() != $aValues['gsm'] ||
            $this->oProfil->getTblAddress()->getFax() != $aValues['fax'] )
        {
            $this->addPaymentEdit($this->getArticle('S2'), $this->getArticle('S2', false), 'tbl_licence');
        }

        if ($this->oProfil->getLastName() != $aValues['last_name'])
        {
            $this->addPaymentEdit($this->getArticle('S3'), $this->getArticle('S3', false), 'tbl_licence');
        }
        if ($this->oProfil->getFirstName() != $aValues['first_name'])
        {
            $this->addPaymentEdit($this->getArticle('S3'), $this->getArticle('S3', false), 'tbl_licence');
        }
        if (substr($this->oProfil->getBirthday(), 0, 10) != $aValues['birthday'])
        {
            $this->addPaymentEdit($this->getArticle('S3'), $this->getArticle('S3', false), 'tbl_licence');
        }
    }

    private function addPayment($nAmount, $sLib, $sRelation, $nClub=false)
    {
        $oPaiement = new tbl_payment();
        $oPaiement->setLib($sLib)
                  ->setRelationTable($sRelation)
                  ->setAmount($nAmount)
                  ->setIdUser($this->nUser);
        if ($nClub)
        {
            $oPaiement->setIdClub($this->nClub);
        } else {
            $oPaiement->setIdLicence($this->nLicence)
                      ->setIdClub($this->nClub);
        }
        $oPaiement->save();
    }

    private function addPaymentEdit($nAmount, $sLib, $sRelation, $nClub=false)
    {
        $oPaiement = new tbl_payment();
        $oPaiement->setLib($sLib)
                  ->setRelationTable($sRelation)
                  ->setIsBrouillon(false)
                  ->setAmount($nAmount)
                  ->setIdUser($this->nUser);
        if ($nClub)
        {
            $oPaiement->setIdClub($this->nClub);
        } else {
            $oPaiement->setIdLicence($this->nLicence)
                      ->setIdClub($this->nClub);
        }
        $oPaiement->save();
    }

    private function addAvoir($nAmount, $sLib, $nClub=false)
    {
        $oPaiement = new tbl_avoir();
        $oPaiement->setLib($sLib)
                  ->setRelationTable('tbl_licence')
                  ->setAmount($nAmount)
                  ->setIdUser($this->nUser);
        if ($nClub)
        {
            $oPaiement->setIdClub($this->nClub);
        } else {
            $oPaiement->setIdLicence($this->nLicence)
                      ->setIdClub($this->nClub);
        }
        $oPaiement->save();
    }

    private function addAvoirEdit($nAmount, $sLib, $nClub=false)
    {
        $oPaiement = new tbl_avoir();
        $oPaiement->setLib($sLib)
                  ->setRelationTable('tbl_licence')
                  ->setAmount($nAmount)
                  ->setIsBrouillon(false)
                  ->setIdUser($this->nUser);
        if ($nClub)
        {
            $oPaiement->setIdClub($this->nClub);
        } else {
            $oPaiement->setIdLicence($this->nLicence)
                      ->setIdClub($this->nClub);
        }
        $oPaiement->save();
    }
}