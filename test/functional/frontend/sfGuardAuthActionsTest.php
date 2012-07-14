<?php
/**
* Scénario
*
* 1. sfGuard Signin
*  Champs du formulaire
*   signin_username / signin_password / bouton : Se connecter
* 2. Formulaire invalide 2 erreurs (vide)
* 3. Formulaire invalide 1 erreur (incorrect login)
* 4. Formulaire valide admin
* 5. Logout 401
* 6. Connexion dcheoux
* 7. Connexion federal1
* 8. Connexion federal2
* 9. Connexion teco
* 10. Connexion stats
* 11. Connexion visuel
**/
include(dirname(__FILE__).'/../../bootstrap/functional.php');

ob_start();
$browser = new browserTestFunctional(new sfBrowser());
$browser->loadData('sfGuard.yml');
$browser->
  get('/sfGuardAuth/signin')->

  with('request')->begin()->
    isParameter('module', 'sfGuardAuth')->
    isParameter('action', 'signin')->
  end()->

  with('response')->begin()->
    matches('#signin_username#')->
    matches('#signin_password#')->
    matches('#Se connecter#')->
    isStatusCode(401)->
  end();
$browser->click('Se connecter', array('signin' => array()))->
  info('Formulaire vide')->
  with('form')->begin()->
    hasErrors(2)->
  end()->
  info('Formulaire user incorrect ou mot de passe')->
  click('Se connecter', array('signin' => array(
    'username' => 'test',
    'password' => 'test',
  )))->
  with('form')->begin()->
    hasErrors(1)->
  end()->
  info('Formulaire valide')->
  click('Se connecter', array('signin' => array(
    'username' => 'admin',
    'password' => 'admin',
  )))->
  with('form')->begin()->
    hasErrors(false)->
  end();
$browser->
  get('/main/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->
  info('Déconnexion')->
  get('/sfGuardAuth/signout')->
  get('/main/index')->
  with('response')->begin()->
    isStatusCode(401)->
  end();
$browser->info('dcheoux')->connexion('dcheoux', 'dcheoux');
$browser->deconnexion();
$browser->info('federal1')->connexion('federal1', 'federal1');
$browser->deconnexion();
$browser->info('federal2')->connexion('federal2', 'federal2');
$browser->deconnexion();
$browser->info('teco')->connexion('teco', 'teco');
$browser->deconnexion();
$browser->info('stats')->connexion('stats', 'stats');
$browser->deconnexion();
$browser->info('visuel')->connexion('visuel', 'visuel');
$browser->deconnexion();