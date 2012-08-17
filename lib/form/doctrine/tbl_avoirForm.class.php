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
    }
    if ($this->isValid()) {
        $nIdUser = sfContext::getInstance()->getUser()->getGuardUser()->getId();
        $oPayment->setLib($aValues['lib'])
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
    unset($this['num'], $this['is_brouillon'], $this['is_used'], $this['relation_table'], $this['id_bordereau'], $this['id_user'], $this['id_typepayment'], $this['created_at'], $this['updated_at']);
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
