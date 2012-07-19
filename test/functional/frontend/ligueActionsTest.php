<?php
/**
* Scénario
*
* Authorization
*
* 1. federal 1 Not
* 2. federal 2 Not
* 3. teco Not
* 4. stats Not
* 5. visuel Not
* 6. dcheoux Yes
* 7. admin yes
*
**/
include(dirname(__FILE__).'/../../bootstrap/functional.php');

ob_start();
$browser = new browserTestFunctional(new sfBrowser());
$browser->loadData('sfGuard.yml');
$browser->info('federal1')->connexion('federal1', 'federal1');
$browser->
  get('/ligue/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();
$browser->info('federal2')->connexion('federal2', 'federal2');
$browser->
  get('/ligue/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('teco')->connexion('teco', 'teco');
$browser->
  get('/ligue/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('stats')->connexion('stats', 'stats');
$browser->
  get('/ligue/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('visuel')->connexion('visuel', 'visuel');
$browser->
  get('/ligue/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('dcheoux')->connexion('dcheoux', 'dcheoux');
$browser->
  get('/ligue/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();
$browser->info('admin')->connexion('admin', 'admin');
$browser->
  get('/ligue/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();
$browser->info('admin N1')->connexion('adminN1', 'adminN1');
$browser->
  get('/ligue/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();

/**
* Ajouter une ligue
* Formulaire 2 erreurs requis
* Formulaire valide
*
*/
$browser->info('federal1')->connexion('federal1', 'federal1');
$browser->get('/ligue/new')->
  info('Nouvelle ligue')->
  info('9 erreurs requis')->
  click('Mettre à jour', array('tbl_ligue' => array(
  )))->
  with('form')->begin()->
    hasErrors(9)->
  end()->
  info('Formulaire correcte')->
  click('Mettre à jour', array('tbl_ligue' => array(
    'name'             => 'TEST',
    'num'              => '100',
    'affiliation'      => 'TTT',
    'email'            => 'testLigue@test.fr',
    'password'         => 'test33',
    'username'         => 'testLigue',
    'nom'              => 'test',
    'prenom'           => 'test',
    'address1'         => 'test',
  )))->
  with('form')->begin()->
    hasErrors(false)->
  end()->
  with('response')->isRedirected()->
  followRedirect()->
  info('Redirection vers la catégorie')->
    with('request')->begin()->
      isParameter('module', 'ligue')->
      isParameter('action', 'edit')->
    end()->
    info('Email existant')->
    click('Mettre à jour', array('tbl_ligue' => array(
    'name'             => 'TEST',
    'num'              => '100',
    'affiliation'      => 'TTT',
    'email'            => 'tgilbert@tgprojets.fr',
    'username'         => 'testLigue',
    'nom'              => 'test',
    'prenom'           => 'test',
    'address1'         => 'test',
    )))->
    with('form')->begin()->
        hasErrors(1)->
    end()->
    info('Identifiant existant')->
    click('Mettre à jour', array('tbl_ligue' => array(
    'name'             => 'TEST',
    'num'              => '100',
    'affiliation'      => 'TTT',
    'email'            => 'testLigue@test.fr',
    'username'         => 'admin',
    'nom'              => 'test',
    'prenom'           => 'test',
    'address1'         => 'test',
    )))->
    with('form')->begin()->
        hasErrors(1)->
    end()->
    info('Identifiant ligue existant')->
    click('Mettre à jour', array('tbl_ligue' => array(
    'name'             => 'PEC',
    'num'              => '100',
    'affiliation'      => 'TTT',
    'email'            => 'testLigue@test.fr',
    'username'         => 'testLigue',
    'nom'              => 'test',
    'prenom'           => 'test',
    'address1'         => 'test',
    )))->
    with('form')->begin()->
        hasErrors(1)->
    end()->
    info('num ligue existant')->
    click('Mettre à jour', array('tbl_ligue' => array(
    'name'             => 'TEST',
    'num'              => '400',
    'affiliation'      => 'TTT',
    'email'            => 'testLigue@test.fr',
    'username'         => 'testLigue',
    'nom'              => 'test',
    'prenom'           => 'test',
    'address1'         => 'test',
    )))->
    with('form')->begin()->
        hasErrors(1)->
    end()->
    info('Edition OK')->
    click('Mettre à jour', array('tbl_ligue' => array(
    'name'             => 'TEST',
    'num'              => '301',
    'affiliation'      => 'TTT',
    'email'            => 'testLigue@test.fr',
    'username'         => 'testLigue',
    'nom'              => 'test',
    'prenom'           => 'test',
    'address1'         => 'test',
    )))->
    with('form')->begin()->
        hasErrors(false)->
    end()->
    with('response')->isRedirected()->
    followRedirect();
$browser->deconnexion();