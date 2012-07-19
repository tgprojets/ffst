<?php

require_once dirname(__FILE__).'/../lib/clubGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/clubGeneratorHelper.class.php';

/**
 * club actions.
 *
 * @package    sf_sandbox
 * @subpackage club
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class clubActions extends autoClubActions
{
  public function executeListEditPassword(sfWebRequest $request)
  {
    $this->oClub = $this->getRoute()->getObject();
    $this->oUser = $this->oClub->getSfGuardUser();
    $this->getUser()->setAttribute('back_password', '@tbl_club');
    $request->setParameter('id', $this->oUser->getId());
    $this->forward('sfGuardUser', 'editPassword');
  }
}
