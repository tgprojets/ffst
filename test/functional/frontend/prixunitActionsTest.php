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
  get('/prixunit/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('federal2')->connexion('federal2', 'federal2');
$browser->
  get('/prixunit/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('teco')->connexion('teco', 'teco');
$browser->
  get('/prixunit/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('stats')->connexion('stats', 'stats');
$browser->
  get('/prixunit/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('visuel')->connexion('visuel', 'visuel');
$browser->
  get('/prixunit/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('dcheoux')->connexion('dcheoux', 'dcheoux');
$browser->
  get('/prixunit/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();
$browser->info('admin')->connexion('admin', 'admin');
$browser->
  get('/prixunit/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();
$browser->info('admin N1')->connexion('adminN1', 'adminN1');
$browser->
  get('/prixunit/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();

/**
* Ajouter une prixunit
* Formulaire 2 erreurs requis
* Formulaire valide
*
*/
$oArticle = Doctrine::getTable('tbl_prixunit')->findOneBy('code', 'CA');
$browser->info('dcheoux')->connexion('dcheoux', 'dcheoux');
$browser->get('/prixunit/'.$oArticle->getId().'/edit')->
  info('Edition article prix')->
  click('Mettre à jour', array('tbl_prixunit' => array(
    'lib'     => 'test',
    'prix'    => '100',
  )))->
  with('form')->begin()->
    hasErrors(false)->
  end()->
  with('response')->isRedirected()->
  followRedirect()->
  info('Redirection vers l\'article')->
    with('request')->begin()->
      isParameter('module', 'prixunit')->
      isParameter('action', 'edit')->
    end();
$browser->deconnexion();