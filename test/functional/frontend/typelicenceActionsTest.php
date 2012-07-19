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
  get('/typelicence/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('federal2')->connexion('federal2', 'federal2');
$browser->
  get('/typelicence/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('teco')->connexion('teco', 'teco');
$browser->
  get('/typelicence/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('stats')->connexion('stats', 'stats');
$browser->
  get('/typelicence/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('visuel')->connexion('visuel', 'visuel');
$browser->
  get('/typelicence/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('dcheoux')->connexion('dcheoux', 'dcheoux');
$browser->
  get('/typelicence/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();
$browser->info('admin')->connexion('admin', 'admin');
$browser->
  get('/typelicence/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();
$browser->info('admin N1')->connexion('adminN1', 'adminN1');
$browser->
  get('/typelicence/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();

/**
* Ajouter une typelicence
* Formulaire 2 erreurs requis
* Formulaire valide
*
*/
$browser->info('dcheoux')->connexion('dcheoux', 'dcheoux');
$browser->get('/typelicence/new')->
  info('Nouveau type de licence')->
  info('3 erreurs code & Lib & prix requis')->
  click('Mettre à jour', array('tbl_typelicence' => array(
  )))->
  with('form')->begin()->
    hasErrors(3)->
  end()->
  info('Formulaire correcte')->
  click('Mettre à jour', array('tbl_typelicence' => array(
    'lib'     => 'test',
    'code'    => 'TEST',
    'prix'    => '100',
  )))->
  with('form')->begin()->
    hasErrors(false)->
  end()->
  with('response')->isRedirected()->
  followRedirect()->
  info('Redirection vers licence')->
    with('request')->begin()->
      isParameter('module', 'typelicence')->
      isParameter('action', 'edit')->
    end()->
    click('Mettre à jour', array('tbl_typelicence' => array(
      'lib'   => 'test 1',
      'code'  => 'TEST',
      'prix'  => '120'
    )))->
    with('response')->isRedirected()->
    followRedirect();
$browser->deconnexion();