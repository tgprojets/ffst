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
  get('/params/majorDate')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('federal2')->connexion('federal2', 'federal2');
$browser->
  get('/params/majorDate')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('teco')->connexion('teco', 'teco');
$browser->
  get('/params/majorDate')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('stats')->connexion('stats', 'stats');
$browser->
  get('/params/majorDate')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('visuel')->connexion('visuel', 'visuel');
$browser->
  get('/params/majorDate')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('dcheoux')->connexion('dcheoux', 'dcheoux');
$browser->
  get('/params/majorDate')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();
$browser->info('admin')->connexion('admin', 'admin');
$browser->
  get('/params/majorDate')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();
$browser->info('admin N1')->connexion('adminN1', 'adminN1');
$browser->
  get('/params/majorDate')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();

/**
* 1. Connexion admin
* 2. Formulaire invalide Requis
* 3. Formulaire invalide M > 12 & J > 31
* 4. Formulaire valide
* 5. Vérifier les valeurs Int M, Int J, Renew J, Renew M
*
**/
$browser->info('admin')->connexion('admin', 'admin');
$browser->get('/params/majorDate')->
  info('Formulaire invalide Requis 4')->
  click('Enregistrer', array('param' => array(
  )))->
  with('form')->begin()->
    hasErrors(4)->
  end()->
  info('Formulaire invalide M > 12 & J > 31 renew')->
  click('Enregistrer', array('param' => array(
    'date_major_renew_day'   => '10',
    'date_major_renew_month' => '10',
    'date_major_int_day'     => '32',
    'date_major_int_month'   => '13'
  )))->
  with('form')->begin()->
    hasErrors(2)->
  end()->
  info('Formulaire invalide M > 12 & J > 31 INT')->
  click('Enregistrer', array('param' => array(
    'date_major_renew_day'   => '32',
    'date_major_renew_month' => '13',
    'date_major_int_day'     => '30',
    'date_major_int_month'   => '4'
  )))->
  with('form')->begin()->
    hasErrors(2)->
  end()->
  info('Formulaire valide')->
  click('Enregistrer', array('param' => array(
    'date_major_renew_day'   => '29',
    'date_major_renew_month' => '12',
    'date_major_int_day'     => '30',
    'date_major_int_month'   => '4'
  )))->
  with('form')->begin()->
    hasErrors(false)->
  end();

if (Licence::getDateMajor('renew', 'day') == 29) {
    $browser->test()->pass('Date renew day OK');
} else {
    $browser->test()->fail('Date renew day KO');
}
if (Licence::getDateMajor('renew', 'month') == 12) {
    $browser->test()->pass('Date renew month OK');
} else {
    $browser->test()->fail('Date renew month KO');
}
if (Licence::getDateMajor('int', 'day') == 30) {
    $browser->test()->pass('Date int day OK');
} else {
    $browser->test()->fail('Date int day KO');
}
if (Licence::getDateMajor('int', 'month') == 4) {
    $browser->test()->pass('Date int month OK');
} else {
    $browser->test()->fail('Date int month KO');
}