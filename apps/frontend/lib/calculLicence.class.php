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


    public function calcCotisationLicence()
    {
        if ($this->numLicence == 1) {
            //Cotisation annuel
            $this->addPayment($this->getArticle('CA'), $this->getArticle('CA', false), 'tbl_club', true, true);

            //Cotisation 1
            $this->addPayment($this->getArticle('CT1'), $this->getArticle('CT1', false), 'tbl_club', true, true);
        } elseif ($this->numLicence > 10 && $this->numLicence <=30 ) {
            //Cotisation 2
            $this->addPayment($this->getArticle('CT2'), $this->getArticle('CT2', false), 'tbl_club', true, true);
        } elseif ($this->numLicence > 30 ) {
            //Cotisation 3
            $this->addPayment($this->getArticle('CT3'), $this->getArticle('CT3', false), 'tbl_club', true, true);
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
            if (Licence::getDateMajor('int', 'month') > 6) {
                if (date('Y', strtotime($this->DateSaisie)) == $this->YearStart) {
                    if ((int) date('m', strtotime($this->DateSaisie)) >= Licence::getDateMajor('int', 'day') &&
                        (int) date('d', strtotime($this->DateSaisie)) >= Licence::getDateMajor('int', 'month')) {
                        return true;
                    }
                } else {
                    return true;
                }
            } else {
                if (date('Y', strtotime($this->DateSaisie)) == $this->YearEnd) {
                    if ((int) date('m', strtotime($this->DateSaisie)) >= Licence::getDateMajor('int', 'day') &&
                        (int) date('d', strtotime($this->DateSaisie)) >= Licence::getDateMajor('int', 'month')) {
                        return true;
                    }
                } else {
                    return false;
                }
            }
        }

        return false;
    }

    private function payMajorRenew()
    {
        if ($this->bNew == false) {
            if (Licence::getDateMajor('renew', 'month') > 6)
            {
                if (date('Y', strtotime($this->DateSaisie)) == $this->YearStart) {
                    if ( (int) date('m', strtotime($this->DateSaisie)) >= Licence::getDateMajor('renew', 'day') &&
                         (int) date('d', strtotime($this->DateSaisie)) >= Licence::getDateMajor('renew', 'month')) {
                        return true;
                    }
                } else {
                    return true;
                }
            } else {
                if (date('Y', strtotime($this->DateSaisie)) == $this->YearEnd) {
                    if ( (int) date('m', strtotime($this->DateSaisie)) >= Licence::getDateMajor('renew', 'day') &&
                         (int) date('d', strtotime($this->DateSaisie)) >= Licence::getDateMajor('renew', 'month')) {
                        return true;
                    }
                } else {
                    return false;
                }
            }
        }
        return false;
    }

    public function calcLicence($bBrouillon=true)
    {

        //Prix licence
        $this->addPayment($this->getPrixLicence($this->nTypeLicence), $this->getPrixLicence($this->nTypeLicence, false), 'tbl_licence', false, $bBrouillon);
        //Prix majoration
        //Renouvellement
        if ($this->payMajorRenew()) {
            $this->addPayment($this->getArticle('MDD'), $this->getArticle('MDD', false), 'tbl_licence_mdd', false, $bBrouillon);
        }
        //Internationalisation
        if ($this->international)
        {
            $this->addPayment($this->getArticle('INT'), $this->getArticle('INT', false), 'tbl_licence_int', false, $bBrouillon);
        }
        if ($this->payMajorInternational()) {
            $this->addPayment($this->getArticle('MJI'), $this->getArticle('MJI', false), 'tbl_licence_int', false, $bBrouillon);
        }
    }

    public function calcLicenceEdit($aValues, $bBrouillon)
    {
        //Famille
        if ($this->bInternational == false && $aValues['international'])
        {
            $this->bInternational = true;
            $this->addPayment($this->getArticle('INT'), $this->getArticle('INT', false), 'tbl_licence_int', false, $bBrouillon);
            if( $this->payMajorInternational()) {
                $this->addPayment($this->getArticle('MJI'), $this->getArticle('MJI', false), 'tbl_licence_int', false, $bBrouillon);
            }
        } elseif ($this->bInternational && $aValues['international'] == false) {
            //Supprimer paiement int
            $this->deletePaiement('tbl_licence_int');
        }


        if ($this->oLicence->getIdTypelicence() != $aValues['id_typelicence'])
        {
            //Supprimer ancienne et ajouter la nouvelle
            $this->deletePaiement('tbl_licence');
            $this->setTypeLicence($aValues['id_typelicence']);
            $this->addPayment($this->getPrixLicence($this->nTypeLicence), $this->getPrixLicence($this->nTypeLicence, false), 'tbl_licence', false, $bBrouillon);
        }

    }

    public function deletePaiement($sType)
    {
        $oPaiement = Doctrine::getTable('tbl_payment')->findToDdelete($sType, $this->nUser, $this->nLicence);
    }

    public function existePaiement($sType)
    {
        $oPaiement = Doctrine::getTable('tbl_payment')->findPayment($sType, $this->nUser, $this->nLicence);
        if ($oPaiement->count() > 0)
        {
            return true;
        }
        return false;
    }

    public function calcLicenceEditDateValid($aValues)
    {
        //Famille
        if ($this->bInternational == false && $aValues['international'])
        {
            if ($this->existePaiement('tbl_licence_int') == false)
            {
                $this->bInternational = true;
                $this->addPayment($this->getArticle('INT'), $this->getArticle('INT', false), 'tbl_licence_int', false, false);
                if( $this->payMajorInternational()) {
                    $this->addPayment($this->getArticle('MJI'), $this->getArticle('MJI', false), 'tbl_licence_int', false, false);
                }
            }
        } elseif ($this->bInternational && $aValues['international'] == false) {
            //Supprimer paiement int
            $this->deletePaiement('tbl_licence_int');
        }

        if ($this->oLicence->getIdTypelicence() != $aValues['id_typelicence'])
        {
            //Calcul différence de prix
            $oNewLicence = Doctrine::getTable('tbl_typelicence')->find($aValues['id_typelicence']);
            $nAmount = $oNewLicence->getPrix() - $this->oLicence->getTblTypelicence()->getPrix();
            if ($nAmount > 0) {
                //Ajoute nouveau paiement
                $this->addPayment($nAmount, 'Supplément pour modification licence', 'tbl_licence', false, false);
            }
        }

    }

    private function addPayment($nAmount, $sLib, $sRelation, $nClub, $bBrouillon)
    {
        $oPaiement = new tbl_payment();
        $oPaiement->setLib($sLib)
                  ->setRelationTable($sRelation)
                  ->setAmount($nAmount)
                  ->setIsBrouillon($bBrouillon)
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