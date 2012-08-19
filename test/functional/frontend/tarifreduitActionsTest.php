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
  get('/tarifreduit/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('federal2')->connexion('federal2', 'federal2');
$browser->
  get('/tarifreduit/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('teco')->connexion('teco', 'teco');
$browser->
  get('/tarifreduit/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('stats')->connexion('stats', 'stats');
$browser->
  get('/tarifreduit/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('visuel')->connexion('visuel', 'visuel');
$browser->
  get('/tarifreduit/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('dcheoux')->connexion('dcheoux', 'dcheoux');
$browser->
  get('/tarifreduit/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();
$browser->info('admin')->connexion('admin', 'admin');
$browser->
  get('/tarifreduit/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();
$browser->info('admin N1')->connexion('adminN1', 'adminN1');
$browser->
  get('/tarifreduit/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();

/**
* Ajouter une tarifreduit
* Formulaire 2 erreurs requis
* Formulaire valide
*
*/
$oArticle = Doctrine::getTable('tbl_tarifreduit')->findOneBy('code', 'MIN');
$browser->info('dcheoux')->connexion('dcheoux', 'dcheoux');
$browser->get('/tarifreduit/'.$oArticle->getId().'/edit')->
  info('Edition tarif réduit prix')->
  click('Mettre à jour', array('tbl_tarifreduit' => array(
    'lib'     => 'test',
    'prix'    => '100',
  )))->
  with('form')->begin()->
    hasErrors(false)->
  end()->
  with('response')->isRedirected()->
  followRedirect()->
  info('Redirection vers le tarif réduit')->
    with('request')->begin()->
      isParameter('module', 'tarifreduit')->
      isParameter('action', 'edit')->
    end();
$browser->deconnexion();