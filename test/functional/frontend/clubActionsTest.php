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
  get('/club/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();
$browser->info('federal2')->connexion('federal2', 'federal2');
$browser->
  get('/club/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('teco')->connexion('teco', 'teco');
$browser->
  get('/club/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('stats')->connexion('stats', 'stats');
$browser->
  get('/club/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('visuel')->connexion('visuel', 'visuel');
$browser->
  get('/club/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('dcheoux')->connexion('dcheoux', 'dcheoux');
$browser->
  get('/club/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();
$browser->info('admin')->connexion('admin', 'admin');
$browser->
  get('/club/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();
$browser->info('admin N1')->connexion('adminN1', 'adminN1');
$browser->
  get('/club/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();

/**
* Ajouter une club
* Formulaire 2 erreurs requis
* Formulaire valide
*
*/
$browser->info('federal1')->connexion('federal1', 'federal1');
$browser->get('/club/new')->
  info('Nouvelle club')->
  info('9 erreurs requis')->
  click('Mettre à jour', array('tbl_club' => array(
  )))->
  with('form')->begin()->
    hasErrors(9)->
  end()->
  info('Formulaire correcte')->
  click('Mettre à jour', array('tbl_club' => array(
    'name'             => 'TEST',
    'num'              => '100',
    'affiliation'      => 'TTT',
    'email'            => 'test@test.fr',
    'password'         => 'test33',
    'username'         => 'test',
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
      isParameter('module', 'club')->
      isParameter('action', 'edit')->
    end()->
    info('Email existant')->
    click('Mettre à jour', array('tbl_club' => array(
    'name'             => 'TEST',
    'num'              => '100',
    'affiliation'      => 'TTT',
    'email'            => 'tgilbert@tgprojets.fr',
    'username'         => 'test',
    'nom'              => 'test',
    'prenom'           => 'test',
    'address1'         => 'test',
    )))->
    with('form')->begin()->
        hasErrors(1)->
    end()->
    info('Identifiant existant')->
    click('Mettre à jour', array('tbl_club' => array(
    'name'             => 'TEST',
    'num'              => '100',
    'affiliation'      => 'TTT',
    'email'            => 'test@test.fr',
    'username'         => 'admin',
    'nom'              => 'test',
    'prenom'           => 'test',
    'address1'         => 'test',
    )))->
    with('form')->begin()->
        hasErrors(1)->
    end()->
    info('Identifiant club existant')->
    click('Mettre à jour', array('tbl_club' => array(
    'name'             => 'DEC',
    'num'              => '100',
    'affiliation'      => 'TTT',
    'email'            => 'test@test.fr',
    'username'         => 'test',
    'nom'              => 'test',
    'prenom'           => 'test',
    'address1'         => 'test',
    )))->
    with('form')->begin()->
        hasErrors(1)->
    end()->
    info('num club existant')->
    click('Mettre à jour', array('tbl_club' => array(
    'name'             => 'TEST',
    'num'              => '300',
    'affiliation'      => 'TTT',
    'email'            => 'test@test.fr',
    'username'         => 'test',
    'nom'              => 'test',
    'prenom'           => 'test',
    'address1'         => 'test',
    )))->
    with('form')->begin()->
        hasErrors(1)->
    end()->
    info('Edition OK')->
    click('Mettre à jour', array('tbl_club' => array(
    'name'             => 'TEST',
    'num'              => '301',
    'affiliation'      => 'TTT',
    'email'            => 'test@test.fr',
    'username'         => 'test',
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