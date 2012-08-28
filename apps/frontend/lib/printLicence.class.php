<?php

class PrintLicence {

    private $pdf;
    private $image;
    private $config;

    public function __construct()
    {
        $this->config = sfTCPDFPluginConfigHandler::loadConfig('config_lic');
        $this->pdf    = new sfTCPDF();
    }

    public function createLic()
    {
        // add a new page
        $this->pdf->AddPage();
        $this->image = sfConfig::get('sf_web_dir').'/images/'.PDF_HEADER_LOGO;
        $this->pdf->setPageOrientation(PDF_PAGE_ORIENTATION);
        $this->pdf->setEqualColumns(2, 150);
        $this->getLicence();
        $this->getMedical();
        // print chapter body
        $this->pdf->Output(sfConfig::get('sf_upload_dir').DIRECTORY_SEPARATOR.'test.pdf', 'FI');
        throw new sfStopException();
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
        $this->pdf->Cell(80, 0, "SAISON 20XX/20XX", 0, 2, 'L', 0, '', 0);
        $this->pdf->setXY(180, 10);

        //image et texte
        $this->pdf->Image($this->image, 240, 8, 36, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $this->pdf->SetFont('helvetica', 'B', 6);
        $this->pdf->setXY(233, 30);
        $this->pdf->Cell(80, 0, "Fédération Française des Sports de Traîneau,", 0, 1, 'L', 0, '', 0);
        $this->pdf->setXY(235, 32);
        $this->pdf->Cell(80, 0, "de ski-Pulka/Joëring, et de Cross Canins", 0, 1, 'L', 0, '', 0);

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
        $yDep = 4;
        $this->pdf->SetFont('helvetica', '', 11);
        $this->pdf->setXY(152, $this->pdf->getY()+$yDep);
        $this->pdf->Cell(80, 0, "Date :", 0, 1, 'L', 0, '', 0);
        $this->pdf->setXY(152, $this->pdf->getY()+$yDep);
        $this->pdf->Cell(80, 0, "Je soussigné(e), Docteur en médecine :", 0, 1, 'L', 0, '', 0);
        $this->pdf->setXY(152, $this->pdf->getY()+$yDep);
        $this->pdf->Cell(80, 0, "Certifie que l'état de santé de :", 0, 1, 'L', 0, '', 0);
        $this->pdf->setXY(152, $this->pdf->getY()+$yDep);
        $this->pdf->Cell(80, 0, "Nom, prénom :", 0, 1, 'L', 0, '', 0);
        $this->pdf->setXY(152, $this->pdf->getY()+$yDep);
        $this->pdf->Cell(80, 0, "Né(e) le :", 0, 1, 'L', 0, '', 0);
        $this->pdf->setXY(152, $this->pdf->getY()+$yDep);
        $this->pdf->Cell(140, 0, "Ne présente aucune contre-indication cliniquement décelable à la pratique", 0, 1, 'L', 0, '', 0);
        $this->pdf->setXY(152, $this->pdf->getY());
        $this->pdf->Cell(140, 0, "des Sports de traîneau, de ski-pulka/joëring et de cross canins", 0, 1, 'L', 0, '', 0);


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
        $this->pdf->Cell(70, 0, "SAISON 20XX/20XX", 0, 2, 'L', 0, '', 0);
        $this->pdf->Image($this->image, 107, 8, 36, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $this->pdf->SetFont('helvetica', 'B', 6);
        $this->pdf->setXY(92, 30);
        $this->pdf->Cell(80, 0, "Fédération Française des Sports de Traîneau,", 0, 2, 'L', 0, '', 0);
        $this->pdf->setXY(94, 32);
        $this->pdf->Cell(80, 0, "de ski-Pulka/Joëring, et de Cross Canins", 0, 2, 'L', 0, '', 0);

    }
}