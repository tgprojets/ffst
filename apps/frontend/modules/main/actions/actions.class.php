<?php

/**
 * main actions.
 *
 * @package    sf_sandbox
 * @subpackage main
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class mainActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {

  }
  public function executeGeneratePassword(sfWebRequest $request)
  {
    if ($request->isXmlHttpRequest()) {
       $jsonresponse['password'] = myGenerique::generatePassword();
       return $this->renderText(json_encode($jsonresponse));
    }
    $this->redirect404();
  }
}
