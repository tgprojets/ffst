<?php

/**
 * sfGuardPermission form.
 *
 * @package    sf_sandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrinePluginFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sfGuardPermissionForm extends PluginsfGuardPermissionForm
{
  public function setupInheritance()
  {
    if (!$this->isNew()) {
        unset($this['name'], $this['created_at'], $this['updated_at']);
    } else {
        unset($this['created_at'], $this['updated_at']);
        $this->setValidator('name', new sfValidatorString(array('max_length' => 255, 'required' => true),
            array(
                'max_length' => 'Le libellé est trop long. 255 caractères maximum',
                'required' => 'Libellé requis',
            )));

        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }
  }
}
