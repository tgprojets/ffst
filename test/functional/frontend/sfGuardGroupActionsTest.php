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
  get('/sfGuardGroup/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('federal2')->connexion('federal2', 'federal2');
$browser->
  get('/sfGuardGroup/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('teco')->connexion('teco', 'teco');
$browser->
  get('/sfGuardGroup/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('stats')->connexion('stats', 'stats');
$browser->
  get('/sfGuardGroup/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('visuel')->connexion('visuel', 'visuel');
$browser->
  get('/sfGuardGroup/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('dcheoux')->connexion('dcheoux', 'dcheoux');
$browser->
  get('/sfGuardGroup/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();
$browser->info('admin')->connexion('admin', 'admin');
$browser->
  get('/sfGuardGroup/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();
$browser->info('admin N1')->connexion('adminN1', 'adminN1');
$browser->
  get('/sfGuardGroup/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();

/**
* Nouveau group
* 1. Nouveau group
* 2. Formulaire invalide 1 erreur requis
* 3. Formulaire correct
*
**/
$browser->info('admin')->connexion('admin', 'admin');

$browser->get('/sfGuardGroup/new')->
  info('Nouveeau group')->
  info('1 erreur titre requis')->
  click('Mettre à jour', array('sf_guard_group' => array(
  )))->
  with('form')->begin()->
    hasErrors(1)->
  end()->
  info('Formulaire correcte')->
  click('Mettre à jour', array('sf_guard_group' => array(
    'name'     => 'test',
  )))->
  with('form')->begin()->
    hasErrors(false)->
  end()->
  with('response')->isRedirected()->
  followRedirect()->
  info('Redirection vers le groupe')->
    with('request')->begin()->
      isParameter('module', 'sfGuardGroup')->
      isParameter('action', 'edit')->
    end();
    /*click('Mettre à jour', array('tbl_slider' => array(
      'title' => 'Titre image new 1',
      'link'  => 'http://www.google.com'
    )))->
    with('response')->isRedirected()->
    followRedirect();*/
$browser->deconnexion();

