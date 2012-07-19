<?php
/**
* Scénario
*
* 1. connexion federal 1 Not OK
* 2. connexion federal 1 OK
* 3. connexion federal 2 Not OK
* 4. connexion federal 2 OK
* 5. connexion Admin
*
**/
include(dirname(__FILE__).'/../../bootstrap/functional.php');

ob_start();
$browser = new browserTestFunctional(new sfBrowser());
$browser->loadData('sfGuard.yml');
$browser->info('federal1 Not ok')->connexion('federal1', 'federal', true);
$browser->info('federal1 ok')->connexion('federal1', 'federal1');
$browser->deconnexion();
$browser->info('federal2 Not ok')->connexion('federal2', 'federal', true);
$browser->info('federal2 ok')->connexion('federal2', 'federal2');
$browser->deconnexion();
$browser->info('admin module tracability')->connexion('admin', 'admin');
$browser->
  get('/tracability/index')->
  with('response')->begin()
    ->matches('#127.0.0.1#')
    ->matches('#federal1#')
    ->matches('#federal2#')
    ->matches('#admin#')
    ->matches('!#visuel#')
    ->matches('!#teco#')
    ->matches('!#stats#')
  ->end();
  $browser->deconnexion();
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
$browser->info('federal1')->connexion('federal1', 'federal1');
$browser->
  get('/tracability/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('federal2')->connexion('federal2', 'federal2');
$browser->
  get('/tracability/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('teco')->connexion('teco', 'teco');
$browser->
  get('/tracability/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('stats')->connexion('stats', 'stats');
$browser->
  get('/tracability/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('visuel')->connexion('visuel', 'visuel');
$browser->
  get('/tracability/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('admin N1')->connexion('adminN1', 'adminN1');
$browser->
  get('/tracability/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();
$browser->info('dcheoux')->connexion('dcheoux', 'dcheoux');
$browser->
  get('/tracability/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();
$browser->info('admin')->connexion('admin', 'admin');
$browser->
  get('/tracability/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
