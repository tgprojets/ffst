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
    }
    if ($this->isValid()) {
        $nIdUser = sfContext::getInstance()->getUser()->getGuardUser()->getId();
        $oPayment->setLib($aValues['lib'])
                 ->setDescription($aValues['description'])
                 ->setAmount($aValues['amount'])
                 ->setIsBrouillon(false)
                 ->setIdLicence($aValues['id_licence']==''?null:$aValues['id_licence'])
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
    $this->buildWidget();
    $this->buildValidator();
  }
  public function buildWidget()
  {
        $this->widgetSchema['id_licence']            = new sfWidgetFormChoice(array(
            'label'            => 'Cherche licencié (Nom prénom)',
            'choices'          => array(),
            'renderer_class'   => 'sfWidgetFormDoctrineJQueryAutocompleter',
            'renderer_options' => array('model' => 'tbl_profil', 'url' => sfContext::getInstance()->getController()->genUrl('@ajax_getLicence')),
        ));
  }

  public function buildValidator()
  {
      $this->setValidator('id_licence', new sfValidatorString(array('required' => false)));
  }

}
