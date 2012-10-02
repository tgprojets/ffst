<?php

/**
 * tbl_mandat form.
 *
 * @package    sf_sandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class tbl_mandatForm extends Basetbl_mandatForm
{
  public function configure()
  {
    $this->widgetSchema['id_profil']                 = new sfWidgetFormInputHidden();
    $nIdProfil = $this->options['id_profil'];
    $this->setDefault('id_profil', $nIdProfil);
    $this->widgetSchema['date_begin']              = new sfWidgetFormI18nDate(array(
          'culture' => 'fr',
          'format' => '%day% %month% %year%',
    ));
    $this->widgetSchema['date_end']              = new sfWidgetFormI18nDate(array(
          'culture' => 'fr',
          'format' => '%day% %month% %year%',
    ));
    $this->setValidator('date_begin',   new sfValidatorDate(array('required' => true)));
    $this->setValidator('date_end',   new sfValidatorDate(array('required' => true)));
    $this->widgetSchema->setLabels(array(
      'date_begin'          => 'Date de début',
      'date_end'            => 'Date de fin',
      'fonction_actuel'     => 'Poste',
    ));
  }
}
