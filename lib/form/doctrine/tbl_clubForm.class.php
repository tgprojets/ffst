<?php

/**
 * tbl_club form.
 *
 * @package    sf_sandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class tbl_clubForm extends Basetbl_clubForm
{
  public function configure()
  {
    unset($this['created_at'], $this['updated_at']);
    $this->buildWidget();
    $this->buildValidator();
    $this->defaultsWidget();
    //Default
  }

  public function save($con = null)
  {
    $aValues = $this->processValues($this->getValues());
    if ($this->isNew()) {
        //Enregistre l'utilisateur
        $oSfGuardUser = new sfGuardUser();

        //Enregistre l'addresse
        $oAddress = new tbl_address();

        //Enregistre ligue
        $oClub = new tbl_club();

    } else {
        $oClub = Doctrine::getTable('tbl_club')->find($aValues['id']);
        $oSfGuardUser = $oClub->getSfGuardUser();
        $oAddress = $oClub->getTblAddress();
    }
    if ($this->isValid()) {
        $oSfGuardUser->setEmailAddress($aValues['email'])
                     ->setUsername($aValues['username'])
                     ->setFirstName($aValues['prenom'])
                     ->setLastName($aValues['nom'])
                     ->save();
        if ($this->isNew()) {
            $oSfGuardUser->setPassword($aValues['password']);
            $oSfGuardUser->save();
            $oGroup = Doctrine::getTable('sfGuardGroup')->findOneBy('name', 'CLUB');
            $oUserGroup = new sfGuardUserGroup();
            $oUserGroup->setUserId($oSfGuardUser->getId())
                       ->setGroupId($oGroup->getId())
                       ->save();
        }
        $oAddress->setAddress1($aValues['address1'])
                 ->setAddress2($aValues['address2'])
                 ->setTel($aValues['tel'])
                 ->setGsm($aValues['gsm'])
                 ->setFax($aValues['fax'])
                 ->save();
        if ($aValues['id_codepostaux'] != '') {

          $oAddress->setIdCodepostaux($aValues['id_codepostaux'])->save();
        }

        $oClub->setName($aValues['name'])
              ->setNum($aValues['num'])
              ->setAffiliation($aValues['affiliation'])
              ->setIdAffectation($aValues['id_affectation'])
              ->setSigle($aValues['sigle'])
              ->setLogo($aValues['logo'])
              ->setIdGen($aValues['id_gen'])
              ->setIdLigue($aValues['id_ligue'])
              ->setOrganisation($aValues['organisation'])
              ->setIdAddress($oAddress->getId())
              ->setIdUser($oSfGuardUser->getId())
              ->save();
    }
    return $oClub;
  }

  public function buildWidget()
  {
      if ($this->isNew()) {
        $this->widgetSchema['password']                     = new sfWidgetFormInputText();
      }
      $this->widgetSchema['email']                     = new sfWidgetFormInputText();
      $this->widgetSchema['username']                  = new sfWidgetFormInputText();
      $this->widgetSchema['nom']                       = new sfWidgetFormInputText();
      $this->widgetSchema['prenom']                    = new sfWidgetFormInputText();
      $this->widgetSchema['address1']                  = new sfWidgetFormInputText();
      $this->widgetSchema['address2']                  = new sfWidgetFormInputText();
      $this->widgetSchema['tel']                       = new sfWidgetFormInputText();
      $this->widgetSchema['gsm']                       = new sfWidgetFormInputText();
      $this->widgetSchema['fax']                       = new sfWidgetFormInputText();
      $this->widgetSchema['id_address']                = new sfWidgetFormInputHidden();
      $this->widgetSchema['id_user']                   = new sfWidgetFormInputHidden();
      $this->widgetSchema['id_affectation']            = new sfWidgetFormDoctrineChoice(
        array(
          'model' => $this->getRelatedModelName('tbl_affectation'),
          'add_empty' => 'Aucune',

      ));
      $this->widgetSchema['id_codepostaux']            = new sfWidgetFormChoice(array(
          'label'            => 'Ville (Code postal)',
          'choices'          => array(),
          'renderer_class'   => 'sfWidgetFormDoctrineJQueryAutocompleter',
          'renderer_options' => array('model' => 'tbl_codepostaux', 'url' => sfContext::getInstance()->getController()->genUrl('@ajax_getCitys')),
      ));
      $sFileThumbnailPicture = "";
      if ($this->getObject()->getLogo()) {
        $sFileThumbnailPicture = '/uploads/'.sfConfig::get('app_images_logo').DIRECTORY_SEPARATOR.$this->getObject()->getLogo();
      }
      $this->widgetSchema['logo'] = new sfWidgetFormInputFileEditable(array(
        'label'        => 'Logo <br /> (300px x 200px)',
        'file_src'     => $sFileThumbnailPicture,
        'is_image'     => true,
        'edit_mode'    => !$this->isNew(),
        'template'     => '<div><img src="'.$sFileThumbnailPicture.'" width="40px"/><br />%input%<br />%delete% %delete_label%</div>',
        'delete_label' => 'Enlever l\'image',
    ));
  }
  public function buildValidator()
  {
    if ($this->isNew()) {
      $this->setValidator('password', new sfValidatorString(
        array('required' => true, 'min_length' => 5, 'max_length' => 20),
        array(
                    'min_length' => 'Le mot de passe est trop court. 5 caractères minimum.',
                    'max_length' => 'Le mot de passe est trop long. 20 caractères maximum',
                    'required' => 'Mot de passe requis',
                    'invalid' => 'Le mot de passe doit etre compris entre 5 et 20 caractères'
      )));
    }
    $this->setValidator('email', new sfValidatorEmail(
        array('required' => true),
        array(
         'required' => 'Email est requis',
         'invalid'  => 'Cet email est incorrect. Saisir un email valide.'
        )));
    $this->setValidator('username', new sfValidatorString(
        array('required' => true),
        array(
         'required' => 'Identifiant est requis',
        )));
    $this->setValidator('nom', new sfValidatorString(
        array('required' => 'true'),
        array(
          'required' => 'Nom est requis'
        )));
    $this->setValidator('prenom', new sfValidatorString(
        array('max_length' => 50),
        array(
          'required' => 'Prénom est requis'
        )));
    $this->setValidator('address1', new sfValidatorString(
        array('max_length' => 250),
        array(
          'required' => 'Adresse est requis'
        )));
    $this->setValidator('id_affectation', new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('tbl_affectation'), 'required' => false)));
    $this->setValidator('address2',       new sfValidatorString(array('max_length' => 250, 'required' => false)));
    $this->setValidator('tel',            new sfValidatorString(array('max_length' => 50, 'required' => false)));
    $this->setValidator('gsm',            new sfValidatorString(array('max_length' => 50, 'required' => false)));
    $this->setValidator('fax',            new sfValidatorString(array('max_length' => 50, 'required' => false)));
    $this->setValidator('id_codepostaux', new sfValidatorString(array('required' => false)));
    //Validator manque vérification username / email
    $this->validatorSchema['id_user']    = new sfValidatorString(array('required' => false));
    $this->validatorSchema['id_address'] = new sfValidatorString(array('required' => false));
    $this->validatorSchema['logo'] = new sfValidatorFile(array(
      'required'   => false,
      'mime_types' => 'web_images',
      'path'       => sfConfig::get('sf_upload_dir').DIRECTORY_SEPARATOR.sfConfig::get('app_images_logo').DIRECTORY_SEPARATOR,
    ));
    $this->validatorSchema['logo_delete'] = new sfValidatorPass();
    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->validatorSchema->setPostValidator(new sfValidatorAnd(
            array(
              new sfValidatorCallback(array('callback'=> array($this, 'checkEmail'))),
              new sfValidatorCallback(array('callback'=> array($this, 'checkUsername'))),
              new sfValidatorDoctrineUnique(array('model' => 'tbl_club', 'column' => array('name'))),
              new sfValidatorDoctrineUnique(array('model' => 'tbl_club', 'column' => array('num'))),
       ))
    );

  }
  public function defaultsWidget()
  {
    if (!$this->isNew()) {
        $oUser = Doctrine::getTable('sfGuardUser')->find($this->getObject()->getIdUser());
        $this->setDefault('email', $oUser->getEmailAddress());
        $this->setDefault('username', $oUser->getUsername());
        $this->setDefault('nom', $oUser->getLastName());
        $this->setDefault('prenom', $oUser->getFirstName());
        $oAddress = Doctrine::getTable('tbl_address')->find($this->getObject()->getIdAddress());
        $this->setDefault('address1', $oAddress->getAddress1());
        $this->setDefault('address2', $oAddress->getAddress2());
        $this->setDefault('tel', $oAddress->getTel());
        $this->setDefault('fax', $oAddress->getFax());
        $this->setDefault('gsm', $oAddress->getGsm());
        $this->setDefault('id_codepostaux', $oAddress->getIdCodepostaux());
    }
  }

    public function checkEmail($validator, $values) {
        if (! empty($values['email'])) {

            if (!$this->isNew()) {
                $nbr = Doctrine_Query::create()
                ->from('sfGuardUser u')
                ->where("u.email_address = ?", $values['email'])
                ->andWhere('u.id <> ?', $values['id_user'])
                ->count();
            } else {
                $nbr = Doctrine_Query::create()
                ->from('sfGuardUser u')
                ->where("u.email_address = ?", $values['email'])
                ->count();
            }

            if ($nbr==0) {
            // Login dispo
               return $values;
            } else {
            // Login pas dispo
               throw new sfValidatorError($validator, 'Cet email existe.');
           }
       }
    }
    public function checkUsername($validator, $values) {
        if (! empty($values['username'])) {

            if (!$this->isNew()) {
                $nbr = Doctrine_Query::create()
                ->from('sfGuardUser u')
                ->where("u.username = ?", $values['username'])
                ->andWhere('u.id <> ?', $values['id_user'])
                ->count();
            } else {
                $nbr = Doctrine_Query::create()
                ->from('sfGuardUser u')
                ->where("u.username = ?", $values['username'])
                ->count();
            }

            if ($nbr==0) {
            // Login dispo
               return $values;
            } else {
            // Login pas dispo
               throw new sfValidatorError($validator, 'Cet identifiant existe.');
            }
       }
    }

}
