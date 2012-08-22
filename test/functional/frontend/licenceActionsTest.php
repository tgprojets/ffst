<?php
/**
* Scénario
*
* Authorization
*
* 1. federal 1 yes
* 2. federal 2 yes
* 3. teco Not
* 4. stats Not
* 5. visuel Not
* 6. dcheoux Yes
* 7. admin yes
* 8. Club yes
* 9. Ligue yes
*
**/
include(dirname(__FILE__).'/../../bootstrap/functional.php');

ob_start();
$browser = new browserTestFunctional(new sfBrowser());
$browser->loadData('sfGuard.yml');
$browser->info('federal1')->connexion('federal1', 'federal1');
$browser->
  get('/licence/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();
$browser->info('federal2')->connexion('federal2', 'federal2');
$browser->
  get('/licence/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();
$browser->info('teco')->connexion('teco', 'teco');
$browser->
  get('/licence/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('stats')->connexion('stats', 'stats');
$browser->
  get('/licence/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('visuel')->connexion('visuel', 'visuel');
$browser->
  get('/licence/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('dcheoux')->connexion('dcheoux', 'dcheoux');
$browser->
  get('/licence/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();
$browser->info('admin')->connexion('admin', 'admin');
$browser->
  get('/licence/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();
$browser->info('admin N1')->connexion('adminN1', 'adminN1');
$browser->
  get('/licence/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();
$browser->info('club 1')->connexion('club1', 'club1');
$browser->
  get('/licence/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();
$browser->info('ligue 1')->connexion('ligue1', 'ligue1');
$browser->
  get('/licence/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();

/**
* Ajouter une licence depuis Admin
*
*
*
**/
//Club
$oClub = Doctrine::getTable('tbl_club')->findOneBy('name', 'DE1');
//Categorie
$oCategory = Doctrine::getTable('tbl_category')->findOneBy('code', 'CC');
//Type de licence
$oTypeLicence = Doctrine::getTable('tbl_typelicence')->findOneBy('code', 'ATJ');
//$birthday = date("Y-m-d", strtotime("-1 day"));

$browser->info('federal1')->connexion('federal1', 'federal1');
$browser->get('/licence/new')->
  info('Nouvelle licence')->
  info('5 erreurs requis')->
  click('Mettre à jour et ajouter', array('tbl_licence' => array(
  )))->
  with('form')->begin()->
    hasErrors(5)->
  end()->
  info('Formulaire incorrect email incorrect')->
  click('Mettre à jour et ajouter', array('tbl_licence' => array(
    'id_club'            => $oClub->getId(),
    'id_category'        => $oCategory->getId(),
    'id_typelicence'     => $oTypeLicence->getId(),
    'international'      => false,
    'race_nordique'      => false,
    'id_familly'         => null,
    'cnil'               => false,
    'date_medical'       => null,
    'id_codepostaux'     => null,
    'address1'           => 'Rue de la genève',
    'address2'           => '',
    'tel'                => '0140404040',
    'gsm'                => '0140404041',
    'fax'                => '0140404042',
    'id_address'         => null,
    'sexe'               => 'M',
    'email'              => 'testLigue',
    'last_name'          => 'test33',
    'first_name'         => 'testLigue',
    'birthday'           => '1995-04-20',
  )))->
  with('form')->begin()->
    hasErrors(1)->
  end()->
  info('Formulaire incorrect email exite')->
  click('Mettre à jour et ajouter', array('tbl_licence' => array(
    'id_club'            => $oClub->getId(),
    'id_category'        => $oCategory->getId(),
    'id_typelicence'     => $oTypeLicence->getId(),
    'international'      => false,
    'race_nordique'      => false,
    'id_familly'         => null,
    'cnil'               => false,
    'date_medical'       => null,
    'id_codepostaux'     => null,
    'address1'           => 'Rue de la genève',
    'address2'           => '',
    'tel'                => '0140404040',
    'gsm'                => '0140404041',
    'fax'                => '0140404042',
    'id_address'         => null,
    'sexe'               => 'M',
    'email'              => 'fabien.melun@free.fr',
    'last_name'          => 'test33',
    'first_name'         => 'testLigue',
    'birthday'           => '1995-04-20',
  )))->
  with('form')->begin()->
    hasErrors(1)->
  end()->
  info('Formulaire incorrect email exite')->
  click('Mettre à jour et ajouter', array('tbl_licence' => array(
    'id_club'            => $oClub->getId(),
    'id_category'        => $oCategory->getId(),
    'id_typelicence'     => $oTypeLicence->getId(),
    'international'      => false,
    'race_nordique'      => false,
    'id_familly'         => null,
    'cnil'               => false,
    'date_medical'       => null,
    'id_codepostaux'    => null,
    'address1'           => 'Rue de la genève',
    'address2'           => '',
    'tel'                => '0140404040',
    'gsm'                => '0140404041',
    'fax'                => '0140404042',
    'id_address'         => null,
    'sexe'               => 'M',
    'email'              => 'fabienne.melun@free.fr',
    'last_name'          => 'Melun',
    'first_name'         => 'Fabien',
    'birthday'           => '1995-04-20',
  )))->
  with('form')->begin()->
    hasErrors(1)->
  end()->
  info('Formulaire incorrect email exite')->
  click('Mettre à jour et ajouter', array('tbl_licence' => array(
    'id_club'            => $oClub->getId(),
    'id_category'        => $oCategory->getId(),
    'id_typelicence'     => $oTypeLicence->getId(),
    'international'      => false,
    'race_nordique'      => false,
    'id_familly'         => null,
    'cnil'               => false,
    'date_medical'       => null,
    'id_codepostaux'     => null,
    'address1'           => 'Rue de la genève',
    'address2'           => '',
    'tel'                => '0140404040',
    'gsm'                => '0140404041',
    'fax'                => '0140404042',
    'id_address'         => null,
    'sexe'               => 'M',
    'email'              => 'fabienne.melun@free.fr',
    'last_name'          => 'Melun',
    'first_name'         => 'Fabienne',
    'birthday'           => '1975-04-20',
  )))->
    with('form')->begin()->
        hasErrors(false)->
    end()->
    with('response')->isRedirected()->
    followRedirect();
$browser->deconnexion();

/**
* Club 1
* Impossible de saisir une licence
* Admin
* valide la saisie
* Club 1
* Ajoute licence (true)
* Ajoute même licence (false)
* Cancel saisie
* Ajoute même licence (true)
* Ajoute licence familly (false)
* Ajoute licence familly (true)
* Ajoute licence existe
*
**/
$oFamillyFalse = Doctrine::getTable('tbl_profil')->findOneBy('email','jean.lafleur@free.fr');
$oFamillyTrue = Doctrine::getTable('tbl_profil')->findOneBy('email','damien.lasperche@free.fr');
$browser->info('club 1')->connexion('club1', 'club1');
$browser->addLicence($oClub->getId(), $oCategory->getId(), $oTypeLicence->getId(), false, false,
                  null, false, null, null, 'POI', null, 'M', 'facile@free.fr', 'Pierre', 'Blank', '1975-04-20', true);
$browser->deconnexion();
$browser->info('federal1')->connexion('federal1', 'federal1');
$browser->get('/licence/ListValidSaisie');
$browser->deconnexion();
$browser->info('club 1')->connexion('club1', 'club1');
$browser->info('Ajoute licence');
$browser->addLicence($oClub->getId(), $oCategory->getId(), $oTypeLicence->getId(), false, false,
                  null, false, null, null, 'POI', null, 'M', 'facile@free.fr', 'Pierre', 'Blank', '1975-04-20', false);
$browser->info('Ajoute même licence');
$browser->addLicence($oClub->getId(), $oCategory->getId(), $oTypeLicence->getId(), false, false,
                  null, false, null, null, 'POI', null, 'M', 'facile@free.fr', 'Pierre', 'Blank', '1975-04-20', true);
$browser->info('Annule la saisie');
$browser->get('/licence/ListCancelSaisie');
$browser->info('Ajoute même licence');
$browser->addLicence($oClub->getId(), $oCategory->getId(), $oTypeLicence->getId(), false, false,
                  null, false, null, null, 'POI', null, 'M', 'facile@free.fr', 'Pierre', 'Blank', '1975-04-20', false);
$browser->info('Ajoute licence famille false');
$browser->addLicence($oClub->getId(), $oCategory->getId(), $oTypeLicence->getId(), true, true,
                  $oFamillyFalse->getId(), false, null, null, 'POI', null, 'M', 'facile1@free.fr', 'Pierre', 'Blank', '1975-04-20', true);
$browser->info('Ajoute licence famille true');
$browser->addLicence($oClub->getId(), $oCategory->getId(), $oTypeLicence->getId(), true, true,
                  $oFamillyTrue->getId(), false, null, null, 'POI', null, 'M', 'facile1@free.fr', 'Pierre', 'Blank', '1975-04-20', false);
$browser->addLicenceExiste($oFamillyTrue->getId(), $oClub->getId(), $oCategory->getId(), $oTypeLicence->getId(), true, true,
                  null, false, null, null, $oFamillyTrue->getTblAddress()->getAddress1(), $oFamillyTrue->getTblAddress()->getId(), 'M', $oFamillyTrue->getEmail(), $oFamillyTrue->getFirstName(),
                  $oFamillyTrue->getLastName(), $oFamillyTrue->getBirthday(), false);
$oLicences = Doctrine::getTable('tbl_licence')->findAll();
$oLicence = $oLicences->getLast();
$browser->get('/licence/'.$oLicence->getId().'/edit')->
    with('response')->isRedirected()->
    followRedirect()
    ->with('request')->begin()
      ->isParameter('module', 'licence')
      ->isParameter('action', 'index')
    ->end();
$browser->get('/licence/ListValidSaisie');
$browser->get('/licence/'.$oLicence->getId().'/edit')->
    with('request')->begin()
      ->isParameter('module', 'licence')
      ->isParameter('action', 'edit')
    ->end();
$browser->deconnexion();