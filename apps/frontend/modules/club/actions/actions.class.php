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
  public function executeListRegulation(sfWebRequest $request)
  {
    $oClub = $this->getRoute()->getObject();
    $this->oPaymentLic  = Doctrine::getTable('tbl_payment')->findPaymentLicByClub($oClub->getId());
    $this->oPaymentClub = Doctrine::getTable('tbl_payment')->findPaymentClub($oClub->getId());
    $this->oAvoirLic  = Doctrine::getTable('tbl_avoir')->findAvoirLicByClub($oClub->getId());
    $this->oAvoirClub = Doctrine::getTable('tbl_avoir')->findAvoirClub($oClub->getId());
  }
}
