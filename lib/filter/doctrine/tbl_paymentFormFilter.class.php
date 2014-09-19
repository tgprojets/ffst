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
        $this->widgetSchema['id_profil']            = new sfWidgetFormChoice(array(
            'label'            => 'Cherche licencié (Nom prénom)',
            'choices'          => array(),
            'renderer_class'   => 'sfWidgetFormDoctrineJQueryAutocompleter',
            'renderer_options' => array('model' => 'tbl_profil', 'url' => sfContext::getInstance()->getController()->genUrl('@ajax_getLicence'), 'config' => "{max: 20}"),
        ));
      $aYearLicence = Doctrine::getTable('tbl_saison')->getSaisonLicence();
      $this->widgetSchema['list_yearlicence']   = new sfWidgetFormChoice(array('choices'  => $aYearLicence));
      $this->validatorSchema['list_yearlicence']        = new sfValidatorChoice(

        array('choices' => array_keys($aYearLicence), 'required' => false)
      );

      $this->setValidator('id_profil', new sfValidatorString(array('required' => false)));

  }
    public function addListYearlicenceColumnQuery(Doctrine_Query $query, $field, $values)
    {
        if (!is_array($values))
        {
          $values = array($values);
        }

        if (!count($values) || $values[0] == 0)
        {
          return;
        }
        $sRootAlias = $query->getRootAlias();
        $oSaison = Doctrine::getTable('tbl_saison')->find($values[0]);
        if ($oSaison) {
          $year = explode("/", $oSaison->getYearLicence());
          $dateDebut = date('Y-m-d', mktime(0, 0, 0, $oSaison->getMonthBegin(), $oSaison->getDayBegin(), $year[0]));
          $dateFin   = date('Y-m-d', mktime(0, 0, 0, $oSaison->getMonthEnd(), $oSaison->getDayEnd(), $year[1]));

          $query->andWhere('created_at >= ?',  $dateDebut);
          $query->andWhere('created_at <= ?',  $dateFin);
        } else {
          var_dump($oSaison);
        }
    }

    public function addIdLigueColumnQuery(Doctrine_Query $query, $field, $values)
    {
        if (!is_array($values))
        {
          $values = array($values);
        }

        if (!count($values) || $values[0] == 0)
        {
          return;
        }

        $sRootAlias = $query->getRootAlias();
        $oLigue = Doctrine::getTable('tbl_ligue')->find($values[0]);
        $aClub = array();
        foreach ($oLigue->getTblClub() as $club)
        {
            $aClub[] = $club->getId();
        }
        if ($oLigue) {

            $query->andWhereIn('id_club', $aClub);
        }
    }
}
