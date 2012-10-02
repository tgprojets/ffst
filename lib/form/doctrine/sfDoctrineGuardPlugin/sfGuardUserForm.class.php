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
    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'sfGuardUser', 'column' => array('email_address'))),
        new sfValidatorDoctrineUnique(array('model' => 'sfGuardUser', 'column' => array('username'))),
        new sfValidatorCallback(array('callback'=> array($this, 'checkGroups')))
      ))
    );
  }

  public function checkGroups($validator, $values)
  {
    if (!empty($values['groups_list'])) {
      $aGroups = $values['groups_list'];
      $bAdmin = false;
      $oGroups = Doctrine::getTable('sfGuardGroup')->findBySql('id in ?', array($aGroups));
      $aAdmin = array('N1', 'N2A', 'N2B', 'N2C', 'N2D', 'N3', 'N4');
      foreach($oGroups as $oGroup)
      {
        //Group admin
        if (in_array($oGroup->getName(), $aAdmin)) {
          $bAdmin = true;
        }
      }
      if ($bAdmin) {
        foreach($oGroups as $oGroup)
        {
          //Group admin
          if ($oGroup->getName() == 'CLUB' || $oGroup->getName() == 'LIGUE') {
            throw new sfValidatorError($validator, 'Un utilisateur ne peut pas être administrateur et être affecté à un club ou une ligue.');
          }
        }
      }
    }
    return $values;
  }

}
