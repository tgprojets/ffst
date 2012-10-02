<?php

/**
 * tbl_licence filter form.
 *
 * @package    sf_sandbox
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class tbl_licenceFormFilter extends Basetbl_licenceFormFilter
{
  public function configure()
  {
      $this->widgetSchema['is_valid']      = new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no')));
      if (sfContext::getInstance()->getUser()->isClub()) {
        $this->widgetSchema['id_club']                = new sfWidgetFormInputHidden();

      }
      if (sfContext::getInstance()->getUser()->isLigue()) {
        $this->widgetSchema['id_club']                = new sfWidgetFormDoctrineChoice(
        array(
          'model'        => $this->getRelatedModelName('tbl_club'),
          'add_empty'    => false,
          'table_method' => 'getClubLigue'
        ));
      }
      $aYearLicence = Doctrine::getTable('tbl_licence')->findListYearLicence();
      $this->widgetSchema['list_yearlicence']   = new sfWidgetFormChoice(array('choices'  => $aYearLicence));
      $this->validatorSchema['list_yearlicence']        = new sfValidatorChoice(

        array('choices' => array_keys($aYearLicence), 'required' => false)
      );
      $this->widgetSchema['last_name']               = new sfWidgetFormFilterInput(array('with_empty' => false));
      $this->validatorSchema['last_name']            = new sfValidatorPass(array('required' => false));
      $this->widgetSchema['first_name']               = new sfWidgetFormFilterInput(array('with_empty' => false));
      $this->validatorSchema['first_name']            = new sfValidatorPass(array('required' => false));
      $this->validatorSchema['is_valid']    = new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0)));
  }
  public function addIsValidColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }
    $sRootAlias = $query->getRootAlias();
    if ($values[0] == 1) {
        $query->andWhere('date_validation IS NOT NULL');
    } else {
        $query->andWhere('date_validation IS NULL');
    }
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
    $query->andWhere('year_licence = ?',  $values[0]);
  }

  public function addLastNameColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $sNom = htmlspecialchars(strtoupper($values['text']), ENT_QUOTES);

    $sValue =
    $sRootAlias = $query->getRootAlias();
    $query->leftJoin($sRootAlias.'.tbl_profil p');
    $query->andWhere('UPPER(p.last_name) like \''.$sNom.'%\'');

  }
  public function addFirstNameColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $sNom = htmlspecialchars(strtoupper($values['text']), ENT_QUOTES);

    $sValue =
    $sRootAlias = $query->getRootAlias();
    $query->leftJoin($sRootAlias.'.tbl_profil p1');
    $query->andWhere('UPPER(p1.first_name) like \''.$sNom.'%\'');

  }
}
