<?php

/**
 * tbl_payment form.
 *
 * @package    sf_sandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class tbl_paymentForm extends Basetbl_paymentForm
{
  public function save($con = null)
  {
    $aValues = $this->processValues($this->getValues());
    if ($this->isNew()) {
      $oPayment = new tbl_payment();
    } else {
      $oPayment = Doctrine::getTable('tbl_payment')->find($aValues['id']);
    }
    if ($this->isValid()) {
        $nIdUser = sfContext::getInstance()->getUser()->getGuardUser()->getId();
        $oPayment->setLib($aValues['lib'])
                 ->setDescription($aValues['description'])
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
    unset($this['num'], $this['date_payment'], $this['is_brouillon'], $this['is_payed'], $this['relation_table'], $this['id_bordereau'], $this['id_user'], $this['id_typepayment'], $this['created_at'], $this['updated_at']);
    if (!$this->isNew()) {
      unset($this['id_licence']);
    }
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
