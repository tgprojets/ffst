<?php
/**
* Scénario
*
* Authorization
*
* 1. federal 1 yes
* 2. federal 2 yes
* 3. teco yes
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
  get('/category/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();
$browser->info('federal2')->connexion('federal2', 'federal2');
$browser->
  get('/category/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();
$browser->info('teco')->connexion('teco', 'teco');
$browser->
  get('/category/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();
$browser->info('stats')->connexion('stats', 'stats');
$browser->
  get('/category/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('visuel')->connexion('visuel', 'visuel');
$browser->
  get('/category/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('dcheoux')->connexion('dcheoux', 'dcheoux');
$browser->
  get('/category/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();
$browser->info('admin')->connexion('admin', 'admin');
$browser->
  get('/category/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();
$browser->info('admin N1')->connexion('adminN1', 'adminN1');
$browser->
  get('/category/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();

/**
* Ajouter une category
* Formulaire 2 erreurs requis
* Formulaire valide
*
*/
$browser->info('teco')->connexion('teco', 'teco');
$browser->get('/category/new')->
  info('Nouvelle catégorie')->
  info('2 erreurs Lib & code requis')->
  click('Mettre à jour', array('tbl_category' => array(
  )))->
  with('form')->begin()->
    hasErrors(2)->
  end()->
  info('Formulaire correcte')->
  click('Mettre à jour', array('tbl_category' => array(
    'lib'     => 'test',
    'code'    => 'TEST',
  )))->
  with('form')->begin()->
    hasErrors(false)->
  end()->
  with('response')->isRedirected()->
  followRedirect()->
  info('Redirection vers la catégorie')->
    with('request')->begin()->
      isParameter('module', 'category')->
      isParameter('action', 'edit')->
    end()->
    click('Mettre à jour', array('tbl_category' => array(
      'lib'   => 'test 1',
      'code'  => 'TEST'
    )))->
    with('response')->isRedirected()->
    followRedirect();
$browser->deconnexion();