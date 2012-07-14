<?php
class browserTestFunctional extends sfTestFunctional
{
  public function loadData($psFile)
  {
    Doctrine_Core::loadData(sfConfig::get('sf_test_dir').'/fixtures/'.$psFile);
    return $this;
  }
  public function deconnexion() {
    $this->get('/sfGuardAuth/signout');
  }
  public function connexion($psUser, $psPassword, $bValide = false) {
      $this->
      get('/sfGuardAuth/signin')->
      info('Module connexion')->
      with('request')->begin()->
        isParameter('module', 'sfGuardAuth')->
        isParameter('action', 'signin')->
      end()->
      click("Se connecter", array('signin' => array(
        'username'      => $psUser,
        'password'      => $psPassword,
      )))->
      with('form')->begin()->
        hasErrors($bValide)->
      end();
  }
  public function testPage($psModule, $psAction, $psModuleRedirect="moncompte", $psActionRedirect="index") {
    $this->
      get('/'.$psModule.'/'.$psAction)->
      with('request')->begin()->
        isParameter('module', $psModule)->
        isParameter('action', $psAction)->
      end()->

      with('response')->isRedirected()->
        followRedirect()->
        with('request')->begin()->
          isParameter('module', $psModuleRedirect)->
          isParameter('action', $psActionRedirect)->
      end()
    ;
  }
  public function testPageFront($psModule, $psAction, $psModuleRedirect="main", $psActionRedirect="disable") {
    $this->
      get('/'.$psModule.'/'.$psAction)->
      with('request')->begin()->
        isParameter('module', $psModule)->
        isParameter('action', $psAction)->
      end()->

      with('response')->isRedirected()->
        followRedirect()->
        with('request')->begin()->
          isParameter('module', $psModuleRedirect)->
          isParameter('action', $psActionRedirect)->
      end()
    ;
  }
}
