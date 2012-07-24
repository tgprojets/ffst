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

  public function executeCheckProfil(sfWebRequest $request)
  {
    if ($request->isXmlHttpRequest()) {
      $nId     = $request->getParameter('nIdProfil');
      $nIdClub = $request->getParameter('nIdClub');
      $oProfil = Doctrine::getTable('tbl_profil')->find($nId);
      $oLicence = $oProfil->getTblLicence()->getLast();
      if ($this->getUser()->isClub() || $this->getUser()->isLigue()) {

        if ($nIdClub != $oLicence->getTblClub()->getId()) {
          $jsonresponse['error'] = 'Vous ne pouvez pas transférer ce profil';
          return $this->renderText(json_encode($jsonresponse));
        }
      }
      $oAddress = $oProfil->getTblAddress();
      $jsonresponse['profil']['email'] = $oProfil->getEmail();
      $jsonresponse['profil']['last_name'] = $oProfil->getLastName();
      $jsonresponse['profil']['first_name'] = $oProfil->getFirstName();
      $Birthday  = $oProfil->getBirthday();
      $jsonresponse['profil']['birthday_day'] = substr($Birthday, 8, 2);
      $jsonresponse['profil']['birthday_month'] = (int) substr($Birthday, 5, 2);
      $jsonresponse['profil']['birthday_year'] = substr($Birthday, 0, 4);
      $jsonresponse['profil']['id_codepostaux'] = $oAddress->getTblCodepostaux()->getId();
      $jsonresponse['profil']['ville'] = $oAddress->getTblCodepostaux()->getVille().'('.$oAddress->getTblCodepostaux()->getCodePostaux().')';
      $jsonresponse['profil']['address1'] = $oAddress->getAddress1();
      $jsonresponse['profil']['address2'] = $oAddress->getAddress1();
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
    $oProfils = Doctrine::getTable('tbl_profil')->findByKeyword($keyword);
    $list = array();
    foreach($oProfils as $oProfil)
    {
      $Birthday  = $oProfil->getBirthday();
      $sBirthday = substr($Birthday, 8, 2).'/'.substr($Birthday, 5, 2).'/'.substr($Birthday, 0, 4);
      $list[$oProfil->getId()] = sprintf('%s %s %s', $oProfil->getLastName(), $oProfil->getFirstName(), $sBirthday);
    }
    return $this->renderText(json_encode($list));
  }
}
