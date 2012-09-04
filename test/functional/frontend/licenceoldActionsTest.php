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
  get('/licenceold/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();
$browser->info('federal2')->connexion('federal2', 'federal2');
$browser->
  get('/licenceold/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();
$browser->info('teco')->connexion('teco', 'teco');
$browser->
  get('/licenceold/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('stats')->connexion('stats', 'stats');
$browser->
  get('/licenceold/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();
$browser->info('visuel')->connexion('visuel', 'visuel');
$browser->
  get('/licenceold/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();
$browser->info('dcheoux')->connexion('dcheoux', 'dcheoux');
$browser->
  get('/licenceold/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();
$browser->info('admin')->connexion('admin', 'admin');
$browser->
  get('/licenceold/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();
$browser->info('admin N1')->connexion('adminN1', 'adminN1');
$browser->
  get('/licenceold/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();
$browser->info('club 1')->connexion('club1', 'club1');
$browser->
  get('/licenceold/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();
$browser->info('ligue 1')->connexion('ligue1', 'ligue1');
$browser->
  get('/licenceold/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();

/**
* 1. Impossible d'ajouter une licence
* 2. Impossible de la modifier
* 3. Impossible de le supprimer
* 4. Possible de voir la fiche
*
**/
$oLicences = Doctrine::getTable('tbl_licence')->findAll();
$oLicence = $oLicences->getLast();
$browser->info('federal1')->connexion('federal1', 'federal1');
$browser->get('/licenceold/new')->
    with('response')->isRedirected()->
    followRedirect()->
    with('request')->begin()->
      isParameter('module', 'licenceold')->
      isParameter('action', 'index')->
    end();
$browser->get('/licenceold/'.$oLicence->getId().'/edit')->
    with('response')->isRedirected()->
    followRedirect()
    ->with('request')->begin()
      ->isParameter('module', 'licenceold')
      ->isParameter('action', 'index')
    ->end();
$browser->get('/licenceold/'.$oLicence->getId().'/delete')->
    with('response')->isRedirected()->
    followRedirect()
    ->with('request')->begin()
      ->isParameter('module', 'licenceold')
      ->isParameter('action', 'index')
    ->end();
$browser->get('/licenceold/'.$oLicence->getId().'/ListShow')->
    with('response')->isRedirected(false)
    ->with('request')->begin()
      ->isParameter('module', 'licenceold')
      ->isParameter('action', 'ListShow')
    ->end();
