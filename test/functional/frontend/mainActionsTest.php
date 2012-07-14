<?php
/**
* Scénario
*
* 1. page accueil
* 2. Page 401 not authorize
* 3. Connexion user admin
* 4. Page 200
*
**/
include(dirname(__FILE__).'/../../bootstrap/functional.php');

ob_start();
$browser = new browserTestFunctional(new sfBrowser());
$browser->loadData('sfGuard.yml');
$browser->
  get('/main/index')->

  with('request')->begin()->
    isParameter('module', 'main')->
    isParameter('action', 'index')->
  end()->

  with('response')->begin()->
    isStatusCode(401)->
  end();
$browser->connexion('admin', 'admin');
$browser->
  get('/main/index')->

  with('response')->begin()->
    isStatusCode(200)->
  end();