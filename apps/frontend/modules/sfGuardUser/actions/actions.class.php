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
    if ($request->hasParameter('id'))
    {
      $this->oUser = Doctrine::getTable('sfGuardUser')->find($request->getParameter('id'));
      $this->form = new ModifpasswordForm(array(), array('id' => $this->oUser->getId()));
      if ($request->isMethod('post')) {
          $this->form->bind($request->getParameter('modifpassword'));
          if ($this->form->isValid())
          {
             $aValues = $this->form->getValues();
             $this->oUser->setPassword($aValues['password_forgot']);
             $this->oUser->save();
             $this->redirect('@sf_guard_user');
          }
      }
      $this->setTemplate('editpassword');
    } else {
      $this->redirect('@sf_guard_user');
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
    $request->setParameter('id', $this->oUser->getId());
    $this->forward('sfGuardUser', 'editPassword');
  }

}
