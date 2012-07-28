<?php

/**
 * tbl_licence form.
 *
 * @package    sf_sandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class tbl_licenceForm extends Basetbl_licenceForm
{
  public function configure()
  {
    unset($this['num'], $this['created_at'], $this['updated_at'], $this['is_familly']);
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
        $nMember = Doctrine::getTable('tbl_licence')->countLicenceClub($aValues['id_club']);
        $nMember = str_pad($nMember,3,"0",STR_PAD_LEFT);
        $sNum = $oClub->getNum().'.'.$nMember.'.'.$this->getDateLicence();
    } else {
        $oLicence = Doctrine::getTable('tbl_licence')->find($aValues['id']);
        $sNum = $oLicence->getNum();
        $oProfil = $oLicence->getTblProfil();
        $oAddress = $oProfil->getTblAddress();
    }
    if ($this->isValid()) {
      if ($this->isNew() && $aValues['is_checked'] == 0) {
        $oAddress->setAddress1($aValues['address1'])
                 ->setAddress2($aValues['address2'])
                 ->setTel($aValues['tel'])
                 ->setGsm($aValues['gsm'])
                 ->setFax($aValues['fax'])
                 ->save();
        if ($aValues['id_codepostaux'] != '') {
          $oAddress->setIdCodepostaux($aValues['id_codepostaux'])->save();
        }
        $oProfil->setEmail($aValues['email'])
                ->setFirstName($aValues['last_name'])
                ->setLastName($aValues['first_name'])
                ->setBirthday($aValues['birthday'])
                ->setIdAddress($oAddress->getId())
                ->save();
      }
      $oLicence->setNum($sNum)
               ->setInternational($aValues['international'])
               ->setRaceNordique($aValues['race_nordique'])
               ->setIsFamilly($aValues['id_familly']!=""?true:false)
               ->setIdFamilly($aValues['id_familly'])
               ->setCnil($aValues['cnil'])
               ->setDateMedical($aValues['date_medical'])
               ->setIdClub($aValues['id_club'])
               ->setIdProfil($oProfil->getId())
               ->setIdCategory($aValues['id_category'])
               ->setIdTypelicence($aValues['id_typelicence'])
               ->save();
      if ($this->isNew()) {
        $oLicence->setIsBrouillon(true)
                 ->setIsNew($bIsNew)
                 ->save();
        $oCalcul = new CalculLicence($oLicence->getId());
        $oCalcul->calcCotisationLicence();
        $oCalcul->calcLicence();
      } else {

      }

    }
    return $oLicence;
  }
  public function buildWidget()
  {
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
      $sNow18 = date('Y', strtotime('-10 years'));
      $years = range($sNow18, 1910);
      $this->widgetSchema['email']                     = new sfWidgetFormInputText();
      $this->widgetSchema['last_name']                 = new sfWidgetFormInputText();
      $this->widgetSchema['first_name']                = new sfWidgetFormInputText();
      $this->widgetSchema['address1']                  = new sfWidgetFormInputText();
      $this->widgetSchema['address2']                  = new sfWidgetFormInputText();
      $this->widgetSchema['tel']                       = new sfWidgetFormInputText();
      $this->widgetSchema['gsm']                       = new sfWidgetFormInputText();
      $this->widgetSchema['fax']                       = new sfWidgetFormInputText();
      $this->widgetSchema['id_address']                = new sfWidgetFormInputHidden();
      $this->widgetSchema['id_codepostaux']            = new sfWidgetFormChoice(array(
          'label'            => 'Ville (Code postal)',
          'choices'          => array(),
          'renderer_class'   => 'sfWidgetFormDoctrineJQueryAutocompleter',
          'renderer_options' => array('model' => 'tbl_codepostaux', 'url' => sfContext::getInstance()->getController()->genUrl('@ajax_getCitys')),
      ));
      $this->widgetSchema['birthday']                  = new sfWidgetFormI18nDate(array(
          'culture' => 'fr',
          'format' => '%day% %month% %year%',
          'years' => array_combine($years, $years)
      ));
      $this->widgetSchema['date_medical']              = new sfWidgetFormI18nDate(array(
          'culture' => 'fr',
          'format' => '%day% %month% %year%',
      ));
      if ($this->isNew()) {
        $this->widgetSchema['id_profil']            = new sfWidgetFormChoice(array(
            'label'            => 'Cherche licencié (Nom prénom)',
            'choices'          => array(),
            'renderer_class'   => 'sfWidgetFormDoctrineJQueryAutocompleter',
            'renderer_options' => array('model' => 'tbl_profil', 'url' => sfContext::getInstance()->getController()->genUrl('@ajax_getLicence')),
        ));
        $this->widgetSchema['is_checked']           = new sfWidgetFormInputHidden();
      }
  }

  public function buildValidator()
  {
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
    $this->setValidator('address1', new sfValidatorString(
        array('max_length' => 250),
        array(
          'required' => 'Adresse est requis'
        )));
    $this->setValidator('address2',       new sfValidatorString(array('max_length' => 250, 'required' => false)));
    $this->setValidator('tel',            new sfValidatorString(array('max_length' => 50, 'required' => false)));
    $this->setValidator('gsm',            new sfValidatorString(array('max_length' => 50, 'required' => false)));
    $this->setValidator('fax',            new sfValidatorString(array('max_length' => 50, 'required' => false)));
    $this->setValidator('id_codepostaux', new sfValidatorString(array('required' => false)));
    $this->setValidator('birthday',       new sfValidatorDate(array('required' => true)));
    $this->setValidator('date_medical',   new sfValidatorDate(array('required' => false)));
    $this->validatorSchema['id_address']     = new sfValidatorString(array('required' => false));
    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    if ($this->isNew()) {
      $this->setValidator('id_profil', new sfValidatorString(array('required' => false)));
      $this->setValidator('is_checked', new sfValidatorString(array('required' => false)));
    }
    $this->validatorSchema->setPostValidator(new sfValidatorAnd(
            array(
              new sfValidatorCallback(array('callback'=> array($this, 'checkEmail'))),
              new sfValidatorCallback(array('callback'=> array($this, 'checkNameBirthday'))),
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
        $this->setDefault('id_codepostaux', $oAddress->getIdCodepostaux());
    } else {
      $this->setDefault('is_checked', '0');
      if (sfContext::getInstance()->getUser()->isClub()) {
        $oClub = sfContext::getInstance()->getUser()->getClub();
        $this->setDefault('id_club', $oClub->getId());
      }
    }
  }
  public function getDateLicence()
  {
    if (date('d') >= 1 && date('m') >= 7) {
      $startDate = date('Y');
      $endDate   = date('Y')+1;
    } else {
      $startDate = date('Y')-1;
      $endDate   = date('Y');
    }
    $sDate = (string) $startDate.'/'.(string) $endDate;

    return $sDate;
  }

  public function checkEmail($validator, $values)
  {
    if (! empty($values['email'])) {
      if ($this->isNew() && $values['is_checked'] == 0) {
        $nbr = Doctrine_Query::create()
          ->from('tbl_profil p')
          ->where("p.email = ?", $values['email'])
          ->count();

        if ($nbr==0) {
          // Login dispo
          return $values;
        } else {
          // Login pas dispo
          throw new sfValidatorError($validator, 'Cet addresse mail existe déjà.');
        }
      } else {
        $nbr = Doctrine_Query::create()
          ->from('tbl_profil p')
          ->where("p.email = ?", $values['email'])
          ->andWhere("p.id <> ?", $values['id_profil'])
          ->count();

        if ($nbr==0) {
          // Login dispo
          return $values;
        } else {
          // Login pas dispo
          throw new sfValidatorError($validator, 'Cet addresse mail existe déjà.');
        }
      }
    }
  }
  public function checkNameBirthday($validator, $values)
  {
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
}
