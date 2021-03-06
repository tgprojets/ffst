<?php

require_once dirname(__FILE__).'/../lib/ligueGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/ligueGeneratorHelper.class.php';

/**
 * ligue actions.
 *
 * @package    sf_sandbox
 * @subpackage ligue
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ligueActions extends autoLigueActions
{
  public function executeListEditPassword(sfWebRequest $request)
  {
    $this->oLigue = $this->getRoute()->getObject();
    $this->oUser = $this->oLigue->getSfGuardUser();
    $this->getUser()->setAttribute('back_password', '@tbl_ligue');
    $request->setParameter('id', $this->oUser->getId());
    $this->forward('sfGuardUser', 'editPassword');
  }
}
