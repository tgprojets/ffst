<?php

/**
 * sfGuardUser form.
 *
 * @package    sf_sandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrinePluginFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sfGuardUserForm extends PluginsfGuardUserForm
{
  public function configure()
  {
     parent::configure();
      unset($this['created_at'], $this['updated_at'], $this['algorithm'], $this['salt'], $this['last_login']);
      if ($this->getObject()->getId()) {
        unset($this['password']);
      } else {
        $this->widgetSchema['password'] = new sfWidgetFormInputPassword();
        $this->validatorSchema['password'] = new sfValidatorString(array('required' => true),
        array('required' => 'Mot de passe requis'));
      }
      $this->validatorSchema['username'] = new sfValidatorString(array('required' => true),
        array('required' => 'Nom utilisateur requis'));
      $this->validatorSchema['email_address'] = new sfValidatorEmail(
        array('required' => true),
        array(
            'required' => 'Email est requis',
            'invalid' => 'Email invalide'
        ));
  }
}
