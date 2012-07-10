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
  get('/sfGuardUser/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('federal2')->connexion('federal2', 'federal2');
$browser->
  get('/sfGuardUser/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('teco')->connexion('teco', 'teco');
$browser->
  get('/sfGuardUser/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('stats')->connexion('stats', 'stats');
$browser->
  get('/sfGuardUser/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('visuel')->connexion('visuel', 'visuel');
$browser->
  get('/sfGuardUser/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('dcheoux')->connexion('dcheoux', 'dcheoux');
$browser->
  get('/sfGuardUser/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();
$browser->info('admin')->connexion('admin', 'admin');
$browser->
  get('/sfGuardUser/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();

/**
* Nouveau utilisateur
*
*
**/
$oGroupN1 = Doctrine::getTable('sfGuardGroup')->findOneBy('name', 'N1');
$browser->get('/sfGuardUser/new')->
  info('Nouveau utilisateur')->
  info('3 erreurs titre requis')->
  click('Mettre à jour', array('sf_guard_user' => array(
  )))->
  with('form')->begin()->
    hasErrors(3)->
  end()->
  info('Formulaire incorrecte utilisateur existant')->
  click('Mettre à jour', array('sf_guard_user' => array(
    'email_address'  => 'test@test.com',
    'username'       => 'admin',
    'password'       => 'admin',
  )))->
  with('form')->begin()->
    hasErrors(1)->
  end()->
  info('Formulaire incorrecte email existant')->
  click('Mettre à jour', array('sf_guard_user' => array(
    'email_address'  => 'tgilbert@tgprojets.fr',
    'username'       => 'plop',
    'password'       => 'admin',
  )))->
  with('form')->begin()->
    hasErrors(1)->
  end()->
  info('Formulaire correcte')->
  click('Mettre à jour', array('sf_guard_user' => array(
    'email_address'  => 'test@test.com',
    'username'       => 'plop',
    'password'       => 'admin',
    'groups_list'     => array($oGroupN1->getId())
  )))->
  with('form')->begin()->
    hasErrors(false)->
  end();
$browser->deconnexion();
$browser->info('plop')->connexion('plop', 'admin');
$browser->
  get('/sfGuardUser/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();
