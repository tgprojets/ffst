<?php

class mainComponents extends sfComponents
{
  public function executeCheckNavigateur(sfWebRequest $request)
  {
      if (@ereg('MSIE 6', $_SERVER['HTTP_USER_AGENT'])) {
         $this->bNavigateurIncompatible = true;
      }
  }
}
