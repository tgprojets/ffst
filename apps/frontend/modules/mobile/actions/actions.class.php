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

      $oLicence = Doctrine::getTable('tbl_licence')->findOneBy('num', $keyPress.'.'.Licence::getDateLicence());
      if ($oLicence) {
        $oProfil = $oLicence->getTblProfil();
        $reponse['licence']['response']['profil'][0]['name'] = $oProfil->getName();
        if ($oLicence->getDateValidation() != null)
        {
          $reponse['licence']['response']['profil'][0]['valide'] = 'OUI';
        } else {
          $reponse['licence']['response']['profil'][0]['valide'] = 'NON';
        }
        $reponse['licence']['response']['profil'][0]['num'] = substr($oLicence->getNum(), 0, 7);
      } else {
        $oProfils = Doctrine::getTable('tbl_profil')->findByKeyword($keyPress, false);
        $nCompteur = 0;
        foreach ($oProfils as $oProfil)
        {
          $reponse['licence']['response']['profil'][$nCompteur]['name'] = $oProfil->getName();
          //Récupére la licence de cette année

          $oLicence = Doctrine::getTable('tbl_licence')->findByProfil(Licence::getDateLicence(), $oProfil->getId());
          if ($oLicence) {
            if ($oLicence->getDateValidation() != null)
            {
              $reponse['licence']['response']['profil'][$nCompteur]['valide'] = 'OUI';
            } else {
              $reponse['licence']['response']['profil'][$nCompteur]['valide'] = 'NON';
            }
            $reponse['licence']['response']['profil'][$nCompteur]['num'] = substr($oLicence->getNum(), 0, 7);
          } else {
            $reponse['licence']['response']['profil'][$nCompteur]['valide'] = 'NON';
            $reponse['licence']['response']['profil'][$nCompteur]['num'] = '';
          }

          $nCompteur++;
        }
      }
    } else {
      $reponse['licence']['response'] = 0;
    }

    return $this->renderText(json_encode($reponse));
  }
}
