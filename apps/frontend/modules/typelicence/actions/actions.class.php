<?php

require_once dirname(__FILE__).'/../lib/typelicenceGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/typelicenceGeneratorHelper.class.php';

/**
 * typelicence actions.
 *
 * @package    sf_sandbox
 * @subpackage typelicence
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class typelicenceActions extends autoTypelicenceActions
{
    public function executeListMovedown(sfWebRequest $request)
    {
      $this->oTypeLicence = $this->getRoute()->getObject();
      $this->oTypeLicence->moveDown();
      $this->redirect('@tbl_typelicence');
    }
    public function executeListMoveup(sfWebRequest $request)
    {
      $this->oTypeLicence = $this->getRoute()->getObject();
      $this->oTypeLicence->moveUp();
      $this->redirect('@tbl_typelicence');
    }
  
}
