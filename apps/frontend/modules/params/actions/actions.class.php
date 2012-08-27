<?php

/**
 * params actions.
 *
 * @package    sf_sandbox
 * @subpackage params
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class paramsActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeMajorDate(sfWebRequest $request)
  {
    $this->form = new ParamForm();
      if ($request->isMethod('post')) {
          $this->form->bind($request->getParameter('param'));
          if ($this->form->isValid())
          {
            $this->form->save();
            $this->getUser()->setFlash('notice', 'Paramètre enregistré');
          }
    }
  }
}
