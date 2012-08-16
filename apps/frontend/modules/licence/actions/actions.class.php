<?php

require_once dirname(__FILE__).'/../lib/licenceGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/licenceGeneratorHelper.class.php';

/**
 * licence actions.
 *
 * @package    sf_sandbox
 * @subpackage licence
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class licenceActions extends autoLicenceActions
{
  public function executeListValidSaisie(sfWebRequest $request)
  {
    $this->nValider = true;
    if ($this->getUser()->isClub())
    {
        $oClub = $this->getUser()->getClub();
        Doctrine::getTable('tbl_licence')->validSaisie(true, false, $oClub->getId());
        $this->oPaymentLic  = Doctrine::getTable('tbl_payment')->findPaymentLicByClub($oClub->getId());
        $this->oPaymentClub = Doctrine::getTable('tbl_payment')->findPaymentClub($oClub->getId());
        $this->oAvoirLic  = Doctrine::getTable('tbl_avoir')->findAvoirLicByClub($oClub->getId());
        $this->oAvoirClub = Doctrine::getTable('tbl_avoir')->findAvoirClub($oClub->getId());

    } elseif ($this->getUser()->isLigue()) {
        $oLigue = $this->getUser()->getLigue();
        Doctrine::getTable('tbl_licence')->validSaisie(false, true, $oLigue->getId());
        //$this->oPayment = Doctrine::getTable('tbl_payment')->findPaymentClub();
    } elseif ($this->getUser()->hasCredential('licence')) {
        //Doctrine::getTable('tbl_licence')->validSaisie(false, false, 0);
    } else {
        $this->redirect('@tbl_licence');
    }

    $this->setTemplate('validSaisie');
  }

  public function executeListCancelSaisie(sfWebRequest $request)
  {
    if ($this->getUser()->isClub())
    {
        $oClub = $this->getUser()->getClub();
        Doctrine::getTable('tbl_licence')->cancelSaisie(true, false, $oClub->getId());
    } elseif ($this->getUser()->isLigue()) {
        $oLigue = $this->getUser()->getLigue();
        Doctrine::getTable('tbl_licence')->cancelSaisie(false, true, $oLigue->getId());
    } elseif ($this->getUser()->hasCredential('licence')) {
        //Doctrine::getTable('tbl_licence')->cancelSaisie(false, false, 0);
    } else {
        $this->redirect('@tbl_licence');
    }

    $this->setTemplate('cancelSaisie');
  }
  public function executeListSaisie(sfWebRequest $request)
  {
    $this->nValider = false;
    if ($this->getUser()->isClub())
    {
        $oClub = $this->getUser()->getClub();
        $this->oPaymentLic  = Doctrine::getTable('tbl_payment')->findPaymentLicByClub($oClub->getId());
        $this->oPaymentClub = Doctrine::getTable('tbl_payment')->findPaymentClub($oClub->getId());
        $this->oAvoirLic  = Doctrine::getTable('tbl_avoir')->findAvoirLicByClub($oClub->getId());
        $this->oAvoirClub = Doctrine::getTable('tbl_avoir')->findAvoirClub($oClub->getId());
    } elseif ($this->getUser()->isLigue()) {
        $oLigue = $this->getUser()->getLigue();
        $this->oLicences = Doctrine::getTable('tbl_licence')->findSaisie(false, true, $oLigue->getId());
    } elseif ($this->getUser()->hasCredential('licence')) {
        //Doctrine::getTable('tbl_licence')->cancelSaisie(false, false, 0);
    } else {
        $this->redirect('@tbl_licence');
    }

    $this->setTemplate('validSaisie');
  }

  public function executeListPaypal(sfWebRequest $request)
  {
    if ($this->getUser()->isClub())
    {
        $oClub = $this->getUser()->getClub();
        $this->oPaymentLic    = Doctrine::getTable('tbl_payment')->findPaymentLicByClub($oClub->getId());
        $this->oPaymentClub   = Doctrine::getTable('tbl_payment')->findPaymentClub($oClub->getId());
        $this->oAvoirLic      = Doctrine::getTable('tbl_avoir')->findAvoirLicByClub($oClub->getId());
        $this->oAvoirClub     = Doctrine::getTable('tbl_avoir')->findAvoirLicByClub($oClub->getId());
        $this->nAmountLic     = Doctrine::getTable('tbl_payment')->getAmountLicByClub($oClub->getId());
        $this->nAmountClub    = Doctrine::getTable('tbl_payment')->getAmountClub($oClub->getId());
        $this->nAmountTotal   = $this->nAmountLic+$this->nAmountClub;
        $this->oBordereau = $this->createBordereau($this->nAmountTotal);
        $this->linkBordereau($this->oPaymentLic, $this->oBordereau->getId());
        $this->linkBordereau($this->oPaymentClub, $this->oBordereau->getId());
        $this->linkBordereau($this->oAvoirLic, $this->oBordereau->getId());
        $this->linkBordereau($this->oAvoirClub, $this->oBordereau->getId());
    } else {
        $this->redirect('@tbl_licence');
    }

    $this->setTemplate('paypal');
  }

  private function createBordereau($nAmount)
  {
    $nIdUser = $this->getUser()->getGuardUser()->getId();
    $oBordereaux = Doctrine::getTable('tbl_bordereau')->findBySql('id_user='.$nIdUser.' and is_payed = false');
    foreach ($oBordereaux as $oBordereau) {
        $oBordereau->delete();
    }
    $oBordereau     = new tbl_bordereau();
    $oBordereau->setLib('Paiement Licence')
                     ->setAmount($nAmount)
                     ->setIdUser($nIdUser)
                     ->save();
    return $oBordereau;
  }

  private function linkBordereau($oElements, $nIdBordereau)
  {
    foreach ($oElements as $oElement) {
        $oElement->setIdBordereau($nIdBordereau)->save();
    }
  }
}
