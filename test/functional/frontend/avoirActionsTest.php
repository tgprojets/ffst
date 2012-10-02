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
  get('/avoir/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('federal2')->connexion('federal2', 'federal2');
$browser->
  get('/avoir/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('teco')->connexion('teco', 'teco');
$browser->
  get('/avoir/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('stats')->connexion('stats', 'stats');
$browser->
  get('/avoir/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('visuel')->connexion('visuel', 'visuel');
$browser->
  get('/avoir/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('dcheoux')->connexion('dcheoux', 'dcheoux');
$browser->
  get('/avoir/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();
$browser->info('admin')->connexion('admin', 'admin');
$browser->
  get('/avoir/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();
$browser->info('admin N1')->connexion('adminN1', 'adminN1');
$browser->
  get('/avoir/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();

/**
* Ajouter une note
* Formulaire 3 erreurs requis
* Formulaire valide
*
*/
$oClub = Doctrine::getTable('tbl_club')->findOneBy('name', 'DE1');
$browser->info('dcheoux')->connexion('dcheoux', 'dcheoux');
$browser->get('/avoir/new')->
  info('Nouvelle note')->
  info('3 erreurs requis')->
  click('Mettre à jour', array('tbl_avoir' => array(
  )))->
  with('form')->begin()->
    hasErrors(3)->
  end()->
  info('Formulaire correcte')->
  click('Mettre à jour', array('tbl_avoir' => array(
    'lib'     => 'test',
    'amount'  => '100',
    'id_club' => $oClub->getId()
  )))->
  with('form')->begin()->
    hasErrors(false)->
  end()->
  with('response')->isRedirected()->
    followRedirect();
$oAvoirs = Doctrine::getTable('tbl_avoir')->findAll();
$aAvoirs = array();
foreach($oAvoirs as $oAvoir)
{
    $aAvoirs[] = $oAvoir->getId();
    if($oAvoir->getIsUsed()) {
        $browser->test()->fail('Avoir utilisé');
    } else {
        $browser->test()->pass('Avoir inutilisé');
    }
}
$browser->get('/avoir/batchRegulation_cheque', array('ids' => $aAvoirs))->
  info('Régulation avoir');
$oAvoirs = Doctrine::getTable('tbl_avoir')->findAll();
foreach($oAvoirs as $oAvoir)
{
    if($oAvoir->getIsUsed()) {
        $browser->test()->pass('Avoir utilisé');
    } else {
        $browser->test()->fail('Avoir inutilisé');
    }
}
$browser->deconnexion();