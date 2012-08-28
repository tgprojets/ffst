<?php
/**
 * sfGuardFormSignin for sfGuardAuth signin action
 *
 * @package    noname-airsoft
 * @subpackage form
 * @author     Thomas GILBERT <tgilbert@tgprojets.fr>
 * @version    1.0 GIT
 */
class ParamForm extends sfForm
{

  public function setup()
  {
      $this->setWidgets(array(
        'date_major_renew_day'          => new sfWidgetFormInputText(),
        'date_major_renew_month'        => new sfWidgetFormInputText(),
        'date_major_int_day'            => new sfWidgetFormInputText(),
        'date_major_int_month'          => new sfWidgetFormInputText(),
      ));
      $this->widgetSchema->setLabels(array(
        'date_major_renew_day'   => 'Majoration renouvellement jour',
        'date_major_renew_month'   => 'Majoration renouvellement mois',
        'date_major_int_day'     => 'Majoration international jour',
        'date_major_int_month'     => 'Majoration international mois',
      ));
      $this->setValidators(array(
      'date_major_renew_day' => new sfValidatorInteger(
        array('required' => true, 'min' => 1, 'max' => 31),
          array(
            'required' => 'Jour requis',
          )
      ),
      'date_major_renew_month' => new sfValidatorInteger(
        array('required' => true, 'min' => 1, 'max' => 12),
          array(
            'required' => 'Mois requis',
          )
      ),
      'date_major_int_day' => new sfValidatorInteger(
        array('required' => true, 'min' => 1, 'max' => 31),
          array(
            'required' => 'Utilisateur requis',
          )
      ),
      'date_major_int_month' => new sfValidatorInteger(
        array('required' => true, 'min' => 1, 'max' => 12),
          array(
            'required' => 'Utilisateur requis',
          )
      ),
    ));

    $this->setDefault('date_major_renew_day', Licence::getDateMajor('renew', 'day'));
    $this->setDefault('date_major_renew_month', Licence::getDateMajor('renew', 'month'));
    $this->setDefault('date_major_int_day', Licence::getDateMajor('int', 'day'));
    $this->setDefault('date_major_int_month', Licence::getDateMajor('int', 'month'));

    $this->widgetSchema->setNameFormat('param[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->widgetSchema->setFormFormatterName('list');
  }
  public function save() {
    $aValues = $this->getValues();
    $oInitParam = new InitParam();
    $oInitParam->addValue('Date validation', 'date_renew_day', $aValues['date_major_renew_day']);
    $oInitParam->addValue('Date validation', 'date_renew_month', $aValues['date_major_renew_month']);
    $oInitParam->addValue('Date validation', 'date_int_day', $aValues['date_major_int_day']);
    $oInitParam->addValue('Date validation', 'date_int_month', $aValues['date_major_int_month']);
  }
}
