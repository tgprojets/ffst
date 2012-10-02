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
$browser->deconnexion();
$browser->info('admin N1')->connexion('adminN1', 'adminN1');
$browser->
  get('/sfGuardUser/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();
/**
* Nouveau utilisateur
* 1. Nouveau utlisateur
* 2. Formulaire invalide 3 erreurs requis
* 3. Formulaire invalide 1 erreurs utilisateur existant
* 4. Formulaire invalide 1 erreurs Email existant
* 5. Formulaire correct
* 6. Connexion avec le nouveau utilisateur
*
**/
$browser->info('admin')->connexion('admin', 'admin');
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

/**
* 1. Connexion admin
* 2. Désactiver compte federal1
* 3. Connexion compte federal1
* 4. Erreur connexion impossible
* 5. Connexion admin
* 6. Activer compte federal1
* 7. Changer mot de passe
* 8. Connexion compte federal1 avec nouveau mot de passe
* 9. Statut 200
*
*
**/
$oUserFederal1 = Doctrine::getTable('sfGuardUser')->findOneBy('username', 'federal1');
$browser->info('Désactiver compte')->connexion('admin', 'admin');
$browser->get('/sfGuardUser/'.$oUserFederal1->getId().'/listActivate');
$browser->deconnexion();
$browser->
  get('/sfGuardAuth/signin')->
  click('Se connecter', array('signin' => array(
    'username' => 'federal1',
    'password' => 'federal1',
  )))->
  with('form')->begin()->
    hasErrors(1)->
  end();
$browser->deconnexion();
$browser->info('Changer mot de passe federal1 compte')->connexion('admin', 'admin');
$browser->get('/sfGuardUser/'.$oUserFederal1->getId().'/listActivate');
$browser->get('/sfGuardUser/'.$oUserFederal1->getId().'/listEditPassword')->
  click('Enregistrer', array('modifpassword' => array(
    'password' => 'test33',
  )))->
  with('form')->begin()->
    hasErrors(false)->
  end();
$browser->deconnexion();
$browser->info('Désactiver compte')->connexion('federal1', 'test33');
$browser->deconnexion();


/**
* Edition du compte
*
*
**/
$browser->info('Edition compte')->connexion('admin', 'admin');
$browser->get('/sfGuardUser/'.$oUserFederal1->getId().'/edit')->
  click('Mettre à jour', array('sf_guard_user' => array(
    'first_name'    => 'Fede',
    'last_name'     => 'Fede',
    'groups_list'     => array($oGroupN1->getId())
  )))->
  with('form')->begin()->
    hasErrors(false)->
  end();
$browser->deconnexion();
$browser->connexion('federal1', 'test33');
$browser->
  get('/sfGuardUser/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();
