<?php

/**
 * tbl_params form.
 *
 * @package    sf_sandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class tbl_paramsForm extends Basetbl_paramsForm
{
  public function configure()
  {
    unset($this['code'], $this['lib']);
  }
}
