<?php
/**
 * sfGuardFormSignin for sfGuardAuth signin action
 *
 * @package    noname-airsoft
 * @subpackage form
 * @author     Thomas GILBERT <tgilbert@tgprojets.fr>
 * @version    1.0 GIT
 */
class ModifpasswordForm extends sfForm
{

  public function setup()
  {
      $nIdUser = $this->options['id'];
      $this->setWidgets(array(
        'id'                        => new sfWidgetFormInputHidden(),
        'password'                  => new sfWidgetFormInputText(),
      ));
      $this->widgetSchema->setLabels(array(
        'password_forgot'   => 'Nouveau mot de passe',
      ));
      $this->setValidators(array(
      'id' => new sfValidatorString(
        array('required' => true),
          array(
            'required' => 'Utilisateur requis',
          )
      ),
      'password'      => new sfValidatorString(
                    array('required' => true, 'min_length' => 5, 'max_length' => 20),
                    array(
                                'min_length' => 'Le mot de passe est trop court. 5 caractères minimum.',
                                'max_length' => 'Le mot de passe est trop long. 20 caractères maximum',
                                'required' => 'Mot de passe requis',
                                'invalid' => 'Le mot de passe doit etre compris entre 5 et 20 caractères'
                            )
      ),
    ));
    $this->setDefault('id', $nIdUser);

    $this->widgetSchema->setNameFormat('modifpassword[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->widgetSchema->setFormFormatterName('list');
  }
}
