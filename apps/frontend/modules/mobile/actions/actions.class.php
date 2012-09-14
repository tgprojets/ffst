<?php

/**
 * mobile actions.
 *
 * @package    sf_sandbox
 * @subpackage mobile
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class mobileActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {

  }

  public function executeSearchPageAjax(sfWebRequest $request)
  {
    $this->getResponse()->setHttpHeader('Content-Type', 'application/json;');
    $time = microtime(true);

      $reponse['visitormsg']['sessionid'] = session_id();
      if ($this->getUser()->isAuthenticated()) {
        $reponse['visitormsg']['response'] = 1;
        //$reponse['visitormsg']['response']['sessionid'] = session_id();
      } else {
        $reponse['visitormsg']['response'] = 0;
      }
      return $this->renderText(json_encode($reponse));
  }

  public function executeConnectAjax(sfWebRequest $request)
  {
    $this->getResponse()->setHttpHeader('Content-Type', 'application/json;');
    $time = microtime(true);

    $sLogin    = $request->getParameter('login');
    $sPassword = $request->getParameter('password');

    $oUser = Doctrine::getTable('sfGuardUser')->findOneBy('username', $sLogin);

    if ($oUser) {
        $bValidPassword = $oUser->checkPassword($sPassword);
        if ($bValidPassword) {
            $reponse['visitormsg']['response'] = 1;
            $this->getUser()->signin($oUser, false);
        } else {
            $reponse['visitormsg']['response'] = 0;
            //$reponse['visitormsg']['response']['formErrors'] = 'Mot de passe incorrect';
        }
    } else {
        $reponse['visitormsg']['response']['formErrors'] = 'Identifiant incorrect ou mot de passe';
    }

    return $this->renderText(json_encode($reponse));
  }
}
