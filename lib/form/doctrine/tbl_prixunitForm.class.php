<?php

/**
 * tbl_prixunit form.
 *
 * @package    sf_sandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class tbl_prixunitForm extends Basetbl_prixunitForm
{
  public function configure()
  {
    unset($this['created_at'], $this['updated_at'], $this['code']);
  }
}
