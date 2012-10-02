<?php

/**
 * tbl_licence form.
 *
 * @package    sf_sandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class tbl_licenceoldForm extends Basetbl_licenceForm
{

  public function configure()
  {
    unset($this['num'], $this['created_at'], $this['updated_at'], $this['is_familly'], $this['id_familly']);
    $this->buildWidget();
    $this->buildValidator();
    $this->defaultsWidget();
  }
  public function save($con = null)
  {
    $aValues = $this->processValues($this->getValues());
    if ($this->isNew()) {
        //Enregistre l'utilisateur
        if ($aValues['id_profil'] != "" && $aValues['is_checked'] == 1) {
          $oProfil = Doctrine::getTable('tbl_profil')->find($aValues['id_profil']);
          $bIsNew = false;
        } else {
          $oProfil = new tbl_profil();
          $bIsNew = true;
        }

        //Enregistre l'addresse
        $oAddress = new tbl_address();

        //Enregistre ligue
        $oLicence = new tbl_licence();

        //Calcul num licence
        $oClub = Doctrine::getTable('tbl_club')->find($aValues['id_club']);
        $nMember = Doctrine::getTable('tbl_licence')->countLicenceClub($aValues['id_club'], $aValues['year_licence']);
        $nMember = str_pad($nMember,3,"0",STR_PAD_LEFT);
        $sNum = $oClub->getNum().'.'.$nMember.'.'.$aValues['year_licence'];
    } else {
        $oLicence = Doctrine::getTable('tbl_licence')->find($aValues['id']);
        $sNum = $oLicence->getNum();
        $oProfil = $oLicence->getTblProfil();
        $oAddress = $oProfil->getTblAddress();
    }
    if ($this->isValid()) {
      if ($this->isNew()) {
        $oAddress->setAddress1($aValues['address1'])
                 ->setAddress2($aValues['address2'])
                 ->setTel($aValues['tel'])
                 ->setGsm($aValues['gsm'])
                 ->setFax($aValues['fax'])
                 ->save();
        if ($aValues['is_foreign'] == 0) {
          $oAddress->setIdCodepostaux($aValues['id_codepostaux'])
                   ->setCountry('')
                   ->setCpForeign('')
                   ->setCityForeign('')
                   ->save();
        } else {
          $oAddress->setIdCodepostaux(null)
                   ->setCountry($aValues['country'])
                   ->setCpForeign($aValues['cp_foreign'])
                   ->setCityForeign($aValues['city_foreign'])
                   ->save();
        }
        $oProfil->setEmail($aValues['email'])
                ->setFirstName($aValues['first_name'])
                ->setLastName($aValues['last_name'])
                ->setSexe($aValues['sexe'])
                ->setBirthday($aValues['birthday'])
                ->setIdAddress($oAddress->getId())
                ->save();
      }
      $oLicence->setNum($sNum)
               ->setInternational($aValues['international'])
               ->setRaceNordique($aValues['race_nordique'])
               ->setIsBrouillon(false)
               ->setCnil($aValues['cnil'])
               ->setDateMedical($aValues['date_medical'])
               ->setIdClub($aValues['id_club'])
               ->setIdProfil($oProfil->getId())
               ->setIdCategory($aValues['id_category'])
               ->setIdTypelicence($aValues['id_typelicence'])
               ->setDateValidation(date('Y-m-d'))
               ->save();
      if ($this->isNew()) {
        $oLicence->setIsNew($bIsNew)
                 ->setYearLicence($aValues['year_licence'])
                 ->save();
      } else {
        $oAddress->setAddress1($aValues['address1'])
                 ->setAddress2($aValues['address2'])
                 ->setTel($aValues['tel'])
                 ->setGsm($aValues['gsm'])
                 ->setFax($aValues['fax'])
                 ->save();
        if ($aValues['is_foreign'] == 0) {
          $oAddress->setIdCodepostaux($aValues['id_codepostaux'])
                   ->setCountry('')
                   ->setCpForeign('')
                   ->setCityForeign('')
                   ->save();
        } else {
          $oAddress->setIdCodepostaux(null)
                   ->setCountry($aValues['country'])
                   ->setCpForeign($aValues['cp_foreign'])
                   ->setCityForeign($aValues['city_foreign'])
                   ->save();
        }
          $oProfil->setEmail($aValues['email'])
                  ->setSexe($aValues['sexe'])
                  ->setFirstName($aValues['first_name'])
                  ->setLastName($aValues['last_name'])
                  ->setBirthday($aValues['birthday'])
                  ->save();

      }

    }
    return $oLicence;
  }
  public function buildWidget()
  {
      $sNow18 = date('Y', strtotime('-6 years'));
      $sNow1 = date('Y', strtotime('now'));
      $years = range($sNow18, 1910);
      $yearsLicence = range($sNow1, 2005);
      $aSexe = array('H' => 'Homme', 'F' => 'Femme');

      $this->widgetSchema['sexe']                      = new sfWidgetFormChoice(array('choices'  => $aSexe, 'multiple' => false, 'expanded' => true));
      $this->widgetSchema['email']                     = new sfWidgetFormInputText();

      $this->widgetSchema['address1']                  = new sfWidgetFormInputText();
      $this->widgetSchema['address2']                  = new sfWidgetFormInputText();
      $this->widgetSchema['tel']                       = new sfWidgetFormInputText();
      $this->widgetSchema['gsm']                       = new sfWidgetFormInputText();
      $this->widgetSchema['fax']                       = new sfWidgetFormInputText();
      $this->widgetSchema['id_address']                = new sfWidgetFormInputHidden();
      $this->widgetSchema['id_typelicence']            = new sfWidgetFormDoctrineChoice(
        array(
          'model'        => $this->getRelatedModelName('tbl_typelicence'),
          'table_method' => 'retrieveByRank',
          'add_empty'    => false
        ));
      $this->widgetSchema['id_category']               = new sfWidgetFormDoctrineChoice(
        array(
          'model' => $this->getRelatedModelName('tbl_category'),
          'add_empty' => 'Aucune',
      ));
      $this->widgetSchema['id_codepostaux']            = new sfWidgetFormChoice(array(
          'label'            => 'Ville (Code postal)',
          'choices'          => array(),
          'renderer_class'   => 'sfWidgetFormDoctrineJQueryAutocompleter',
          'renderer_options' => array('model' => 'tbl_codepostaux', 'url' => sfContext::getInstance()->getController()->genUrl('@ajax_getCitys'), 'config' => "{max: 20}"),

      ));
      $this->widgetSchema['last_name']                 = new sfWidgetFormInputText();
      $this->widgetSchema['first_name']                = new sfWidgetFormInputText();
      $this->widgetSchema['birthday']                  = new sfWidgetFormI18nDate(array(
          'culture' => 'fr',
          'format' => '%day% %month% %year%',
          'years' => array_combine($years, $years)
      ));
      $this->widgetSchema['is_medical']                = new sfWidgetFormInputCheckbox();
      $this->widgetSchema['date_medical']              = new sfWidgetFormI18nDate(array(
          'culture' => 'fr',
          'format' => '%day% %month% %year%',
      ));
      $this->widgetSchema['country']               = new sfWidgetFormI18nChoiceCountry(array('culture' => 'fr'));
      $this->widgetSchema['is_foreign']            = new sfWidgetFormInputCheckbox();
      $this->widgetSchema['city_foreign']          = new sfWidgetFormInputText();
      $this->widgetSchema['cp_foreign']            = new sfWidgetFormInputText();
      $this->widgetSchema['date_validation']           = new sfWidgetFormI18nDate(array(
          'culture' => 'fr',
          'format' => '%day% %month% %year%',
          'years' => array_combine($yearsLicence, $yearsLicence)
      ));
      if ($this->isNew()) {
        $this->widgetSchema['year_licence']         = new sfWidgetFormInputText();
        $this->widgetSchema['id_profil']            = new sfWidgetFormChoice(array(
            'label'            => 'Cherche licencié (Nom prénom)',
            'choices'          => array(),
            'renderer_class'   => 'sfWidgetFormDoctrineJQueryAutocompleter',
            'renderer_options' => array('model' => 'tbl_profil', 'url' => sfContext::getInstance()->getController()->genUrl('@ajax_getLicence'), 'config' => "{max: 20}"),
        ));
        $this->widgetSchema['is_checked']           = new sfWidgetFormInputHidden();
      } else {
        $this->widgetSchema['id_profil']           = new sfWidgetFormInputHidden();
      }
  }

  public function buildValidator()
  {
    $aSexe = array('H' => 'Homme', 'F' => 'Femme');

    $this->validatorSchema['sexe']        = new sfValidatorChoice(
      array('choices' => array_keys($aSexe), 'required' => false)
    );
    $this->setValidator('email', new sfValidatorEmail(
        array('required' => true),
        array(
         'required' => 'Email est requis',
         'invalid'  => 'Cet email est incorrect. Saisir un email valide.'
        )));

    $this->setValidator('last_name', new sfValidatorString(
        array('required' => 'true'),
        array(
          'required' => 'Nom est requis'
        )));
    $this->setValidator('first_name', new sfValidatorString(
        array('max_length' => 50),
        array(
          'required' => 'Prénom est requis'
        )));
    $this->setValidator('birthday',       new sfValidatorDate(array('required' => true)));

    $this->setValidator('address1', new sfValidatorString(
        array('max_length' => 250),
        array(
          'required' => 'Adresse est requise'
        )));
    $this->setValidator('address2',       new sfValidatorString(array('max_length' => 250, 'required' => false)));
    $this->setValidator('tel',            new sfValidatorString(array('max_length' => 50, 'required' => false)));
    $this->setValidator('gsm',            new sfValidatorString(array('max_length' => 50, 'required' => false)));
    $this->setValidator('fax',            new sfValidatorString(array('max_length' => 50, 'required' => false)));
    $this->setValidator('id_codepostaux', new sfValidatorString(array('required' => false)));
    $this->setValidator('is_medical',     new sfValidatorBoolean(array('required' => false)));
    $this->setValidator('date_medical',   new sfValidatorDate(array('required' => false)));
    $this->setValidator('id_typelicence', new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('tbl_typelicence'))));
    $this->validatorSchema['id_address']     = new sfValidatorString(array('required' => false));
    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    $this->setValidator('id_category', new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('tbl_category'), 'required' => false)));
    $this->setValidator('is_foreign',   new sfValidatorBoolean(array('required' => false)));
    $this->setValidator('country',      new sfValidatorString(array('required' => false)));
    $this->setValidator('city_foreign', new sfValidatorString(array('required' => false)));
    $this->setValidator('cp_foreign',   new sfValidatorString(array('required' => false)));

    $this->setValidator('date_validation',   new sfValidatorDate(array('required' => false)));
    if ($this->isNew()) {
      $this->setValidator('year_licence',  new sfValidatorString(array('min_length' => 9, 'max_length' => 9, 'required' => true)));
      $this->setValidator('id_profil',     new sfValidatorString(array('required' => false)));
      $this->setValidator('is_checked',    new sfValidatorString(array('required' => false)));
    }
    $this->validatorSchema->setPostValidator(new sfValidatorAnd(
            array(
              new sfValidatorCallback(array('callback'=> array($this, 'checkNameBirthday'))),
              new sfValidatorCallback(array('callback'=> array($this, 'checkYearLicence'))),
              new sfValidatorCallback(array('callback'=> array($this, 'checkCategory'))),
              new sfValidatorCallback(array('callback'=> array($this, 'checkSaisieLicence'))),
              new sfValidatorCallback(array('callback'=> array($this, 'checkCountry'))),
       ))
    );

  }
  public function defaultsWidget()
  {
    if (!$this->isNew()) {
        $oUser = Doctrine::getTable('tbl_profil')->find($this->getObject()->getIdProfil());
        $this->setDefault('email', $oUser->getEmail());
        $this->setDefault('last_name', $oUser->getLastName());
        $this->setDefault('first_name', $oUser->getFirstName());
        $this->setDefault('birthday', $oUser->getBirthday());
        $oAddress = Doctrine::getTable('tbl_address')->find($oUser->getIdAddress());
        $this->setDefault('address1', $oAddress->getAddress1());
        $this->setDefault('address2', $oAddress->getAddress2());
        $this->setDefault('tel', $oAddress->getTel());
        $this->setDefault('fax', $oAddress->getFax());
        $this->setDefault('gsm', $oAddress->getGsm());
        $this->setDefault('id_profil', $oUser->getId());
        $this->setDefault('sexe', $oUser->getSexe());
        if ($this->getObject()->getDateMedical())
        {
          $this->setDefault('is_medical', true);
        }
        if ($this->getObject()->getIdFamilly())
        {
          $this->setDefault('is_familly', true);
        }
        if ($oAddress->getIdCodepostaux() != null) {
          $this->setDefault('country', 'FR');
          $this->setDefault('id_codepostaux', $oAddress->getIdCodepostaux());
        } else {
          $this->setDefault('country', $oAddress->getCountry());
          $this->setDefault('cp_foreign', $oAddress->getCpForeign());
          $this->setDefault('city_foreign', $oAddress->getCityForeign());
          $this->setDefault('is_foreign', true);
        }
    } else {
      $this->setDefault('country', 'FR');
      $this->setDefault('sexe', 'H');
      $this->setDefault('is_checked', '0');
    }
  }

  public function checkYearLicence($validator, $values)
  {
    if ($this->isNew() && !empty($values['id_profil']) && !empty($values['year_licence']))
    {

      $nbr = Doctrine_Query::create()
          ->from('tbl_licence l')
          ->where("l.id_profil = ?", $values['id_profil'])
          ->andWhere("l.year_licence = ?", $values['year_licence'])
          ->andWhere("l.is_cancel = ?", false)
          ->count();
      if ($nbr>0) {
        die();
        throw new sfValidatorError($validator, 'Ce licencié a déjà une licence.'.$values['year_licence']);
      }

    }
    return $values;
  }

  public function checkSaisieLicence($validator, $values)
  {
    if (!$this->isNew() && $this->getObject()->getDateValidation() != null)
    {
      $oTypeLicence     = Doctrine::getTable('tbl_typelicence')->find($values['id_typelicence']);
      $oLicence         = Doctrine::getTable('tbl_licence')->find($values['id']);

      if ($oLicence->getTblTypelicence()->getRank() > $oTypeLicence->getRank()) {
        throw new sfValidatorError($validator, 'La licence doit être supérieure à l\'ancienne.');
      }

    }

    //Tarif junior
    $bornAt = new DateTime($values['birthday']);
    $age = $bornAt->diff(new \DateTime(Licence::getStartYearLicence().'-12-31' ))->y;
    //Choix de la licence
    $nbr = Doctrine_Query::create()
        ->from('tbl_typelicence tl')
        ->where("tl.id = ?", $values['id_typelicence'])
        ->andWhere("tl.is_minor = ?", true)
        ->count();
        if ($nbr>0) {
          if ($age >= 18) {
            throw new sfValidatorError($validator, 'Ce licencié n\'a pas le droit à cette licence, il est majeur (Voir date de naissance) .');
          }
        }

    return $values;
  }

  public function checkNameBirthday($validator, $values)
  {
    if (!$this->isNew() && $this->getObject()->getDateValidation() != null && $this->bClub)
    {
      return $values;
    }
    if (! empty($values['last_name']) && ! empty($values['first_name']) && ! empty($values['birthday'])) {
      if ($this->isNew() && $values['is_checked'] == 0) {
        $nbr = Doctrine_Query::create()
          ->from('tbl_profil p')
          ->where('upper(p.first_name) LIKE ?', mb_strtoupper($values['first_name']).'%')
          ->andWhere('upper(p.last_name) LIKE ?', mb_strtoupper($values['last_name']).'%')
          ->andWhere('p.birthday = ?', $values['birthday'])
          ->count();

        if ($nbr==0) {
          // Login dispo
          return $values;
        } else {
          // Login pas dispo
          throw new sfValidatorError($validator, 'Ce licencié existe déjà.');
        }
      } else {
        $nbr = Doctrine_Query::create()
          ->from('tbl_profil p')
          ->where('upper(p.first_name) LIKE ?', mb_strtoupper($values['first_name']).'%')
          ->andWhere('upper(p.last_name) LIKE ?', mb_strtoupper($values['last_name']).'%')
          ->andWhere("p.id <> ?", $values['id_profil'])
          ->andWhere('p.birthday = ?', $values['birthday'])
          ->count();

        if ($nbr==0) {
          // Login dispo
          return $values;
        } else {
          // Login pas dispo
          throw new sfValidatorError($validator, 'Ce licencié existe déjà.');
        }
      }
    }
  }

  public function checkCategory($validator, $values)
  {
    if (! empty($values['id_typelicence']))
    {
      $oTypeLicence = Doctrine::getTable('tbl_typelicence')->find($values['id_typelicence']);
      $sCode = $oTypeLicence->getTblGrouplicence()->getCode();
      switch ($sCode) {
        case "COM":
          if (empty($values['id_category'])) {
            throw new sfValidatorError($validator, 'Veuillez choisir une catégorie pour ce type de licence.');
          }
        break;
        case "DIG":
          if (!empty($values['id_category'])) {
            throw new sfValidatorError($validator, 'Veuillez choisir aucune catégorie pour ce type de licence.');
          }
        break;
        case "PRO":
          if (!empty($values['id_category'])) {
            throw new sfValidatorError($validator, 'Veuillez choisir aucune catégorie pour ce type de licence.');
          }
        break;
        case "SPL":
          if (!empty($values['id_category'])) {
            throw new sfValidatorError($validator, 'Veuillez choisir aucune catégorie pour ce type de licence.');
          }
        break;
      }
    }
    return $values;
  }
  public function checkCountry($validator, $values)
  {
    if ($values['is_foreign'] == 0)
    {
      if (empty($values['id_codepostaux'])) {
        throw new sfValidatorError($validator, 'Veuillez saisir une adresse');
      }
    } else {
      if (empty($values['country']) || empty($values['cp_foreign']) || empty($values['city_foreign'])) {
        throw new sfValidatorError($validator, 'Veuillez saisir un code postal et une ville pour le pays étrangers.');
      }
    }
    return $values;
  }
}
