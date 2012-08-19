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
  get('/regulation/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('federal2')->connexion('federal2', 'federal2');
$browser->
  get('/regulation/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('teco')->connexion('teco', 'teco');
$browser->
  get('/regulation/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('stats')->connexion('stats', 'stats');
$browser->
  get('/regulation/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('visuel')->connexion('visuel', 'visuel');
$browser->
  get('/regulation/index')->
  with('response')->begin()->
    isStatusCode(403)->
  end();
$browser->deconnexion();
$browser->info('dcheoux')->connexion('dcheoux', 'dcheoux');
$browser->
  get('/regulation/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();
$browser->info('admin')->connexion('admin', 'admin');
$browser->
  get('/regulation/index')->
  with('response')->begin()->
    isStatusCode(200)->
  end();
$browser->deconnexion();
$browser->info('admin N1')->connexion('adminN1', 'adminN1');
$browser->
  get('/regulation/index')->
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
$browser->get('/regulation/new')->
  info('Nouvelle note')->
  info('3 erreurs requis')->
  click('Mettre à jour', array('tbl_payment' => array(
  )))->
  with('form')->begin()->
    hasErrors(3)->
  end()->
  info('Formulaire correcte')->
  click('Mettre à jour', array('tbl_payment' => array(
    'lib'     => 'test',
    'amount'  => '100',
    'id_club' => $oClub->getId()
  )))->
  with('form')->begin()->
    hasErrors(false)->
  end()->
  with('response')->isRedirected()->
    followRedirect();
$oPayments = Doctrine::getTable('tbl_payment')->findAll();
$aPayments = array();
foreach($oPayments as $oPayment)
{
    $aPayments[] = $oPayment->getId();
    if($oPayment->getIsPayed()) {
        $browser->test()->fail('Payment payé');
    } else {
        $browser->test()->pass('Payment impayé');
    }
}
$browser->get('/regulation/batchRegulation_cheque', array('ids' => $aPayments))->
  info('Régulation paiement');
$oPayments = Doctrine::getTable('tbl_payment')->findAll();
foreach($oPayments as $oPayment)
{
    if($oPayment->getIsPayed()) {
        $browser->test()->pass('Payment payé');
    } else {
        $browser->test()->fail('Payment impayé');
    }
}


$browser->deconnexion();