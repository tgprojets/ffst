<?php

/**
 * tbl_prixlicence form.
 *
 * @package    sf_sandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class tbl_prixlicenceForm extends Basetbl_prixlicenceForm
{
    public function configure()
    {
        if ($this->isNew()) {
            $this->widgetSchema['id_federation']                = new sfWidgetFormDoctrineChoice(
              array(
                'model'        => $this->getRelatedModelName('tbl_federation'),
                'add_empty'    => false,
            ));

            $this->widgetSchema['id_typelicence']                = new sfWidgetFormDoctrineChoice(
              array(
                'model'        => $this->getRelatedModelName('tbl_typelicence'),
                'add_empty'    => false,
            ));
            $this->validatorSchema->setPostValidator(new sfValidatorAnd(
                    array(
                      new sfValidatorCallback(array('callback'=> array($this, 'relationExist'))),
               ))
            );
        }
    }

    public function relationExist($validator, $values)
    {
        $nbr = Doctrine_Query::create()
          ->from('tbl_prixlicence pl')
          ->where('pl.id_federation = ?', $values['id_federation'])
          ->andWhere('pl.id_typelicence = ?', $values['id_typelicence'])
          ->count();

        if ($nbr==0) {
          // Login dispo
          return $values;
        } else {
          // Login pas dispo
          throw new sfValidatorError($validator, 'Cette relation existe déjà.');
        }
    }
}
