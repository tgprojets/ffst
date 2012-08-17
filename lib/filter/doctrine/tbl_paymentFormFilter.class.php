<?php

/**
 * tbl_payment filter form.
 *
 * @package    sf_sandbox
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class tbl_paymentFormFilter extends Basetbl_paymentFormFilter
{
  public function configure()
  {
        $this->widgetSchema['id_licence']            = new sfWidgetFormChoice(array(
            'label'            => 'Cherche licencié (Nom prénom)',
            'choices'          => array(),
            'renderer_class'   => 'sfWidgetFormDoctrineJQueryAutocompleter',
            'renderer_options' => array('model' => 'tbl_profil', 'url' => sfContext::getInstance()->getController()->genUrl('@ajax_getLicence')),
        ));
        $this->setValidator('id_licence', new sfValidatorString(array('required' => false)));
  }
}
