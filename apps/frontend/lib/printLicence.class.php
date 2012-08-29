<?php

class PrintLicence {

    private $pdf;
    private $image;
    private $config;
    private $oLicence;
    private $oProfil;
    private $oCodePostal;
    private $oTypeLicence;
    private $oCategory;

    public function __construct($oLicence)
    {
        $this->config       = sfTCPDFPluginConfigHandler::loadConfig('config_lic');
        $this->pdf          = new sfTCPDF();
        $this->oLicence     = $oLicence;
        $this->oProfil      = $oLicence->getTblProfil();
        $this->oAddress     = $this->oProfil->getTblAddress();
        $this->oCodePostal  = $this->oAddress->getTblCodepostaux();
        $this->oTypeLicence = $oLicence->getTblTypelicence();
        $this->oCategory    = $oLicence->getTblCategory();
    }

    public function createLic()
    {
        // add a new page
        sfContext::getInstance()->getConfiguration()->loadHelpers('Date');

        $this->image = sfConfig::get('sf_web_dir').'/images/'.PDF_HEADER_LOGO;
        $this->pdf->setPageOrientation(PDF_PAGE_ORIENTATION);
        $this->pdf->setEqualColumns(2, 150);

        $this->pdf->SetFont("helvetica", "", 12);
        $this->pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $this->pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $this->pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $this->pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $this->pdf->AddPage();

        $this->getLicence();
        $this->getMedical();
        // print chapter body
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

    }

    private function getMedical()
    {
        $this->pdf->selectColumn(1);

        //Titre
        $this->pdf->setY(14);
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
        $this->getHeaderImage(240);

        //Bloc important
        $this->pdf->setXY(150, 36);
        $this->pdf->Cell(140, 20, '', 1, 2, 'L', 0, '', 0);
        $this->pdf->SetFont('helvetica', '', 11);
        $this->pdf->setXY(150, 37);
        $this->pdf->Cell(140, 0, "IMPORTANT", 0, 2, 'C', 0, '', 0);
        $this->pdf->setXY(150, 41);
        $this->pdf->Cell(140, 0, "Cette attestation de licence n'est valable qu'avec le présent certificat", 0, 1, 'C', 0, '', 0);
        $this->pdf->setXY(150, 45);
        $this->pdf->Cell(140, 0, "Complétée, tamponée et signée par un medecin ou bien qu'accompagnée", 0, 1, 'C', 0, '', 0);
        $this->pdf->setXY(150, 49);
        $this->pdf->Cell(140, 0, "d'un certificat médical rédigé dans les même termes datant de moins 6 mois.", 0, 1, 'C', 0, '', 0);

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
        $this->pdf->Cell(140, 0, "Ne présente aucune contre-indication cliniquement décelable à la pratique", 0, 1, 'L', 0, '', 0);
        $this->pdf->setXY($xPos, $this->pdf->getY());
        $this->pdf->Cell(140, 0, "des Sports de traîneau, de ski-pulka/joëring et de cross canins", 0, 1, 'L', 0, '', 0);

        //Type de prestation
        $this->pdf->setXY($xPos, $this->pdf->getY()+$yDep);
        $html = '
        <input type="checkbox" name="dirigeant" value="1" checked="checked" />
            <label for="dirigeant">en tant que cadre dirigeant bénévole </label>';
        $this->pdf->writeHTML($html, true, false, false, false, '');

        $this->pdf->setXY($xPos, $this->pdf->getY()+$yDep);
        $html = '
        <input type="checkbox" name="sport" value="1" checked="checked" />
            <label for="sport">dans le cadre de l\'activité sportive </label>';
        $this->pdf->writeHTML($html, true, false, false, false, '');

        $this->pdf->setXY($xPos, $this->pdf->getY()+$yDep);
        $html = '
        <input type="checkbox" name="competition" value="1" checked="checked" />
            <label for="competition">y compris en compétition </label>';
        $this->pdf->writeHTML($html, true, false, false, false, '');

        //Cachet
        $xPos = $xPos-4;
        $yPos = $this->pdf->getY();
        $this->pdf->setXY($xPos, $this->pdf->getY()+$yDep);
        $this->pdf->Cell(140, 30, '', 1, 2, 'L', 0, '', 0);
        $this->pdf->SetFont('helvetica', '', 11);
        $this->pdf->setXY($xPos+4, $yPos-2);
        $this->pdf->Cell(130, 15, "Cachet du médecin :", 0, 2, 'L', 0, '', 0);
        $this->pdf->Cell(130, 15, "Signature du médecin :", 0, 2, 'L', 0, '', 0);
    }



    private function getHeaderImage($xPostion)
    {
        $this->pdf->Image($this->image, $xPostion, 8, 36, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $this->pdf->SetFont('helvetica', 'B', 6);
        $this->pdf->setXY($xPostion-6, 30);
        $this->pdf->Cell(80, 0, "Fédération Française des Sports de Traîneau,", 0, 2, 'L', 0, '', 0);
        $this->pdf->setXY($xPostion-4, 32);
        $this->pdf->Cell(80, 0, "de ski-Pulka/Joëring, et de Cross Canins", 0, 2, 'L', 0, '', 0);
    }
}