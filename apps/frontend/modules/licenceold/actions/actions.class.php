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
    $this->redirect('@licence_old');
  }

  public function executeNew(sfWebRequest $request)
  {
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
}
