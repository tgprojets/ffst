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

      $oAddress = $oProfil->getTblAddress();
      $club = $this->getUser()->getClub();
      if ($club) {
          if ($club != $oLicence->getTblClub()) {
              $jsonresponse['profil']['is_other_club'] = true;
          } else {
              $jsonresponse['profil']['is_other_club'] = false;
          }
      } else {
          $jsonresponse['profil']['is_other_club'] = false;
      }
      $jsonresponse['profil']['email'] = $oProfil->getEmail();
      $jsonresponse['profil']['sexe'] = $oProfil->getSexe();
      $jsonresponse['profil']['last_name'] = $oProfil->getLastName();
      $jsonresponse['profil']['first_name'] = $oProfil->getFirstName();
      $jsonresponse['profil']['image'] = $oProfil->getImage();
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

      if ($oLicence) {
          $jsonresponse['profil']['id_category'] = $oLicence->getTblCategory()->getId();
          $jsonresponse['profil']['id_typelicence'] = $oLicence->getTblTypelicence()->getId();
          $jsonresponse['profil']['international'] = $oLicence->getInternational();
          $jsonresponse['profil']['race_nordique'] = $oLicence->getRaceNordique();
          $jsonresponse['profil']['is_familly'] = $oLicence->getIsFamilly();
          $jsonresponse['profil']['cnil'] = $oLicence->getCnil();
          $jsonresponse['profil']['lastname_doctor'] = $oLicence->getLastnameDoctor();
          $jsonresponse['profil']['firstname_doctor'] = $oLicence->getFirstnameDoctor();
          $jsonresponse['profil']['rpps'] = $oLicence->getRpps();
      }

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

    $club = $this->getUser()->getClub();

    $limit = $request->getParameter('limit');
    if (strlen($keyword) <= 2) {
      return $this->renderText(json_encode(array()));
    }
    if ($club) {
        $oProfils = Doctrine::getTable('tbl_profil')->findByKeywordClub($keyword, $club);
    } else {
        $oProfils = Doctrine::getTable('tbl_profil')->findByKeyword($keyword);
    }
    $sYearLicence = Licence::getDateLicence();
    $list = array();
    foreach($oProfils as $oProfil)
    {
      $licence = Doctrine::getTable('tbl_licence')->findByProfil($sYearLicence, $oProfil->getId());
        if (!$licence) {

          $Birthday  = $oProfil->getBirthday();
          $sBirthday = substr($Birthday, 8, 2).'/'.substr($Birthday, 5, 2).'/'.substr($Birthday, 0, 4);
          $list[$oProfil->getId()] = sprintf('%s %s %s', $oProfil->getLastName(), $oProfil->getFirstName(), $sBirthday);
        }
    }
    return $this->renderText(json_encode($list));
  }

  /**
   * Récupère la liste des villes en ajax
   *
   * @param sfWebRequest $request
   * @return Json return Json array of matching City objects converted to string
   */
  public function executeGetLicenceFamille(sfWebRequest $request)
  {
    $keyword = $request->getParameter('q');

    $club = $this->getUser()->getClub();

    $limit = $request->getParameter('limit');
    if (strlen($keyword) <= 2) {
      return $this->renderText(json_encode(array()));
    }
    if ($club) {
        $oProfils = Doctrine::getTable('tbl_profil')->findByKeywordClub($keyword, $club);
    } else {
        $oProfils = Doctrine::getTable('tbl_profil')->findByKeyword($keyword);
    }
    $list = array();
    $sYearLicence = Licence::getDateLicence();
    foreach($oProfils as $oProfil)
    {
      $licence = Doctrine::getTable('tbl_licence')->findByProfil($sYearLicence, $oProfil->getId());
        if ($licence ) {

          $Birthday  = $oProfil->getBirthday();
          $sBirthday = substr($Birthday, 8, 2).'/'.substr($Birthday, 5, 2).'/'.substr($Birthday, 0, 4);
          $list[$oProfil->getId()] = sprintf('%s %s %s', $oProfil->getLastName(), $oProfil->getFirstName(), $sBirthday);
        }
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

  public function execute404(sfWebRequest $request)
  {

  }

  public function executeExportData(sfWebRequest $request)
  {
    $oExport = new ExportLicence(sfConfig::get('sf_root_dir').'/data/patch/GestionLicences2010-2011.csv');
    $oExport->createLicence();
    $aValues = $oExport->getCodeExist();
    echo '<pre>';
    print_r($aValues);
    echo '</pre>';
    die();
    $this->redirect('@homepage');
  }

  public function executeAideForm(sfWebRequest $request)
  {
    $this->form = new AideForm();
    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter($this->form->getName()),
                            $request->getFiles($this->form->getName()));

      if ($this->form->isValid())
      {
        $aFormulaire = $this->form->getValues();

        $file = $this->form->getValue('aide_pdf');

        $filename = 'aide.pdf';

        $file->save(sfConfig::get('sf_upload_dir').'/'.$filename);

        $this->getUser()->setFlash('notice', 'Fichier ajouté.');

        $this->redirect('main/aideForm');
      }
    }
  }

  public function executeDocForm(sfWebRequest $request)
  {
    $this->form = new DocForm();
    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter($this->form->getName()),
                            $request->getFiles($this->form->getName()));

      if ($this->form->isValid())
      {
        $aFormulaire = $this->form->getValues();

        file_put_contents(sfConfig::get('sf_upload_dir').'/contenu.txt', $aFormulaire['doc']);

        $this->getUser()->setFlash('notice', 'Document modifié.');

        $this->redirect('main/docForm');
      }
    }
  }

  public function executeDoc(sfWebRequest $request)
  {
    $this->sDocument = file_get_contents(sfConfig::get('sf_upload_dir').'/contenu.txt');
  }

  public function executeEndSaison(sfWebRequest $request)
  {

  }

  public function executeSaison(sfWebRequest $request)
  {
    if (Licence::endSaison()) {
      $nYear = date('Y')+1;
      $this->yearLicence = date('Y').'/'.$nYear;
      $oSaison = Doctrine::getTable('tbl_saison')->findOneBy('year_licence', $this->yearLicence);
      // if ($oSaison) {
      //   $this->redirect('main/endSaison');
      // }
      $this->bNewSaison = true;
      $this->form = new tbl_saisonForm();
    } else {
      $oSaison = Doctrine::getTable('tbl_saison')->findOneBy('is_outstanding', true);
      $this->yearLicence = $oSaison->getYearLicence();
      $this->bNewSaison = false;
      $this->form = new tbl_saisonForm($oSaison);
    }
    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter($this->form->getName()),
                                  $request->getFiles($this->form->getName()));
      if ($this->form->isValid())
      {
        $this->form->save();
        $this->getUser()->setFlash('notice', 'Enregistré');
      }
    }
  }

  public function executeCloseSaisonAjax(sfWebRequest $request)
  {
    if ($request->isXmlHttpRequest()) {
      $this->getResponse()->setHttpHeader('Content-Type', 'application/json;');
      $time = microtime(true);
      $oSaison = Doctrine::getTable('tbl_saison')->findOneBy('is_outstanding', true);
      if ($oSaison) {
        $oSaison->setIsOutstanding(false)->save();
        $reponse['error'] = false;
      } else {
        $reponse['error'] = true;
      }
      return $this->renderText(json_encode($reponse));
    }
  }

 /**
  * Executes A propos de action
  *
  * @param sfRequest $request A request object
  */
  public function executeTestSendMail(sfWebRequest $request)
  {
    if ($request->isMethod('post'))
    {
       $this->sendMailClassique($request->getParameter('mailfrom'), $request->getParameter('mailto'), 'Test mail', 'corps');
    }
  }

  public function sendMailClassique($psFrom, $psTo, $psSujet, $psBody) {
      $sDate = strtotime("now");
      $message = Swift_Message::newInstance();
      $message->setFrom($psFrom);
      $message->setTo($psTo);
      $message->setReturnPath(sfConfig::get('app_mail_returnpath'));
      $message->setSubject($psSujet);
      $message->setBody($psBody);
      $message->setDate($sDate);
      $message->setContentType('text/html');
      sfContext::getInstance()->getMailer()->send($message);
  }
}
