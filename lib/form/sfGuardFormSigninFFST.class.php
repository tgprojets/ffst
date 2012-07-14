<?php
/**
 * sfGuardFormSignin for sfGuardAuth signin action
 *
 * @package    sfDoctrineGuardPlugin
 * @subpackage form
 * @author     Thomas GILBERT <tgilbert@tgprojets.fr>
 * @version    1.0 GIT
 */
class sfGuardFormSigninFFST extends BasesfGuardFormSignin
{
  /**
   * @see sfForm
   */
  public function setup() {

    $this->setWidgets(array(
      'username' => new sfWidgetFormInputText(),
      'password' => new sfWidgetFormInputPassword(array('type' => 'password')),
    ));

    $this->setValidators(array(
      'username' => new sfValidatorString(),
      'password' => new sfValidatorString(),
    ));

    $this->widgetSchema->setLabels(array(
      'username'   => 'Login',
      'password'   => 'Password',
    ));

    $this->validatorSchema->setPostValidator(new sfGuardValidatorUser());

    $this->widgetSchema->setNameFormat('signin[%s]');

  }
}
