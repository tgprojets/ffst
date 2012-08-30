<?php

class PrintLicence {

    private $pdf;
    private $image;
    private $config;
    private $oLicence;
    private $oProfil;
    private $oCodePostal;
    private $oTypeLicence;
    private $oGroupLicence;
    private $oCategory;

    public function __construct($oLicence)
    {
        $this->config        = sfTCPDFPluginConfigHandler::loadConfig('config_lic');
        $this->pdf           = new ffstTCPDF();
        $this->oLicence      = $oLicence;
        $this->oProfil       = $oLicence->getTblProfil();
        $this->oAddress      = $this->oProfil->getTblAddress();
        $this->oCodePostal   = $this->oAddress->getTblCodepostaux();
        $this->oTypeLicence  = $oLicence->getTblTypelicence();
        $this->oCategory     = $oLicence->getTblCategory();
        $this->oGroupLicence = $this->oTypeLicence->getTblGrouplicence();
    }

    public function createLic()
    {
        // add a new page
        sfContext::getInstance()->getConfiguration()->loadHelpers('Date');

        $this->image = sfConfig::get('sf_web_dir').'/images/'.PDF_HEADER_LOGO;
        $imageBack = sfConfig::get('sf_web_dir').'/images/background.jpg';
        $this->pdf->setPageOrientation(PDF_PAGE_ORIENTATION);
        $this->pdf->setEqualColumns(2, 150);

        $this->pdf->SetFont("helvetica", "", 12);

        $this->pdf->AddPage();
        $this->pdf->Image($imageBack, 0, 50, 150, 93, '', '', '', false, 300, 'C', false, false, 0);

        $this->getLicence();
        $this->getMedical();

        $this->pdf->Output(sfConfig::get('sf_upload_dir').DIRECTORY_SEPARATOR.'test.pdf', 'FI');
        throw new sfStopException();
    }

    private function getLicence()
    {
        $this->pdf->selectColumn();
        $this->pdf->setY(14);
        $this->pdf->SetFont('courier', '', 11);
        $this->pdf->Cell(80, 0, 'SUGGESTION TRACE "ATTESTATION DE LICENCE"', 0, 2, 'L', 0, '', 0);
        $this->pdf->setXY(12, 20);
        $this->pdf->Cell(80, 14, '', 1, 2, 'C', 0, '', 0);
        $this->pdf->setXY(14, 22);
        $this->pdf->SetFont('courier', 'B', 13);
        $this->pdf->Cell(70, 0, "ATTESTATION DE LICENCE FFST", 0, 2, 'L', 0, '', 0);
        $this->pdf->setXY(14, 26);
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
        $this->pdf->Cell(50, 0, format_date($this->oLicence->getDateValidation(), 'dd MMMM yyyy'), 0, 1, 'L', 0, '', 0);

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
        $xPos = 80;
        $this->pdf->setY($yPos);
        $this->pdf->Cell(70, 10, '', 1, 2, 'L', 0, '', 0);
        $this->pdf->setY($yPos);
        $this->pdf->MultiCell(70, 0, $this->oGroupLicence->getLib(), 0, 'L');
        $this->pdf->setXY(80, $yPos);
        $this->pdf->Cell(60, 10, '', 1, 2, 'L', 0, '', 0);
        $this->pdf->setXY(80, $yPos);
        if ($this->oGroupLicence->getCode() == 'MON' || $this->oGroupLicence->getCode() == 'ATT')
        {
            $this->caseCheck(true, $this->oCategory->getLib(), $xPos, $yPos, 40);
            $yPos = $yPos+4;
            $this->pdf->setXY(80, $yPos);
            $this->caseCheck($this->oTypeLicence->getIsMinor(), '< 18 ans', $xPos, $yPos, 20);
            $xPos += 20;
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

        //Titre
        $this->pdf->setXY(150, 14);
        $this->pdf->SetFont('courier', '', 11);
        $this->pdf->Cell(75, 0, 'SUGGESTION TRACE "CERTIFICAT MEDICAL"', 0, 2, 'L', 0, '', 0);

        //Bloc titre
        $this->pdf->setXY(152, 20);
        $this->pdf->Cell(54, 14, '', 1, 2, 'C', 0, '', 0);
        $this->pdf->setXY(154, 22);
        $this->pdf->SetFont('courier', 'B', 13);
        $this->pdf->Cell(80, 0, "CERTIFICAT MEDICAL", 0, 2, 'L', 0, '', 0);
        $this->pdf->setXY(154, 26);
        $this->pdf->Cell(80, 0, "SAISON ".$this->oLicence->getYearLicence(), 0, 2, 'L', 0, '', 0);
        $this->pdf->setXY(180, 10);

        //image et texte
        $this->getHeaderImage(245);

        //Bloc important
        $this->pdf->setXY(150, 36);
        $this->pdf->Cell(140, 20, '', 1, 2, 'L', 0, '', 0);
        $this->pdf->SetFont('helvetica', '', 11);
        $this->pdf->setXY(150, 37);
        $this->pdf->Cell(140, 0, "IMPORTANT", 0, 2, 'C', 0, '', 0);
        $this->pdf->setXY(150, 41);
        $this->pdf->MultiCell(140, 0, "Cette attestation de licence n'est valable qu'avec le présent certificat Complétée, tamponée et signée par un medecin ou bien qu'accompagnée d'un certificat médical rédigé dans les même termes datant de moins 6 mois.", 0, 'C');

        //Champs
        $yDep = 3;
        $xPos = 152;
        $this->pdf->SetFont('helvetica', '', 11);
        $yPos = $this->pdf->getY();
        $this->pdf->setXY($xPos, $this->pdf->getY()+$yDep);
        $this->pdf->Cell(15, 0, "Date :", 0, 1, 'L', 0, '', 0);
        if ($this->oLicence->getDateMedical() != null)
        {
            $this->pdf->setXY($xPos+15, $yPos+$yDep);
            $this->pdf->SetFont('helvetica', 'B', 11);
            $this->pdf->Cell(30, 0, format_date($this->oLicence->getDateMedical(), 'dd MMMM yyyy'), 0, 1, 'L', 0, '', 0);
            $this->pdf->SetFont('helvetica', '', 11);
        }

        $this->pdf->setXY($xPos, $this->pdf->getY()+$yDep);
        $this->pdf->Cell(80, 0, "Je soussigné(e), Docteur en médecine :", 0, 1, 'L', 0, '', 0);

        $yPos = $this->pdf->getY();
        $this->pdf->setXY($xPos, $this->pdf->getY()+$yDep);
        $this->pdf->Cell(55, 0, "Certifie que l'état de santé de :", 0, 1, 'L', 0, '', 0);
        $this->pdf->setXY($xPos+55, $yPos+$yDep);
        $this->pdf->SetFont('helvetica', 'B', 11);
        $this->pdf->Cell(60, 0, $this->oProfil->getName(), 0, 1, 'L', 0, '', 0);
        $this->pdf->SetFont('helvetica', '', 11);

        $yPos = $this->pdf->getY();
        $this->pdf->setXY($xPos, $this->pdf->getY()+$yDep);
        $this->pdf->Cell(28, 0, "Nom, prénom :", 0, 1, 'L', 0, '', 0);
        $this->pdf->setXY($xPos+28, $yPos+$yDep);
        $this->pdf->SetFont('helvetica', 'B', 11);
        $this->pdf->Cell(60, 0, $this->oProfil->getLastName().', '.$this->oProfil->getFirstName(), 0, 1, 'L', 0, '', 0);
        $this->pdf->SetFont('helvetica', '', 11);

        $yPos = $this->pdf->getY();
        $this->pdf->setXY($xPos, $this->pdf->getY()+$yDep);
        $this->pdf->Cell(18, 0, "Né(e) le :", 0, 1, 'L', 0, '', 0);
        $this->pdf->setXY($xPos+18, $yPos+$yDep);
        $this->pdf->SetFont('helvetica', 'B', 11);
        $this->pdf->Cell(30, 0, format_date($this->oProfil->getBirthday(), 'dd MMMM yyyy'), 0, 1, 'L', 0, '', 0);
        $this->pdf->SetFont('helvetica', '', 11);

        $this->pdf->setXY($xPos, $this->pdf->getY()+$yDep);
        $this->pdf->MultiCell(140, 0, "Ne présente aucune contre-indication cliniquement décelable à la pratique des Sports de traîneau, de ski-pulka/joëring et de cross canins", 0, 'L');

        //Type de prestation
        $yPos = $this->pdf->getY()+$yDep;
        $this->pdf->setXY($xPos, $this->pdf->getY()+$yDep);
        $this->caseCheck($this->oGroupLicence->getCode()=='DIG'?true:false, 'en tant que cadre dirigeant bénévole', $xPos, $yPos, 70);
        $yPos = $this->pdf->getY()+$yDep;
        $this->pdf->setXY($xPos, $this->pdf->getY()+$yDep);
        $this->caseCheck($this->oGroupLicence->getCode()=='SPL'?true:false, 'dans le cadre de l\'activité sportive', $xPos, $yPos, 70);
        $yPos = $this->pdf->getY()+$yDep;
        $this->pdf->setXY($xPos, $this->pdf->getY()+$yDep);
        $bGroup = false;
        if ($this->oGroupLicence->getCode()=='MON' || $this->oGroupLicence->getCode()=='ATT') {
            $bGroup = true;
        }
        $this->caseCheck($bGroup, 'y compris en compétition', $xPos, $yPos, 70);



        //Cachet
        $xPos = $xPos-4;
        $yPos = $this->pdf->getY();
        $this->pdf->setXY($xPos, $this->pdf->getY()+$yDep);
        $this->pdf->Cell(140, 30, '', 1, 2, 'L', 0, '', 0);
        $this->pdf->SetFont('helvetica', '', 11);
        $this->pdf->setXY($xPos+4, $yPos-2);
        $this->pdf->Cell(130, 15, "Cachet du médecin :", 0, 2, 'L', 0, '', 0);
        $this->pdf->Cell(130, 15, "Signature du médecin :", 0, 2, 'L', 0, '', 0);
        $this->getFooter();
    }



    private function getHeaderImage($xPostion)
    {
        $this->pdf->Image($this->image, $xPostion, 8, 36, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $this->pdf->SetFont('helvetica', 'B', 6);
        $this->pdf->setXY($xPostion-8, 30);
        $this->pdf->MultiCell(50, 0, "Fédération Française des Sports de Traîneau, de ski-Pulka/Joëring, et de Cross Canins", 0, 'C');
    }

    private function getFooter()
    {
        $this->pdf->setY(175);
        $this->pdf->SetFont('helvetica', 'B', 8);
        $this->pdf->Cell(140, 0, "Fédération Délégataire agréée par le ministère des Sports", 0, 2, 'C', 0, '', 0);
        $this->pdf->SetFont('helvetica', '', 8);
        $this->pdf->Cell(140, 0, "130, chemin des Charbonneaux", 0, 2, 'C', 0, '', 0);
        $this->pdf->Cell(140, 0, "38250 LANS EN VERCORS", 0, 2, 'C', 0, '', 0);
        $yPos = $this->pdf->getY();
        $xPos = $this->pdf->getX();
        $this->pdf->Cell(70, 0, "Mel : ", 0, 2, 'R', 0, '', 0);
        $this->pdf->setXY($xPos+70, $yPos);
        $this->pdf->SetFont('helvetica', 'I', 8);
        $HTML = '<a href="#">ffst@free.fr</a>';
        $this->pdf->writeHTML($HTML, true, false, false, false, '');
    }
}