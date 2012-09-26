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

  public function executeSearchPageAjax(sfWebRequest $request)
  {
    $this->getResponse()->setHttpHeader('Content-Type', 'application/json;');
    $time = microtime(true);

    if ($this->getUser()->isAuthenticated()) {
      $reponse['visitormsg']['response'] = 1;
    } else {
      if (session_id() != $request->getParameter('symfony')) {
        $reponse['visitormsg']['sessionid'] = session_id();
      }
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
            if (!$this->getUser()->hasCredential('mobile')) {
              $reponse['visitormsg']['response'] = 0;
              $reponse['visitormsg']['formErrors'] = 'Pas autorisé';
              $this->getUser()->signout();
            } else {
              if (session_id() != $request->getParameter('symfony')) {
                $reponse['visitormsg']['sessionid'] = session_id();
              }
            }
        } else {
            $reponse['visitormsg']['response'] = 0;
            //$reponse['visitormsg']['response']['formErrors'] = 'Mot de passe incorrect';
        }
    } else {
        $reponse['visitormsg']['response'] = 0;
        $reponse['visitormsg']['formErrors'] = 'Identifiant incorrect ou mot de passe';
    }

    return $this->renderText(json_encode($reponse));
  }

  public function executeSearchLicenceAjax(sfWebRequest $request)
  {
    $this->getResponse()->setHttpHeader('Content-Type', 'application/json;');
    $time = microtime(true);
    if ($this->getUser()->isAuthenticated()) {
      $keyPress = $request->getParameter('keyPress');

      $aValues = explode(' ', $keyPress);
       // print_r($aValues);
       // die();
      if (count($aValues) == 3) {
        $reponse['licence']['response']['error'] = false;
        $oLicence = Doctrine::getTable('tbl_licence')->findLicence($aValues);
        if ($oLicence) {
          $oProfil = $oLicence->getTblProfil();
          $reponse['licence']['response']['profil']['name'] = $oProfil->getName();
          if ($oLicence->getDateValidation() != null)
          {
            $reponse['licence']['response']['profil']['valide'] = true;
          } else {
            $reponse['licence']['response']['profil']['valide'] = false;
          }
          $reponse['licence']['response']['profil']['num'] = substr($oLicence->getNum(), 0, 7);
          $oGroupeLicence = $oLicence->getTblTypelicence()->getTblGrouplicence();
          if ($oGroupeLicence->getCode() == 'COM') {
            $reponse['licence']['response']['profil']['com'] = true;
          } else {
            $reponse['licence']['response']['profil']['com'] = false;
          }
        } else {
          $reponse['licence']['response']['error'] = true;
        }
      } else {
        $reponse['licence']['response']['error'] = true;
      }
    } else {
      $reponse['licence']['response'] = 0;
    }

    return $this->renderText(json_encode($reponse));
  }

  /*public function executeSearchLicenceAjax(sfWebRequest $request)
  {
    $this->getResponse()->setHttpHeader('Content-Type', 'application/json;');
    $time = microtime(true);
    if ($this->getUser()->isAuthenticated()) {
      $keyPress = $request->getParameter('keyPress');

      //$oLicence = Doctrine::getTable('tbl_licence')->findOneBy('num', $keyPress.'.'.Licence::getDateLicence());
      $oLicences = Doctrine::getTable('tbl_licence')->findByKeypress($keyPress);
      $nCompteur = 0;
      foreach ($oLicences as $oLicence)
      {
        $oProfil = $oLicence->getTblProfil();
        $reponse['licence']['response']['profil'][$nCompteur]['name'] = $oProfil->getName();
        //Récupére la licence de cette année

        if ($oLicence->getDateValidation() != null)
        {
          $reponse['licence']['response']['profil'][$nCompteur]['valide'] = true;
        } else {
          $reponse['licence']['response']['profil'][$nCompteur]['valide'] = false;
        }
        $reponse['licence']['response']['profil'][$nCompteur]['num'] = substr($oLicence->getNum(), 0, 7);
        $oGroupeLicence = $oLicence->getTblTypelicence()->getTblGrouplicence();
        if ($oGroupeLicence->getCode() == 'COM') {
          $reponse['licence']['response']['profil'][$nCompteur]['com'] = true;
        } else {
          $reponse['licence']['response']['profil'][$nCompteur]['com'] = false;
        }
        $nCompteur++;
      }
    } else {
      $reponse['licence']['response'] = 0;
    }

    return $this->renderText(json_encode($reponse));
  }*/
}
