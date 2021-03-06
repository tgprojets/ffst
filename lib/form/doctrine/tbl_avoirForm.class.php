<?php

/**
 * tbl_avoir form.
 *
 * @package    sf_sandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class tbl_avoirForm extends Basetbl_avoirForm
{
  public function save($con = null)
  {
    $aValues = $this->processValues($this->getValues());
    if ($this->isNew()) {
        $oPayment = new tbl_avoir();
    } else {
      $oPayment = Doctrine::getTable('tbl_avoir')->find($aValues['id']);
    }
    if ($this->isValid()) {
        $nIdUser = sfContext::getInstance()->getUser()->getGuardUser()->getId();
        $oPayment->setLib($aValues['lib'])
                 ->setAmount($aValues['amount'])
                 ->setIsBrouillon(false)
                 ->setIdProfil($aValues['id_profil']==''?null:$aValues['id_profil'])
                 ->setIdClub($aValues['id_club'])
                 ->setIdLigue($aValues['id_ligue'])
                 ->setIdUser($nIdUser)
                 ->save();
    }
    return $oPayment;
  }
  public function configure()
  {
    unset($this['num'], $this['is_brouillon'], $this['is_used'], $this['relation_table'], $this['id_bordereau'], $this['id_user'], $this['id_typepayment'], $this['created_at'], $this['updated_at']);
    $this->buildWidget();
    $this->buildValidator();
  }
  public function buildWidget()
  {
        $this->widgetSchema['id_profil']            = new sfWidgetFormChoice(array(
            'label'            => 'Cherche licencié (Nom prénom)',
            'choices'          => array(),
            'renderer_class'   => 'sfWidgetFormDoctrineJQueryAutocompleter',
            'renderer_options' => array('model' => 'tbl_profil', 'url' => sfContext::getInstance()->getController()->genUrl('@ajax_getLicence'), 'config' => "{max: 20}"),
        ));
  }

  public function buildValidator()
  {
      $this->setValidator('lib', new sfValidatorString(
      array('required' => true),
      array(
       'required' => 'Libellé de la note est requise',
      )));
      $this->setValidator('amount', new sfValidatorString(
      array('required' => true),
      array(
       'required' => 'Montant de la note est requise',
      )));
      $this->setValidator('id_profil', new sfValidatorString(array('required' => false)));
      $this->validatorSchema->setPostValidator(new sfValidatorAnd(
            array(
              new sfValidatorCallback(array('callback'=> array($this, 'checkProprietaire'))),
       ))
    );

  }
  public function checkProprietaire($validator, $values)
  {
    if (empty($values['id_profil']) && empty($values['id_club']) && empty($values['id_ligue']))
    {
      throw new sfValidatorError($validator, 'Saisir un propriétaire.');
    }
    return $values;
  }
}
