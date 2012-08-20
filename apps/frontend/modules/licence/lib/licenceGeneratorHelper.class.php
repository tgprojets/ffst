<?php

/**
 * licence module helper.
 *
 * @package    sf_sandbox
 * @subpackage licence
 * @author     Your name here
 * @version    SVN: $Id: helper.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class licenceGeneratorHelper extends BaseLicenceGeneratorHelper
{
  public function linkTo_saveAndSaisie($object, $params)
  {
    return '<li class="sf_admin_action_save_and_saisie"><input type="submit" value="'.__($params['label'], array(), 'sf_admin').'" name="_save_and_saisie" /></li>';
  }
}
