<?php

class CalculLicence {

    private $numLicence;
    private $age;


    public function __construct($nLicence)
    {
        $oLicence = Doctrine::getTable('tbl_licence')->find($nLicence);
        $oProfil  = $oLicence->getTblProfil();

    }

    public function setNumLicence($nLicence)
    {
        $nLicence = (int) substr($nLicence, 0, 3);
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

    public function calcCotisationLicence()
    {
        if ($this->numLicence == 1) {
            //Cotisation annuel
            //Cotisation 1
        } elseif ($this->numLicence > 10 && $this->numLicence <=30 ) {
            //Cotisation 2
        } elseif ($this->numLicence > 30 ) {
            //Cotisation 3
        }
    }

    public function calcLicence()
    {
        //Famille
        //Mineur
        //Prix licence
        //Prix majoration
            //Renouvellement
            //Internationalisation
    }

    public function addPayment()
    {

    }
}