<?php

class PrintLicenceOld {

    private $pdf;
    private $image;
    private $imageProfil;
    private $config;
    private $oLicence;
    private $oProfil;
    private $oCodePostal;
    private $oTypeLicence;
    private $oGroupLicence;
    private $oCategory;
    private $bCertificatValide;
    private $oProfilClub;

    public function __construct($oLicence)
    {
        $this->config        = sfTCPDFPluginConfigHandler::loadConfig('config_lic_old');
        $this->pdf           = new ffstTCPDF();
        $this->oLicence      = $oLicence;
        $this->oProfil       = $oLicence->getTblProfil();
        $this->oAddress      = $this->oProfil->getTblAddress();
        $this->oCodePostal   = $this->oAddress->getTblCodepostaux();
        $this->oTypeLicence  = $oLicence->getTblTypelicence();
        $this->oCategory     = $oLicence->getTblCategory();
        $this->oGroupLicence = $this->oTypeLicence->getTblGrouplicence();
        if ($oLicence->getDateMedical() != null)
        {
            $this->bCertificatValide = true;
        } else {
            $this->bCertificatValide = false;
        }
        $this->oProfilClub = $oLicence->getTblClub()->getSfGuardUser();
    }

    public function createLic()
    {
        // add a new page
        sfContext::getInstance()->getConfiguration()->loadHelpers('Date');

        $this->image       = sfConfig::get('sf_web_dir').'/images/'.PDF_HEADER_LOGO;
        //$this->imageProfil = myGenerique::generateThumbnailSetNewFilenamePrint(sfConfig::get('app_images_profil'), sfConfig::get('app_images_thumbnail'), $this->oProfil->getImage(), '80_80', 80, 80);
        if ($this->oProfil->getImage() != "") {
            $this->imageProfil = sfConfig::get('sf_upload_dir').'/profil/'.$this->oProfil->getImage();
        } else {
            $this->imageProfil = sfConfig::get('sf_web_dir').'/images/default_photo.jpg';
        }
        $imageBack         = sfConfig::get('sf_web_dir').'/images/background.jpg';
        $this->pdf->setPageOrientation(PDF_PAGE_ORIENTATION);
        $this->pdf->setEqualColumns(2, 150);

        $this->pdf->SetFont("helvetica", "", 12);

        $this->pdf->AddPage();
        $this->pdf->Image($imageBack, 0, 50, 140, 93, '', '', '', false, 300, 'C', false, false, 0);

        $this->getLicence();
        $this->getMedical();

        $this->pdf->Output(sfConfig::get('sf_upload_dir').DIRECTORY_SEPARATOR.'licence.pdf', 'FD');
        throw new sfStopException();
    }

    private function getLicence()
    {
        $this->pdf->selectColumn();
        $yPos = 14;
        $yDep = 2;
        $this->pdf->setXY(12, $yPos);
        $this->pdf->Cell(80, 14, '', 1, 2, 'C', 0, '', 0);
        $this->pdf->setXY(14, $yPos + $yDep);
        $this->pdf->SetFont('courier', 'B', 13);
        $this->pdf->Cell(70, 0, "ATTESTATION DE LICENCE FFST", 0, 2, 'L', 0, '', 0);
        $this->pdf->Cell(70, 0, "SAISON ".$this->oLicence->getYearLicence(), 0, 2, 'L', 0, '', 0);

        $this->getHeaderImage(107);

        $this->pdf->SetFont('helvetica', 'B', 12);
        $this->pdf->setY(40);
        $yPos = $this->pdf->getY();
        $this->pdf->Cell(18, 0, "CLUB :", 0, 1, 'L', 0, '', 0);
        $this->pdf->setXY($this->pdf->getX()+18, $yPos);
        $this->pdf->Cell(18, 0, $this->oLicence->getTblClub(), 0, 1, 'L', 0, '', 0);
        $this->pdf->SetFont('helvetica', '', 10);
        $yPos = $this->pdf->getY();
        $this->pdf->Cell(35, 10, "Numéro de licence :", 0, 1, 'L', 0, '', 0);
        $this->pdf->setXY($this->pdf->getX()+35, $yPos);
        $this->pdf->SetFont('helvetica', 'B', 10);
        $this->pdf->Cell(50, 10, $this->oLicence->getNum(), 0, 1, 'L', 0, '', 0);
        $yPos = $this->pdf->getY();
        $this->pdf->SetFont('helvetica', '', 10);
        $this->pdf->Cell(20, 0, "Délivrée le :", 0, 1, 'L', 0, '', 0);
        $this->pdf->setXY($this->pdf->getX()+20, $yPos);
        $this->pdf->SetFont('helvetica', 'B', 10);
        $this->pdf->Cell(50, 0, format_date($this->oLicence->getDateValidation(), 'dd/MM/yyyy'), 0, 1, 'L', 0, '', 0);

        $this->pdf->SetFont('helvetica', '', 10);
        $this->pdf->setXY($this->pdf->getX()+60, $yPos);
        $this->pdf->Cell(20, 0, "Date validité :", 0, 1, 'L', 0, '', 0);
        $this->pdf->SetFont('helvetica', 'B', 10);
        $this->pdf->setXY($this->pdf->getX()+82, $yPos);
        $this->pdf->Cell(50, 0, Licence::getDateEndSaison($this->oLicence->getYearLicence()), 0, 1, 'L', 0, '', 0);

        //NOM / Prénom /Date de naissance
        $yPos = $this->pdf->getY();
        $xPos = $this->pdf->getX();
        $this->pdf->SetFont('helvetica', '', 10);
        $this->pdf->Cell(12, 0, "Nom :", 0, 1, 'L', 0, '', 0);
        $this->pdf->setXY($xPos+12,$yPos);
        $this->pdf->SetFont('helvetica', 'B', 10);
        $this->pdf->Cell(25, 0, $this->oProfil->getLastName(), 0, 1, 'L', 0, '', 0);
        $xPos = $xPos+12;
        $this->pdf->setXY($xPos+25,$yPos);
        $this->pdf->SetFont('helvetica', '', 10);
        $this->pdf->Cell(15, 0, "Prénom :", 0, 1, 'L', 0, '', 0);
        $xPos = $xPos+25;
        $this->pdf->setXY($xPos+15,$yPos);
        $this->pdf->SetFont('helvetica', 'B', 10);
        $this->pdf->Cell(25, 0, $this->oProfil->getFirstName(), 0, 1, 'L', 0, '', 0);
        $xPos = $xPos+15;
        $this->pdf->setXY($xPos+25,$yPos);
        $this->pdf->SetFont('helvetica', '', 10);
        $this->pdf->Cell(32, 0, "Date de naissance :", 0, 1, 'L', 0, '', 0);
        $xPos = $xPos+25;
        $this->pdf->setXY($xPos+32,$yPos);
        $this->pdf->SetFont('helvetica', 'B', 10);
        $this->pdf->Cell(35, 0, format_date($this->oProfil->getBirthday(), 'dd MMMM yyyy'), 0, 1, 'L', 0, '', 0);

        //Image
        $this->pdf->Image($this->imageProfil, 110, 65, 22, '', '', '', '', false, 300, '', false, false, 1);

        //Sexe
        $yPos = $this->pdf->getY();
        $this->pdf->SetFont('helvetica', '', 10);
        $this->pdf->Cell(12, 0, "Sexe :", 0, 1, 'L', 0, '', 0);
        $this->pdf->setXY($this->pdf->getX()+12, $yPos);
        $this->pdf->SetFont('helvetica', 'B', 10);
        $this->pdf->Cell(10, 0, $this->oProfil->getSexe(), 0, 1, 'L', 0, '', 0);

        //N° rue
        $yPos = $this->pdf->getY();
        $this->pdf->SetFont('helvetica', '', 10);
        $this->pdf->Cell(18, 0, "N° et rue :", 0, 1, 'L', 0, '', 0);
        $this->pdf->setXY($this->pdf->getX()+18, $yPos);
        $this->pdf->SetFont('helvetica', 'B', 10);
        $this->pdf->Cell(10, 0, $this->oAddress->getAddress1(), 0, 1, 'L', 0, '', 0);

        if ($this->oAddress->getAddress2()) {
            $yPos = $this->pdf->getY();
            $this->pdf->SetFont('helvetica', '', 10);
            $this->pdf->Cell(16, 0, "Lieu-dit :", 0, 1, 'L', 0, '', 0);
            $this->pdf->setXY($this->pdf->getX()+16, $yPos);
            $this->pdf->SetFont('helvetica', 'B', 10);
            $this->pdf->Cell(10, 0, $this->oAddress->getAddress2(), 0, 1, 'L', 0, '', 0);
        }
        //CP et ville
        if ($this->oCodePostal) {
            $yPos = $this->pdf->getY();
            $xPos = $this->pdf->getX();
            $this->pdf->SetFont('helvetica', '', 10);
            $this->pdf->Cell(8, 0, "CP :", 0, 1, 'L', 0, '', 0);
            $this->pdf->setXY($this->pdf->getX()+8,$yPos);
            $this->pdf->SetFont('helvetica', 'B', 10);
            $this->pdf->Cell(12, 0, $this->oCodePostal->getCodePostaux(), 0, 1, 'L', 0, '', 0);
            $xPos = $xPos+8;
            $this->pdf->setXY($xPos+12,$yPos);
            $this->pdf->SetFont('helvetica', '', 10);
            $this->pdf->Cell(22, 0, "Bureau dist. :", 0, 1, 'L', 0, '', 0);
            $xPos = $xPos+12;
            $this->pdf->setXY($xPos+22,$yPos);
            $this->pdf->SetFont('helvetica', 'B', 10);
            $this->pdf->Cell(30, 0, $this->oCodePostal->getVille(), 0, 1, 'L', 0, '', 0);
        }

        //Téléphone
        $yPos = $this->pdf->getY();
        $this->pdf->SetFont('helvetica', '', 10);
        $this->pdf->Cell(20, 0, "Téléphone :", 0, 1, 'L', 0, '', 0);
        $this->pdf->setXY($this->pdf->getX()+20, $yPos);
        $this->pdf->SetFont('helvetica', 'B', 10);
        $this->pdf->Cell(40, 0, $this->oAddress->getTel().' / '.$this->oAddress->getGsm(), 0, 1, 'L', 0, '', 0);

        //Email
        $yPos = $this->pdf->getY();
        $this->pdf->SetFont('helvetica', '', 10);
        $this->pdf->Cell(16, 0, "E-Mail :", 0, 1, 'L', 0, '', 0);
        $this->pdf->setXY($this->pdf->getX()+16, $yPos);
        $this->pdf->SetFont('helvetica', 'B', 10);
        $this->pdf->Cell(40, 0, $this->oProfil->getEmail(), 0, 1, 'L', 0, '', 0);

        //Renew
        $yPos = $this->pdf->getY();
        $xPos = $this->pdf->getX();
        $this->pdf->SetFont('helvetica', '', 10);
        $this->pdf->Cell(30, 0, "Renouvellement :", 0, 1, 'L', 0, '', 0);
        $this->pdf->setXY($this->pdf->getX()+30, $yPos);
        $xPos = $xPos+30;
        $this->caseCheck(!$this->oLicence->getIsNew(), 'oui', $xPos, $yPos, 12);
        $xPos = $xPos+16;
        $this->pdf->setXY($xPos, $yPos);
        $this->caseCheck($this->oLicence->getIsNew(), 'non', $xPos, $yPos, 12);

        //Cnil
        $yPos = $this->pdf->getY()+4;
        $this->pdf->setY($yPos);
        $this->pdf->Cell(130, 12, '', 1, 2, 'L', 0, '', 0);
        $this->pdf->SetFont('helvetica', '', 11);
        $this->pdf->setY($yPos);
        $this->pdf->Cell(130, 0, "CNIL : Autorisation d'utilisation des données personnelles du licencié :", 0, 2, 'L', 0, '', 0);
        $yPos = $this->pdf->getY();
        $xPos = 60;
        $this->pdf->setXY($xPos, $yPos);
        $this->caseCheck($this->oLicence->getCnil(), 'oui', $xPos, $yPos, 12);
        $xPos = $xPos+16;
        $this->pdf->setXY($xPos, $yPos);
        $this->caseCheck(!$this->oLicence->getCnil(), 'non', $xPos, $yPos, 12);

        //Type de licence
        $yPos = $this->pdf->getY()+4;
        $xPos = 70;
        $this->pdf->setY($yPos);
        $this->pdf->Cell(70, 10, '', 1, 2, 'L', 0, '', 0);
        $this->pdf->setY($yPos);
        $this->pdf->MultiCell(70, 0, $this->oGroupLicence->getLib(), 0, 'L');
        $this->pdf->setXY(70, $yPos);
        $this->pdf->Cell(60, 10, '', 1, 2, 'L', 0, '', 0);
        $this->pdf->setXY(70, $yPos);
        if ($this->oGroupLicence->getCode() == 'COM')
        {
            if ($this->oCategory->getId()) {
                $this->caseCheck(true, $this->oCategory->getLib(), $xPos, $yPos, 40);
            }
            $yPos = $yPos+4;
            if ($this->oTypeLicence->getIsMinor()) {
                $this->pdf->setXY(70, $yPos);
                $this->caseCheck($this->oTypeLicence->getIsMinor(), '< 18 ans', $xPos, $yPos, 20);
                $xPos += 20;
            }
            $this->pdf->setXY($xPos, $yPos);
            $this->caseCheck($this->oLicence->getInternational(), 'INTERNATIONAL', $xPos, $yPos, 30);
        } elseif ($this->oGroupLicence->getCode() == 'DIG' || $this->oGroupLicence->getCode() == 'PRO') {
            $this->pdf->MultiCell(60, 0, 'Assurance "Responsabilité civile" seulement', 0, 'L');

        } elseif ($this->oGroupLicence->getCode() == 'SPL') {
            $this->pdf->Cell(60, 0, 'Non valide en compétition', 0, 'L');
        }
        $this->getFooter();
    }

    public function caseCheck($bValue, $text, $xPos, $yPos, $nLen)
    {
        $this->pdf->SetFont('zapfdingbats', '', 10);
        if ($bValue) {
            $nUnichr = 51;
        } else {
            $nUnichr = 113;
        }
        $this->pdf->Cell(4, 0, $this->pdf->unichr($nUnichr), 0, 1, 'L', 0, '', 0);
        $this->pdf->setXY($xPos + 4, $yPos);
        $this->pdf->SetFont('helvetica', '', 10);
        $this->pdf->Cell($nLen, 0, $text, 0, 1, 'L', 0, '', 0);
    }

    private function getMedical()
    {
        $this->pdf->selectColumn(1);

        //Bloc titre
        $yPos = 14;
        $yDep = 2;

        $this->pdf->setXY(152, $yPos);
        $this->pdf->Cell(54, 14, '', 1, 2, 'C', 0, '', 0);
        $this->pdf->setXY(154, $yPos + $yDep);
        $this->pdf->SetFont('courier', 'B', 13);
        $this->pdf->Cell(80, 0, "CERTIFICAT MEDICAL", 0, 2, 'L', 0, '', 0);
        $this->pdf->Cell(80, 0, "SAISON ".$this->oLicence->getYearLicence(), 0, 2, 'L', 0, '', 0);
        $this->pdf->setXY(180, 10);

        //image et texte
        $this->getHeaderImage(245);

        //Bloc important
        $this->pdf->SetFont('helvetica', '', 10);
        $this->pdf->setXY(150, 40);
        $sImportant = $this->bCertificatValide?Licence::getParam("ct_important_ok"):Licence::getParam("ct_important_ko");
        $this->pdf->MultiCell(140, 0, $sImportant, 1, 'C');

        //Champs
        $yDep = 3;
        $xPos = 152;
        $yPos = $this->pdf->getY();
        $this->pdf->setXY($xPos, $this->pdf->getY()+$yDep);
        $this->pdf->Cell(15, 0, "Date :", 0, 1, 'L', 0, '', 0);
        if ($this->oLicence->getDateMedical() != null)
        {
            $this->pdf->setXY($xPos+15, $yPos+$yDep);
            $this->pdf->SetFont('helvetica', 'B', 10);
            $this->pdf->Cell(30, 0, format_date($this->oLicence->getDateMedical(), 'dd MMMM yyyy'), 0, 1, 'L', 0, '', 0);
            $this->pdf->SetFont('helvetica', '', 10);
        }

        $this->pdf->setXY($xPos, $this->pdf->getY()+$yDep);
        if ($this->bCertificatValide) {
            $sSoussigne = 'Je soussigné(e), '.strtoupper($this->oProfilClub->getLastName()).', '.$this->oProfilClub->getFirstName().' au vu du certificat médical en ma possession et établi par le Docteur en médecine '.strtoupper($this->oLicence->getLastnameDoctor()).', '.strtoupper($this->oLicence->getFirstnameDoctor()).', '.strtoupper($this->oLicence->getRpps()).' ayant certifié que l\'état de santé de :';
            $this->pdf->MultiCell(140, 0, $sSoussigne, 0, 'L');
        } else {
            $this->pdf->Cell(80, 0, "Je soussigné(e), Docteur en médecine :", 0, 1, 'L', 0, '', 0);
            $this->pdf->setXY($xPos, $this->pdf->getY()+$yDep);
            $this->pdf->Cell(55, 0, "Certifie que l'état de santé de :", 0, 1, 'L', 0, '', 0);
        }

        $yPos = $this->pdf->getY()+$yDep;
        $this->pdf->setXY($xPos, $this->pdf->getY()+$yDep+$yDep);
        $this->pdf->Cell(28, 0, "Nom, prénom :", 0, 1, 'L', 0, '', 0);
        $this->pdf->setXY($xPos+28, $yPos+$yDep);
        $this->pdf->SetFont('helvetica', 'B', 10);
        $this->pdf->Cell(60, 0, $this->oProfil->getLastName().', '.$this->oProfil->getFirstName(), 0, 1, 'L', 0, '', 0);
        $this->pdf->SetFont('helvetica', '', 10);

        $yPos = $this->pdf->getY();
        $this->pdf->setXY($xPos, $this->pdf->getY()+$yDep);
        $this->pdf->Cell(18, 0, "Né(e) le :", 0, 1, 'L', 0, '', 0);
        $this->pdf->setXY($xPos+18, $yPos+$yDep);
        $this->pdf->SetFont('helvetica', 'B', 10);
        $this->pdf->Cell(30, 0, format_date($this->oProfil->getBirthday(), 'dd MMMM yyyy'), 0, 1, 'L', 0, '', 0);
        $this->pdf->SetFont('helvetica', '', 10);

        $this->pdf->setXY($xPos, $this->pdf->getY()+$yDep);
        $this->pdf->MultiCell(140, 0, Licence::getParam("ct_contre_indication"), 0, 'L');

        //Type de prestation
        $yPos = $this->pdf->getY()+$yDep;
        $this->pdf->setXY($xPos+10, $this->pdf->getY()+$yDep);
        $this->caseCheck($this->oGroupLicence->getCode()=='DIG'?true:false, 'en tant que cadre dirigeant bénévole', $xPos+10, $yPos, 70);
        $yPos = $this->pdf->getY()+$yDep;
        $this->pdf->setXY($xPos+10, $this->pdf->getY()+$yDep);
        $this->caseCheck($this->oGroupLicence->getCode()=='SPL'?true:false, 'dans le cadre de l\'activité sportive', $xPos+10, $yPos, 70);
        $yPos = $this->pdf->getY()+$yDep;
        $this->pdf->setXY($xPos+10, $this->pdf->getY()+$yDep);
        $bGroup = false;
        if ($this->oGroupLicence->getCode()=='COM') {
            $bGroup = true;
        }
        $this->caseCheck($bGroup, 'y compris en compétition', $xPos+10, $yPos, 70);



        //Cachet
        $xPos = $xPos-4;
        $yPos = $this->pdf->getY();
        $this->pdf->setXY($xPos, $this->pdf->getY()+$yDep);
        $this->pdf->Cell(140, 30, '', 1, 2, 'L', 0, '', 0);
        if ($this->bCertificatValide) {
            $this->pdf->setXY($xPos+4, $yPos+4);
            $this->pdf->MultiCell(130, 0, Licence::getParam("ct_signature"), 0, 'L');
            $yPos = $this->pdf->getY()-2;
            $this->pdf->setXY($xPos+4, $yPos);
            $this->pdf->Cell(30, 15, "Signature :", 0, 2, 'L', 0, '', 0);
            $this->pdf->setXY($xPos+80, $yPos);
            $this->pdf->Cell(30, 15, "Cachet du club :", 0, 2, 'L', 0, '', 0);
        } else {
            $this->pdf->setXY($xPos+4, $yPos+4);
            $this->pdf->Cell(30, 15, "Signature du médecin :", 0, 2, 'L', 0, '', 0);
            $this->pdf->setXY($xPos+80, $yPos+4);
            $this->pdf->Cell(30, 15, "Cachet du médecin :", 0, 2, 'L', 0, '', 0);
        }
        $this->getFooter();
    }



    private function getHeaderImage($xPostion)
    {
        $this->pdf->Image($this->image, $xPostion, 8, 36, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $this->pdf->SetFont('helvetica', 'B', 6);
        $this->pdf->setXY($xPostion-8, 30);
        $this->pdf->MultiCell(50, 0, Licence::getParam("ct_head"), 0, 'C');
    }

    private function getFooter()
    {
        $this->pdf->setY(175);
        $this->pdf->SetFont('helvetica', 'B', 8);
        $this->pdf->Cell(140, 0, Licence::getParam("ct_foot"), 0, 2, 'C', 0, '', 0);
        $this->pdf->SetFont('helvetica', '', 8);
        $this->pdf->Cell(140, 0, Licence::getParam("address_ffst"), 0, 2, 'C', 0, '', 0);
        $this->pdf->Cell(140, 0, Licence::getParam("ville_ffst"), 0, 2, 'C', 0, '', 0);
        $yPos = $this->pdf->getY();
        $xPos = $this->pdf->getX();
        $this->pdf->Cell(70, 0, "Mel : ", 0, 2, 'R', 0, '', 0);
        $this->pdf->setXY($xPos+70, $yPos);
        $this->pdf->SetFont('helvetica', 'I', 8);
        $HTML = '<a href="#">'.Licence::getParam("email_ffst").'</a>';
        $this->pdf->writeHTML($HTML, true, false, false, false, '');
    }
}
