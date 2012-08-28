<?php
class browserTestFunctional extends sfTestFunctional
{
  public function loadData($psFile)
  {
    Doctrine_Core::loadData(sfConfig::get('sf_test_dir').'/fixtures/'.$psFile);
    return $this;
  }
  public function deconnexion() {
    $this->get('/sfGuardAuth/signout');
  }
  public function connexion($psUser, $psPassword, $bValide = false) {
      $this->
      get('/sfGuardAuth/signin')->
      info('Module connexion')->
      with('request')->begin()->
        isParameter('module', 'sfGuardAuth')->
        isParameter('action', 'signin')->
      end()->
      click("Se connecter", array('signin' => array(
        'username'      => $psUser,
        'password'      => $psPassword,
      )))->
      with('form')->begin()->
        hasErrors($bValide)->
      end();
  }
  public function testPage($psModule, $psAction, $psModuleRedirect="moncompte", $psActionRedirect="index") {
    $this->
      get('/'.$psModule.'/'.$psAction)->
      with('request')->begin()->
        isParameter('module', $psModule)->
        isParameter('action', $psAction)->
      end()->

      with('response')->isRedirected()->
        followRedirect()->
        with('request')->begin()->
          isParameter('module', $psModuleRedirect)->
          isParameter('action', $psActionRedirect)->
      end()
    ;
  }
  public function testPageFront($psModule, $psAction, $psModuleRedirect="main", $psActionRedirect="disable") {
    $this->
      get('/'.$psModule.'/'.$psAction)->
      with('request')->begin()->
        isParameter('module', $psModule)->
        isParameter('action', $psAction)->
      end()->

      with('response')->isRedirected()->
        followRedirect()->
        with('request')->begin()->
          isParameter('module', $psModuleRedirect)->
          isParameter('action', $psActionRedirect)->
      end()
    ;
  }

  public function addLicence($nIdClub, $nIdCategory, $nIdTypeLicence, $int, $race, $idFamilly, $cnil, $dateCertif,
                             $idCodepostaux, $address1, $idAddress, $sexe, $email, $LastName, $FirstName, $dateBirthday, $bError, $bDebug=false)
  {
    $this->get('/licence/new')->
      info('Nouvelle licence')->
      click('Mettre à jour', array('tbl_licence' => array(
        'id_club'            => $nIdClub,
        'id_category'        => $nIdCategory,
        'id_typelicence'     => $nIdTypeLicence,
        'international'      => $int,
        'race_nordique'      => $race,
        'is_familly'         => $idFamilly!=null?true:false,
        'id_familly'         => $idFamilly,
        'cnil'               => $cnil,
        'date_medical'       => $dateCertif,
        'id_codepostaux'     => $idCodepostaux,
        'address1'           => $address1,
        'address2'           => '',
        'tel'                => '0140404040',
        'gsm'                => '0140404041',
        'fax'                => '0140404042',
        'id_address'         => $idAddress,
        'sexe'               => $sexe,
        'email'              => $email,
        'last_name'          => $LastName,
        'first_name'         => $FirstName,
        'birthday'           => $dateBirthday,
      )));
    if ($bDebug)
    {
      $this->with('form')->begin()->
        hasErrors(1)->debug()->
      end();
    }
    if ($bError) {
      $this->with('form')->begin()->
        hasErrors(1)->
      end();
    } else {
      $this->with('form')->begin()->
        hasErrors(false)->
      end();
    }
  }

  public function addLicenceExiste($nIdProfil, $nIdClub, $nIdCategory, $nIdTypeLicence, $int, $race, $idFamilly, $cnil, $dateCertif,
                             $idCodepostaux, $address1, $idAddress, $sexe, $email, $LastName, $FirstName, $dateBirthday, $bError)
  {
    $this->get('/licence/new')->
      info('Nouvelle licence existe')->
      click('Mettre à jour', array('tbl_licence' => array(
        'id_profil'          => $nIdProfil,
        'is_checked'         => true,
        'id_club'            => $nIdClub,
        'id_category'        => $nIdCategory,
        'id_typelicence'     => $nIdTypeLicence,
        'international'      => $int,
        'race_nordique'      => $race,
        'id_familly'         => $idFamilly,
        'cnil'               => $cnil,
        'date_medical'       => $dateCertif,
        'id_codepostaux'     => $idCodepostaux,
        'address1'           => $address1,
        'address2'           => '',
        'tel'                => '0140404040',
        'gsm'                => '0140404041',
        'fax'                => '0140404042',
        'id_address'         => $idAddress,
        'sexe'               => $sexe,
        'email'              => $email,
        'last_name'          => $LastName,
        'first_name'         => $FirstName,
        'birthday'           => $dateBirthday,
      )));
    if ($bError) {
      $this->with('form')->begin()->
        hasErrors(1)->
      end();
    } else {
      $this->with('form')->begin()->
        hasErrors(false)->
      end();
    }
  }
}
