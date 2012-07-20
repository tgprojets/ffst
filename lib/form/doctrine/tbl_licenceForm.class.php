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
    unset($this['created_at'], $this['updated_at']);
    if ($this->isNew()) {
        unset($this['num'], $this['id_profil']);
    }
    $this->buildWidget();
    $this->buildValidator();
    $this->defaultsWidget();
  }
  public function save($con = null)
  {
    $aValues = $this->processValues($this->getValues());
    if ($this->isNew()) {
        //Enregistre l'utilisateur
        $oProfil = new tbl_profil();

        //Enregistre l'addresse
        $oAddress = new tbl_address();

        //Enregistre ligue
        $oLicence = new tbl_licence();

        //Calcul num licence
        $oClub = Doctrine::getTable('tbl_club')->find($aValues['id_club']);
        $sNum = $oClub->getNum().'.001.2012/2013';
    } else {
        $oLicence = Doctrine::getTable('tbl_licence')->find($aValues['id']);
        $sNum = $oLicence->getNum();
        $oProfil = $oLicence->getTblProfil();
        $oAddress = $oProfil->getTblAddress();
    }
    if ($this->isValid()) {

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
        $oLicence->setNum($sNum)
                 ->setInternational($aValues['international'])
                 ->setRaceNordique($aValues['race_nordique'])
                 ->setIsFamilly($aValues['is_familly'])
                 ->setCnil($aValues['cnil'])
                 ->setDateMedical($aValues['date_medical'])
                 ->setIdClub($aValues['id_club'])
                 ->setIdProfil($oProfil->getId())
                 ->setIdCategory($aValues['id_category'])
                 ->setIdTypelicence($aValues['id_typelicence'])
                 ->save();
    }
    return $oLicence;
  }
  public function buildWidget()
  {
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
          'culture' => $sCulture,
          'format' => '%day% %month% %year%',
          'years' => array_combine($years, $years)
      ));
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
    $this->validatorSchema['id_address']     = new sfValidatorString(array('required' => false));
    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    // $this->validatorSchema->setPostValidator(new sfValidatorAnd(
    //         array(
    //           new sfValidatorCallback(array('callback'=> array($this, 'checkEmail'))),
    //    ))
    // );

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
    }
  }
}
