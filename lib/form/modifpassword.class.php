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
      $this->setWidgets(array(
      'id'                        => new sfWidgetFormInputHidden(),
      'password_old'              => new sfWidgetFormInputPassword(),
      'password_forgot'           => new sfWidgetFormInputPassword(),
      'repassword'                => new sfWidgetFormInputPassword()
          ));
      $this->widgetSchema->setLabels(array(
      'password_old'      => 'Votre mot de passe actuel',
      'password_forgot'   => 'Nouveau mot de passe',
      'repassword'        => 'Confirmer votre mot de passe'
          ));
      $this->setValidators(array(
      'password_old' => new sfValidatorString(
                                          array('required' => true),
                                          array(
                                'required' => 'Mot de passe requis',
                                'invalid' => 'Le mot de passe doit etre compris entre 6 et 20 caractères')
      ),
      'password_forgot'      => new sfValidatorString(
                    array('required' => true, 'min_length' => 5, 'max_length' => 20),
                    array(
                                'min_length' => 'Le mot de passe est trop court. 5 caractères minimum.',
                                'max_length' => 'Le mot de passe est trop long. 20 caractères maximum',
                                'required' => 'Mot de passe requis',
                                'invalid' => 'Le mot de passe doit etre compris entre 5 et 20 caractères'
                            )
      ),
     'repassword'   => new sfValidatorString(
                        array('required' => true, 'min_length' => 5, 'max_length' => 20),
                        array(
                            'min_length' => 'Le mot de passe est trop court. 5 caractères minimum.',
                            'max_length' => 'Le mot de passe est trop long. 20 caractères maximum',
                            'required' => 'Mot de passe requis',
                            'invalid' => 'Le mot de passe doit etre compris entre 5 et 20 caractères'
                            )
    )));
      $this->validatorSchema->setPostValidator(new sfValidatorAnd(
                   array(
                     new sfValidatorSchemaCompare('password_forgot',  sfValidatorSchemaCompare::EQUAL, 'repassword'),
                     new sfValidatorCallback(array('callback'=> array($this, 'checkPassword')))
                    ))
              );

      $this->widgetSchema->setNameFormat('modifpassword[%s]');

      $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

      $this->widgetSchema->setFormFormatterName('list');
  }

  public function checkPassword($validator, $values) {
        if (! empty($values['password_old'])) {
           $oUser = sfContext::getInstance()->getUser();
           $bCheck = $oUser->checkPassword($values['password_old']);
            if ($bCheck) {
                    // Login dispo
               return $values;
           } else {
                   // Login pas dispo
               throw new sfValidatorError($validator, 'Mot de passe incorrecte');
           }
       }
   }
}
