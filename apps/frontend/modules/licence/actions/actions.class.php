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
      $this->validSaisie();

    } elseif ($this->getUser()->hasCredential('licence') || $this->getUser()->isLigue()) {
      $this->validSaisie();
      $this->getUser()->setFlash('notice', 'Vous avez validé la saisie.');
    }
    $this->redirect('@tbl_licence');
  }

  private function validSaisie()
  {
    Doctrine::getTable('tbl_licence')->validSaisieByUser($this->getUser()->getGuardUser()->getId());
    Doctrine::getTable('tbl_payment')->validSaisieByUser($this->getUser()->getGuardUser()->getId());
    Doctrine::getTable('tbl_avoir')->validSaisieByUser($this->getUser()->getGuardUser()->getId());
  }

  public function executeListCancelSaisie(sfWebRequest $request)
  {
    if ($this->getUser()->isClub() || $this->getUser()->isLigue() || $this->getUser()->hasCredential('licence'))
    {
      $this->cancelSaisie();
      $this->getUser()->setFlash('notice', 'Vous avez annulé la saisie.');
    }
    $this->redirect('@tbl_licence');
  }

  private function cancelSaisie()
  {
    Doctrine::getTable('tbl_licence')->cancelSaisieByUser($this->getUser()->getGuardUser()->getId());
    Doctrine::getTable('tbl_payment')->cancelSaisieByUser($this->getUser()->getGuardUser()->getId());
    Doctrine::getTable('tbl_avoir')->cancelSaisieByUser($this->getUser()->getGuardUser()->getId());
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
      $this->oPaymentClub = Doctrine::getTable('tbl_payment')->findPaymentByUser($this->getUser()->getGuardUser()->getId());
      $this->oAvoirClub   = Doctrine::getTable('tbl_avoir')->findAvoirByUser($this->getUser()->getGuardUser()->getId());
    } elseif ($this->getUser()->hasCredential('licence')) {
      $this->oPaymentClub = Doctrine::getTable('tbl_payment')->findPaymentByUser($this->getUser()->getGuardUser()->getId());
      $this->oAvoirClub   = Doctrine::getTable('tbl_avoir')->findAvoirByUser($this->getUser()->getGuardUser()->getId());
    } else {
      $this->redirect('@tbl_licence');
    }

    $this->setTemplate('saisie');
  }
  public function executeListPaypal(sfWebRequest $request)
  {
    if ($this->getUser()->isClub())
    {
        $oClub = $this->getUser()->getClub();
        $oTypePayment = Doctrine::getTable('tbl_typepayment')->findOneBy('slug', 'paypal');
        //Récupère le bordereau libre
        $this->oBordereau = Doctrine::getTable('tbl_bordereau')->getLastExist($oClub->getId(), $oTypePayment->getId(), false);
        $oPaymentClubNoBordereau        = Doctrine::getTable('tbl_payment')->findPaymentClubBordereau($oClub->getId());
        $oAvoirClubNoBordereau          = Doctrine::getTable('tbl_avoir')->findAvoirClubBordereau($oClub->getId());

        if (!$this->oBordereau && $oPaymentClubNoBordereau->count() > 0) {
          $this->oBordereau          = $this->createBordereau($oClub->getId(), $oTypePayment);
        } elseif (!$this->oBordereau) {
          $this->redirect('tbl_bordereau');
        }

        $this->linkBordereau($oPaymentClubNoBordereau, $this->oBordereau->getId());
        $this->linkBordereau($oAvoirClubNoBordereau, $this->oBordereau->getId());

        $this->oPaymentClub        = Doctrine::getTable('tbl_payment')->findPaymentClubBordereau($oClub->getId(), $this->oBordereau->getId());
        $this->oAvoirClub          = Doctrine::getTable('tbl_avoir')->findAvoirClubBordereau($oClub->getId(), $this->oBordereau->getId());
        $this->nAmountClub         = Doctrine::getTable('tbl_payment')->getAmountClubBordereau($oClub->getId(), $this->oBordereau->getId());
        $this->nAmountAvoirClub    = Doctrine::getTable('tbl_avoir')->getAmountAvoirClubBordereau($oClub->getId(), $this->oBordereau->getId());
        $this->nAmountTotal        = $this->nAmountClub - $this->nAmountAvoirClub;
        $this->oBordereau->setAmount($this->nAmountTotal)->save();
        $this->sClub = $oClub->getName();
    } else {
        $this->redirect('@tbl_licence');
    }

    $this->setTemplate('paypal');
  }

  public function executeListCheque(sfWebRequest $request)
  {
    if ($this->getUser()->isClub())
    {
        $oClub = $this->getUser()->getClub();
        $oTypePayment = Doctrine::getTable('tbl_typepayment')->findOneBy('slug', 'cheque');
        //Récupère le bordereau libre
        $this->oBordereau = Doctrine::getTable('tbl_bordereau')->getLastExist($oClub->getId(), $oTypePayment->getId(), false);

        $oPaymentClubNoBordereau        = Doctrine::getTable('tbl_payment')->findPaymentClubBordereau($oClub->getId());
        $oAvoirClubNoBordereau          = Doctrine::getTable('tbl_avoir')->findAvoirClubBordereau($oClub->getId());

        if (!$this->oBordereau && $oPaymentClubNoBordereau->count() > 0) {
          $this->oBordereau          = $this->createBordereau($oClub->getId(), $oTypePayment);
        } elseif (!$this->oBordereau) {
          $this->redirect('tbl_bordereau');
        }

        $this->linkBordereau($oPaymentClubNoBordereau, $this->oBordereau->getId());
        $this->linkBordereau($oAvoirClubNoBordereau, $this->oBordereau->getId());

        $this->oPaymentClub        = Doctrine::getTable('tbl_payment')->findPaymentClubBordereau($oClub->getId(), $this->oBordereau->getId());
        $this->oAvoirClub          = Doctrine::getTable('tbl_avoir')->findAvoirClubBordereau($oClub->getId(),$this->oBordereau->getId());
        $this->nAmountClub         = Doctrine::getTable('tbl_payment')->getAmountClubBordereau($oClub->getId(), $this->oBordereau->getId());
        $this->nAmountAvoirClub    = Doctrine::getTable('tbl_avoir')->getAmountAvoirClubBordereau($oClub->getId(), $this->oBordereau->getId());
        $this->nAmountTotal        = $this->nAmountClub - $this->nAmountAvoirClub;
        $this->oBordereau->setAmount($this->nAmountTotal)->save();

    } else {
        $this->redirect('@tbl_licence');
    }

    $this->setTemplate('cheque');
  }


  private function createBordereau($nIdClub, $oType)
  {
    $nIdUser      = $this->getUser()->getGuardUser()->getId();

    $oBordereau     = new tbl_bordereau();
    $oBordereau->setLib('Paiement Licence par '.$oType->getLib())
                     ->setIdUser($nIdUser)
                     ->setIdClub($nIdClub)
                     ->setNum(Licence::getNumBordereau())
                     ->setIdTypepayment($oType->getId())
                     ->save();
    return $oBordereau;
  }

  private function linkBordereau($oElements, $nIdBordereau)
  {
    foreach ($oElements as $oElement) {
        $oElement->setIdBordereau($nIdBordereau)->save();
    }
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $notice = $form->getObject()->isNew() ? 'Licence créé avec succès.' : 'Licence modifié avec succès.';

      try {
        $tbl_licence = $form->save();
      } catch (Doctrine_Validator_Exception $e) {

        $errorStack = $form->getObject()->getErrorStack();

        $message = get_class($form->getObject()) . ' has ' . count($errorStack) . " field" . (count($errorStack) > 1 ?  's' : null) . " with validation errors: ";
        foreach ($errorStack as $field => $errors) {
            $message .= "$field (" . implode(", ", $errors) . "), ";
        }
        $message = trim($message, ', ');

        $this->getUser()->setFlash('error', $message);
        return sfView::SUCCESS;
      }

      $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $tbl_licence)));

      if ($request->hasParameter('_save_and_add'))
      {
        $this->getUser()->setFlash('notice', $notice.' You can add another one below.');

        $this->redirect('@tbl_licence_new');
      } elseif ($request->hasParameter('_save_and_saisie')) {
        $this->getUser()->setFlash('notice', $notice.' Votre saisie.');

        $this->redirect('licence/ListSaisie');

      } elseif ($request->hasParameter('_save_and_payed')) {
        $this->getUser()->setFlash('notice', $notice.' Modification enregistré.');

        if ($this->getUser()->hasToPayed()) {
          $this->redirect('licence/ListPaypal');
        } else {
          $this->redirect('@tbl_licence');
        }


      } else {
        $this->getUser()->setFlash('notice', $notice);

        $this->redirect(array('sf_route' => 'tbl_licence_edit', 'sf_subject' => $tbl_licence));
      }
    }
    else
    {
      $this->getUser()->setFlash('error', 'The item has not been saved due to some errors.', false);
    }
  }

  public function executeListValidLicence(sfWebRequest $request)
  {
    $oLicence = $this->getRoute()->getObject();
    if ($this->getUser()->hasCredential('ValidLicence')) {
      $this->getUser()->setFlash('notice', 'Licence validé: '.$oLicence->getTblProfil());
      $oLicence->setDateValidation(date("Y-m-d H:i:s"))->save();
    }
    $this->redirect('@tbl_licence');
  }

  public function executeListImprimer(sfWebRequest $request)
  {
    if ($this->getUser()->hasCredential('account_club') ||
        $this->getUser()->hasCredential('licence')) {


      $oLicence = $this->getRoute()->getObject();
      if ($oLicence->getDateValidation() != null) {
        $oPdf = new PrintLicence($oLicence);
        $oPdf->createLic();

        $this->getUser()->setFlash('notice', 'Impression de la licence: '.$oLicence->getTblProfil());
      } else {
        $this->getUser()->setFlash('error', 'Licence pas valide: '.$oLicence->getTblProfil());
      }
    }
    $this->redirect('@tbl_licence');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $this->redirect('@tbl_licence');
  }

  public function executeEdit(sfWebRequest $request)
  {
    if ($this->getUser()->isLigue()) {
      $this->redirect('@tbl_licence');
    }
    $this->tbl_licence = $this->getRoute()->getObject();

    if ($this->tbl_licence->getYearLicence() != Licence::getDateLicence()) {
      $this->redirect('@tbl_licence');
    }
    $this->form = $this->configuration->getForm($this->tbl_licence);
  }

  public function executeNew(sfWebRequest $request)
  {
    if ($this->getUser()->isLigue()) {
      $this->redirect('@tbl_licence');
    }
    $this->form = $this->configuration->getForm();
    $this->tbl_licence = $this->form->getObject();
  }

  public function executeListShow(sfWebRequest $request)
  {
    $oLicences = $this->buildQuery()->execute();
    $this->oLicence  = $this->getRoute()->getObject();

    $this->preview=0;
    $this->next=0;
    $bPreview=false;
    $bNext=false;
    foreach($oLicences as $oLicence)
    {
      if ($bNext) {
        $this->next = $oLicence->getId();
        break;
      }
      if ($oLicence->getId() == $this->oLicence->getId()) {
        $bPreview=true;
        $bNext=true;
      }
      if ($bPreview==false) {
        $this->preview = $oLicence->getId();
      }
    }
    $this->oProfil   = $this->oLicence->getTblProfil();
    $this->oAddress  = $this->oProfil->getTblAddress();
    if ($this->oLicence->getIdFamilly()) {
      $this->oFamilly = Doctrine::getTable('tbl_profil')->find($this->oLicence->getIdFamilly());
    }
  }

  public function executeListCancelLicence(sfWebRequest $request)
  {
    $this->oLicence  = $this->getRoute()->getObject();
    if ($this->getUser()->hasCredential('ValidLicence')) {
      $this->getUser()->setFlash('notice', 'Licence annulé: '.$this->oLicence->getTblProfil());
      $this->oLicence->setIsCancel(true)->save();
    }
    $this->redirect('@tbl_licence');
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
