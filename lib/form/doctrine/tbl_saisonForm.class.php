<?php

/**
 * tbl_saison form.
 *
 * @package    sf_sandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class tbl_saisonForm extends Basetbl_saisonForm
{
  public function configure()
  {
    unset($this['is_outstanding'], $this['year_licence']);
    if ($this->isNew()) {
        unset($this['id']);
    } else {
        unset($this['id']);
    }
    $this->widgetSchema->setLabels(array(
        'day_begin'     => 'Jour début de saison',
        'month_begin'   => 'Mois début de saison',
        'day_end'       => 'Jour fin de saison',
        'month_end'     => 'Mois fin de saison',
    ));

    $this->setValidators(array(
      'day_begin' => new sfValidatorInteger(
        array('required' => true, 'min' => 1, 'max' => 31),
          array(
            'required' => 'Jour requis',
          )
      ),
      'month_begin' => new sfValidatorInteger(
        array('required' => true, 'min' => 1, 'max' => 12),
          array(
            'required' => 'Mois requis',
          )
      ),
      'day_end' => new sfValidatorInteger(
        array('required' => true, 'min' => 1, 'max' => 31),
          array(
            'required' => 'Jour requis',
          )
      ),
      'month_end' => new sfValidatorInteger(
        array('required' => true, 'min' => 1, 'max' => 12),
          array(
            'required' => 'Mois requis',
          )
      ),
    ));
    if ($this->isNew()) {
        $this->setDefault('day_begin', 1);
        $this->setDefault('month_begin', 9);
        $this->setDefault('day_end', 30);
        $this->setDefault('month_end', 06);
    }
  }

  public function save($con = null)
  {
    $aValues = $this->processValues($this->getValues());
    if ($this->isNew()) {
        //Enlever boolean encours
        $oSaisonOld = Doctrine::getTable('tbl_saison')->findOneBy('is_outstanding', true);
        if ($oSaisonOld)
        {
            $oSaisonOld->setIsOutstanding(false)->save();
        }
        $nYear = date('Y')+1;
        $yearLicence = date('Y').'/'.$nYear;
        $oSaison = new tbl_saison();
        $oSaison->setDayBegin($aValues['day_begin'])
                ->setMonthBegin($aValues['month_begin'])
                ->setDayEnd($aValues['day_end'])
                ->setMonthEnd($aValues['month_end'])
                ->setYearLicence($yearLicence)
                ->save();
    } else {
        $oSaison = Doctrine::getTable('tbl_saison')->findOneBy('is_outstanding', true);
        $oSaison->setDayBegin($aValues['day_begin'])
                ->setMonthBegin($aValues['month_begin'])
                ->setDayEnd($aValues['day_end'])
                ->setMonthEnd($aValues['month_end'])
                ->save();
    }
    return $oSaison;
  }
}
