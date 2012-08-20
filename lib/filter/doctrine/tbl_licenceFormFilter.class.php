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
}
