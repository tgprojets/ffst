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
      $this->redirect('licence/listPaypal');

    } elseif ($this->getUser()->hasCredential('licence') || $this->getUser()->isLigue()) {
      $this->validSaisie();
      $this->getUser()->setFlash('notice', 'Vous avez validé la saisie.');
      $this->redirect('@tbl_licence');
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
        $this->oPaymentClub        = Doctrine::getTable('tbl_payment')->findPaymentClub($oClub->getId());
        $this->oAvoirClub          = Doctrine::getTable('tbl_avoir')->findAvoirClub($oClub->getId());
        $this->nAmountClub         = Doctrine::getTable('tbl_payment')->getAmountClub($oClub->getId());
        $this->nAmountAvoirClub    = Doctrine::getTable('tbl_avoir')->getAmountAvoirClub($oClub->getId());
        $this->nAmountTotal        = $this->nAmountClub - $this->nAmountAvoirClub;
        $this->oBordereau          = $this->createBordereau($this->nAmountTotal);

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
    $oLicence = $this->getRoute()->getObject();
    if ($oLicence->getDateValidation() != null) {
      $this->getUser()->setFlash('notice', 'Impression de la licence: '.$oLicence->getTblProfil());
    } else {
      $this->getUser()->setFlash('error', 'Licence pas valide: '.$oLicence->getTblProfil());
    }
    $this->redirect('@tbl_licence');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $this->redirect('@tbl_licence');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->tbl_licence = $this->getRoute()->getObject();

    if ($this->tbl_licence->getIsBrouillon()) {
      $this->getUser()->setFlash('error', 'Impossible de modifié encours de saisie: '.$this->tbl_licence->getTblProfil());
      $this->redirect('@tbl_licence');
    }
    $this->form = $this->configuration->getForm($this->tbl_licence);
  }
}
