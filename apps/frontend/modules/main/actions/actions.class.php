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

  public function executeCompatibility(sfWebRequest $request)
  {
    $this->setLayout('layout_compatibility');
  }

  public function executeCheckProfil(sfWebRequest $request)
  {
    if ($request->isXmlHttpRequest()) {
      $nId     = $request->getParameter('nIdProfil');
      $nIdClub = $request->getParameter('nIdClub');
      $oProfil = Doctrine::getTable('tbl_profil')->find($nId);
      $oLicence = $oProfil->getTblLicence()->getLast();
      if ($this->getUser()->isClub()) {

        if ($nIdClub != $oLicence->getTblClub()->getId()) {
          $jsonresponse['error'] = 'Vous ne pouvez pas transférer ce profil';
          return $this->renderText(json_encode($jsonresponse));
        }
      }
      if ($this->getUser()->isLigue())
      {
        $oLigue = $this->getUser()->getLigue();
        if (Doctrine::getTable('tbl_profil')->checkTransfert($nId, $oLigue->getId()) == false)
        {
          $jsonresponse['error'] = 'Vous ne pouvez pas transférer ce profil';
          return $this->renderText(json_encode($jsonresponse));
        }
      }
      $oAddress = $oProfil->getTblAddress();
      $jsonresponse['profil']['email'] = $oProfil->getEmail();
      $jsonresponse['profil']['sexe'] = $oProfil->getSexe();
      $jsonresponse['profil']['last_name'] = $oProfil->getLastName();
      $jsonresponse['profil']['first_name'] = $oProfil->getFirstName();
      $Birthday  = $oProfil->getBirthday();
      $jsonresponse['profil']['birthday_day'] = (int) substr($Birthday, 8, 2);
      $jsonresponse['profil']['birthday_month'] = (int) substr($Birthday, 5, 2);
      $jsonresponse['profil']['birthday_year'] = substr($Birthday, 0, 4);
      $jsonresponse['profil']['id_codepostaux'] = $oAddress->getTblCodepostaux()->getId();
      $jsonresponse['profil']['ville'] = $oAddress->getTblCodepostaux()->getVille().'('.$oAddress->getTblCodepostaux()->getCodePostaux().')';
      $jsonresponse['profil']['address1'] = $oAddress->getAddress1();
      $jsonresponse['profil']['address2'] = $oAddress->getAddress2();
      $jsonresponse['profil']['tel'] = $oAddress->getTel();
      $jsonresponse['profil']['gsm'] = $oAddress->getGsm();
      $jsonresponse['profil']['fax'] = $oAddress->getFax();

      $jsonresponse['profil']['id_category'] = $oLicence->getTblCategory()->getId();
      $jsonresponse['profil']['id_typelicence'] = $oLicence->getTblTypelicence()->getId();
      $jsonresponse['profil']['international'] = $oLicence->getInternational();
      $jsonresponse['profil']['race_nordique'] = $oLicence->getRaceNordique();
      $jsonresponse['profil']['is_familly'] = $oLicence->getIsFamilly();
      $jsonresponse['profil']['cnil'] = $oLicence->getCnil();

      return $this->renderText(json_encode($jsonresponse));
    }
    $this->redirect404();
  }

  public function executeGeneratePassword(sfWebRequest $request)
  {
    if ($request->isXmlHttpRequest()) {
       $jsonresponse['password'] = myGenerique::generatePassword();
       return $this->renderText(json_encode($jsonresponse));
    }
    $this->redirect404();
  }
  /**
   * Récupère la liste des villes en ajax
   *
   * @param sfWebRequest $request
   * @return Json return Json array of matching City objects converted to string
   */
  public function executeGetLicence(sfWebRequest $request)
  {
    $keyword = $request->getParameter('q');

    $limit = $request->getParameter('limit');
    if (strlen($keyword) <= 2) {
      return $this->renderText(json_encode(array()));
    }
    $oClub = $this->getUser()->getClub();
    if ($oClub)
    {
      $nClub = $oClub->getId();
    } else {
      $nClub = null;
    }
    $oProfils = Doctrine::getTable('tbl_profil')->findByKeyword($keyword, $nClub);
    $list = array();
    foreach($oProfils as $oProfil)
    {
      $Birthday  = $oProfil->getBirthday();
      $sBirthday = substr($Birthday, 8, 2).'/'.substr($Birthday, 5, 2).'/'.substr($Birthday, 0, 4);
      $list[$oProfil->getId()] = sprintf('%s %s %s', $oProfil->getLastName(), $oProfil->getFirstName(), $sBirthday);
    }
    return $this->renderText(json_encode($list));
  }

  /**
   * Récupère la liste des villes en ajax
   *
   * @param sfWebRequest $request
   * @return Json return Json array of matching City objects converted to string
   */
  public function executeGetProfil(sfWebRequest $request)
  {
    $keyword = $request->getParameter('q');

    $limit = $request->getParameter('limit');
    if (strlen($keyword) <= 2) {
      return $this->renderText(json_encode(array()));
    }
    $oProfils = Doctrine::getTable('sfGuardUser')->findByKeyword($keyword);
    $list = array();
    foreach($oProfils as $oProfil)
    {
      $list[$oProfil->getId()] = sprintf('%s %s (%s)', $oProfil->getLastName(), $oProfil->getFirstName(), $oProfil->getUsername());
    }
    return $this->renderText(json_encode($list));
  }

  public function executeCheckUser(sfWebRequest $request)
  {
    if ($request->isXmlHttpRequest()) {
      $nId     = $request->getParameter('nIdProfil');
      $oUser = Doctrine::getTable('sfGuardUser')->find($nId);
      $jsonresponse['profil']['email'] = $oUser->getEmailAddress();
      $jsonresponse['profil']['last_name'] = $oUser->getLastName();
      $jsonresponse['profil']['first_name'] = $oUser->getFirstName();
      $jsonresponse['profil']['username'] = $oUser->getUsername();
      return $this->renderText(json_encode($jsonresponse));
    }
  }

  public function executeCheckUserCancelClub(sfWebRequest $request)
  {
    if ($request->isXmlHttpRequest()) {
      $nId     = $request->getParameter('nIdClub');
      $bExist = false;
      if ($nId != "") {
        $oClub = Doctrine::getTable('tbl_club')->find($nId);
        $oUser = $oClub->getSfGuardUser();
        if ($oUser) {
          $bExist = true;
          $jsonresponse['profil']['email'] = $oUser->getEmailAddress();
          $jsonresponse['profil']['last_name'] = $oUser->getLastName();
          $jsonresponse['profil']['first_name'] = $oUser->getFirstName();
          $jsonresponse['profil']['username'] = $oUser->getUsername();
          $jsonresponse['profil']['id'] = $oUser->getId();
        }
      }
      if ($bExist == false) {
          $jsonresponse['profil']['email'] = '';
          $jsonresponse['profil']['last_name'] = '';
          $jsonresponse['profil']['first_name'] = '';
          $jsonresponse['profil']['username'] = '';
          $jsonresponse['profil']['id'] = '';
      }
      return $this->renderText(json_encode($jsonresponse));
    }
  }

  public function executeCheckUserCancelLigue(sfWebRequest $request)
  {
    if ($request->isXmlHttpRequest()) {
      $nId     = $request->getParameter('nIdLigue');
      $bExist = false;
      if ($nId != "") {
        $oLigue = Doctrine::getTable('tbl_ligue')->find($nId);
        $oUser = $oLigue->getSfGuardUser();
        if ($oUser) {
          $bExist = true;
          $jsonresponse['profil']['email'] = $oUser->getEmailAddress();
          $jsonresponse['profil']['last_name'] = $oUser->getLastName();
          $jsonresponse['profil']['first_name'] = $oUser->getFirstName();
          $jsonresponse['profil']['username'] = $oUser->getUsername();
          $jsonresponse['profil']['id'] = $oUser->getId();
        }
      }
      if ($bExist == false) {
          $jsonresponse['profil']['email'] = '';
          $jsonresponse['profil']['last_name'] = '';
          $jsonresponse['profil']['first_name'] = '';
          $jsonresponse['profil']['username'] = '';
          $jsonresponse['profil']['id'] = '';
      }
      return $this->renderText(json_encode($jsonresponse));
    }
  }
}
