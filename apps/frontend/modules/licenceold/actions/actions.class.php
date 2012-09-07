<?php

require_once dirname(__FILE__).'/../lib/licenceoldGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/licenceoldGeneratorHelper.class.php';

/**
 * licence actions.
 *
 * @package    sf_sandbox
 * @subpackage licence
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class licenceoldActions extends autoLicenceoldActions
{
  public function executeDelete(sfWebRequest $request)
  {
    $this->redirect('@licence_old');
  }

  public function executeEdit(sfWebRequest $request)
  {
    if ($this->getUser()->hasCredential('ValidLicence')) {
      $this->tbl_licence = $this->getRoute()->getObject();

      $this->form = $this->configuration->getForm($this->tbl_licence);
    } else {
      $this->redirect('@licence_old');
    }
  }

  public function executeNew(sfWebRequest $request)
  {
    if ($this->getUser()->hasCredential('ValidLicence')) {
      $this->form = $this->configuration->getForm();
      $this->tbl_licence = $this->form->getObject();
    } else {
      $this->redirect('@licence_old');
    }
  }
  public function executeListCancelLicence(sfWebRequest $request)
  {
    if ($this->getUser()->hasCredential('ValidLicence')) {
      $this->oLicence  = $this->getRoute()->getObject();
      $this->getUser()->setFlash('notice', 'Licence annulé: '.$this->oLicence->getTblProfil());
      $this->oLicence->setIsCancel(true)->save();
    }
    $this->redirect('@licence_old');
  }

  public function executeListImprimer(sfWebRequest $request)
  {
    if ($this->getUser()->hasCredential('ValidLicence')) {

      $oLicence = $this->getRoute()->getObject();
      if ($oLicence->getDateValidation() != null) {
        $oPdf = new PrintLicence($oLicence);
        $oPdf->createLic();

        $this->getUser()->setFlash('notice', 'Impression de la licence: '.$oLicence->getTblProfil());
      } else {
        $this->getUser()->setFlash('error', 'Licence pas valide: '.$oLicence->getTblProfil());
      }
    }
    $this->redirect('@licence_old');
  }
  public function executeListShow(sfWebRequest $request)
  {
    $this->oLicence  = $this->getRoute()->getObject();
    $this->oProfil   = $this->oLicence->getTblProfil();
    $this->oAddress  = $this->oProfil->getTblAddress();
    if ($this->oLicence->getIdFamilly()) {
      $this->oFamilly = Doctrine::getTable('tbl_profil')->find($this->oLicence->getIdFamilly());
    }
  }

  public function executeListExportData(sfWebRequest $request)
  {
    //$oLicences = Doctrine::getTable('tbl_licence')->findAll();
    $oLicences = $this->buildQuery()->execute();
    $sLicence = Licence::exportData($oLicences);
    header('Content-Type: application/csv') ; //on détermine les en-tête
    header('Content-Disposition: attachment; filename="licence.csv"');
    echo $sLicence;
    return sfView::HEADER_ONLY;
  }
}
