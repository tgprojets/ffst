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
        Doctrine::getTable('tbl_payment')->validSaisie($oClub->getId(), $this->getUser()->getGuardUser()->getId());
        Doctrine::getTable('tbl_avoir')->validSaisie($oClub->getId(), $this->getUser()->getGuardUser()->getId());
        $this->redirect('licence/listPaypal');

    } elseif ($this->getUser()->isLigue()) {
        $oLigue = $this->getUser()->getLigue();
        Doctrine::getTable('tbl_licence')->validSaisie(false, true, $oLigue->getId());

    } elseif ($this->getUser()->hasCredential('licence')) {
        Doctrine::getTable('tbl_licence')->validSaisieByUser($this->getUser()->getGuardUser()->getId());
        Doctrine::getTable('tbl_payment')->validSaisieByUser($this->getUser()->getGuardUser()->getId());
        Doctrine::getTable('tbl_avoir')->validSaisieByUser($this->getUser()->getGuardUser()->getId());
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
        Doctrine::getTable('tbl_payment')->cancelSaisie($oClub->getId(), $this->getUser()->getGuardUser()->getId());
        Doctrine::getTable('tbl_avoir')->cancelSaisie($oClub->getId(), $this->getUser()->getGuardUser()->getId());
    } elseif ($this->getUser()->isLigue()) {
        $oLigue = $this->getUser()->getLigue();
        Doctrine::getTable('tbl_licence')->cancelSaisie(false, true, $oLigue->getId());
    } elseif ($this->getUser()->hasCredential('licence')) {
        Doctrine::getTable('tbl_licence')->cancelSaisieByUser($this->getUser()->getGuardUser()->getId());
        Doctrine::getTable('tbl_payment')->cancelSaisieByUser($this->getUser()->getGuardUser()->getId());
        Doctrine::getTable('tbl_avoir')->cancelSaisieByUser($this->getUser()->getGuardUser()->getId());
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
        $this->oPaymentClub = Doctrine::getTable('tbl_payment')->findPaymentClub($oClub->getId(), true);
        $this->oAvoirClub   = Doctrine::getTable('tbl_avoir')->findAvoirClub($oClub->getId());
    } elseif ($this->getUser()->isLigue()) {
        $oLigue = $this->getUser()->getLigue();
        $this->oPaymentClub = Doctrine::getTable('tbl_payment')->findPaymentByLigue($oLigue->getId(), true);
        $this->oAvoirClub   = Doctrine::getTable('tbl_avoir')->findAvoirByLigue($oLigue->getId());
    } elseif ($this->getUser()->hasCredential('licence')) {
        $this->oPaymentClub = Doctrine::getTable('tbl_payment')->findPaymentByUser($this->getUser()->getGuardUser()->getId());
        $this->oAvoirClub   = Doctrine::getTable('tbl_avoir')->findAvoirByUser($this->getUser()->getGuardUser()->getId());
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
        $this->oPaymentClub        = Doctrine::getTable('tbl_payment')->findPaymentClub($oClub->getId());
        $this->oAvoirClub          = Doctrine::getTable('tbl_avoir')->findAvoirClub($oClub->getId());
        $this->nAmountClub         = Doctrine::getTable('tbl_payment')->getAmountClub($oClub->getId());
        $this->nAmountAvoirClub    = Doctrine::getTable('tbl_avoir')->getAmountAvoirClub($oClub->getId());
        $this->nAmountTotal   = $this->nAmountClub - $this->nAmountAvoirClub;
        $this->oBordereau = $this->createBordereau($this->nAmountTotal);
        $this->linkBordereau($this->oPaymentClub, $this->oBordereau->getId());
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
