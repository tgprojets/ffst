<?php

require_once dirname(__FILE__).'/../lib/sfGuardUserGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/sfGuardUserGeneratorHelper.class.php';

/**
 * sf_guard_user actions.
 *
 * @package    sfGuardPlugin
 * @subpackage sf_guard_user
 * @author     Thomas GILBERT <tgilbert@tgprojets.fr>
 * @version    1.0 GIT
 */
class sfGuardUserActions extends autoSfGuardUserActions
{
  public function executeEditPassword(sfWebRequest $request) {
    $sBack = $this->getUser()->getAttribute('back_password');
    if ($request->hasParameter('id'))
    {
      $this->oUser = Doctrine::getTable('sfGuardUser')->find($request->getParameter('id'));
      $this->form = new ModifpasswordForm(array(), array('id' => $this->oUser->getId()));
      if ($request->isMethod('post')) {
          $this->form->bind($request->getParameter('modifpassword'));
          if ($this->form->isValid())
          {
             $aValues = $this->form->getValues();
             $this->oUser->setPassword($aValues['password']);
             $this->oUser->save();
             $this->getUser()->setFlash('notice', 'Mot de passe: '.$aValues['password'].' enregistré.');
             $this->redirect($sBack);
          }
      }
      $this->setTemplate('editpassword');
    } else {
      $this->redirect($sBack);
    }
  }
  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {

      $notice = $form->getObject()->isNew() ? 'The item was created successfully.' : 'The item was updated successfully.';
      try {
        $sf_guard_user = $form->save();
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

      $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $sf_guard_user)));

      if ($request->hasParameter('_save_and_add'))
      {
        $this->getUser()->setFlash('notice', $notice.' You can add another one below.');

        $this->redirect('@sf_guard_user_new');
      } elseif ($request->hasParameter('_save_and_list')) {
          $this->getUser()->setFlash('notice', $this->getUser()->getFlash('notice') );
          $this->redirect('@sf_guard_user');
      }
      else
      {
        $this->getUser()->setFlash('notice', $notice);

        $this->redirect(array('sf_route' => 'sf_guard_user_edit', 'sf_subject' => $sf_guard_user));
      }
    }
    else
    {
      $this->getUser()->setFlash('error', 'The item has not been saved due to some errors.', false);
    }
  }
  public function executeListEditPassword(sfWebRequest $request)
  {
    $this->oUser = $this->getRoute()->getObject();
    $this->getUser()->setAttribute('back_password', '@sf_guard_user');
    $request->setParameter('id', $this->oUser->getId());
    $this->forward('sfGuardUser', 'editPassword');
  }
  /**
   * Active ou désactive un client
   *
   * @param sfWebRequest $request
   */
  public function executeListActivate(sfWebRequest $request) {
    $oUser = $this->getRoute()->getObject();
    $oUser->setIsActive(!$oUser->getIsActive());
    $oUser->save();
    $this->redirect('@sf_guard_user');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $oUser = $this->getRoute()->getObject();

    if ($oUser->getTblLicence()->count() > 0) {
      $oLicences = $oUser->getTblLicence();
      foreach($oLicences as $oLicence)
      {
        if ($oLicence->getDateValidation() == null ) {
          $this->getUser()->setFlash('error', 'Impossible de supprimer cet utilisateur car il a créé des licences non payées.');
          $this->redirect('@sf_guard_user');
        } else {
          $oLicence->setIdUser(null)->save();
        }
      }
    }

    if ($oUser->getTblPayment()->count() > 0) {
        $oPayments = $oUser->getTblPayment();
        foreach ($oPayments as $oPayment)
        {
          if ($oPayment->getIsPayed() == false) {
            $this->getUser()->setFlash('error', 'Impossible de supprimer cet utilisateur a saisi des paiements non payées.');
            $this->redirect('@sf_guard_user');
          } else {
            $oPayment->setIdUser(null)->save();
          }
        }
    }

    if ($oUser->getTblAvoir()->count() > 0) {
        $oPayments = $oUser->getTblAvoir();
        foreach ($oPayments as $oPayment)
        {
          if ($oPayment->getIsUsed() == false) {
            $this->getUser()->setFlash('error', 'Impossible de supprimer cet utilisateur a saisi des avoirs non utilisés.');
            $this->redirect('@sf_guard_user');
          } else {
            $oPayment->setIdUser(null)->save();
          }
        }
    }

    if ($oUser->getTblBordereau()->count() > 0) {
        $oBordereaux = $oUser->getTblBordereau();
        foreach ($oBordereaux as $oBordereau)
        {
          if ($oBordereau->getIsPayed() == false) {
            $this->getUser()->setFlash('error', 'Impossible de supprimer cet utilisateur a saisi des bordereaux non payés.');
            $this->redirect('@sf_guard_user');
          } else {
            $oBordereau->setIdUser(null)->save();
          }
        }
    }

    if ($oUser->getTblClub()->count() > 0) {
      $oClubs = $oUser->getTblClub();
      foreach($oClubs as $oClub)
      {
        $oClub->setIdUser(null)->save();
      }
    }

    if ($oUser->getTblLigue()->count() > 0) {
      $oLigues = $oUser->getTblLigue();
      foreach($oLigues as $oLigue)
      {
        $oLigue->setIdUser(null)->save();
      }
    }

    $request->checkCSRFProtection();

    $this->dispatcher->notify(new sfEvent($this, 'admin.delete_object', array('object' => $this->getRoute()->getObject())));

    if ($this->getRoute()->getObject()->delete())
    {
      $this->getUser()->setFlash('notice', 'Utilisateur supprimé.');
    }

    $this->redirect('@sf_guard_user');
  }
}
