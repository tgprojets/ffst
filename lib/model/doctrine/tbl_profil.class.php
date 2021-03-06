<?php

/**
 * tbl_profil
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 *
 * @package    sf_sandbox
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class tbl_profil extends Basetbl_profil
{
    public function __toString()
    {

        return $this->getFirstName().' '.$this->getLastName();
    }
    public function getName()
    {
        return $this->getFirstName().' '.$this->getLastName();
    }
    public function getLastLicence()
    {
      $oLicences = $this->getTblLicence();
      if (Licence::getDateLicence() != null)
      {
        foreach($oLicences as $oLicence)
        {
          if ($oLicence->getYearLicence() == Licence::getDateLicence()) {
            return $oLicence->getTblTypelicence();
          }
        }
      }
      return '';
    }
    public function getLastClub()
    {
      $oLicences = $this->getTblLicence();
      $maxLicence = 0;
      $club = null;
      foreach($oLicences as $oLicence)
      {
        if ($oLicence->getYearLicence() > $maxLicence) {
          $maxLicence = $oLicence->getYearLicence();
          $club = $oLicence->getTblClub();
        }
      }
      return $club;
    }
    public function getLastLicenceValide()
    {
      $oLicences = $this->getTblLicence();
      if (Licence::getDateLicence() != null)
      {
        foreach($oLicences as $oLicence)
        {
          if ($oLicence->getYearLicence() == Licence::getDateLicence()) {
            if ($oLicence->getDateValidation()) {
                return true;
            } else {
                return false;
            }
          }
        }
      }
      return false;
    }

  public function generateImageFilename(sfValidatedFile $file, $objectSlug = null)
  {
    $sFiles = $this->getImage();
    //Delete thumbnail
    if (!empty($sFiles)) {
      myGenerique::deleteThumbnail($sFiles, sfConfig::get("sf_upload_dir").DIRECTORY_SEPARATOR.sfConfig::get('app_images_profil'));
    }
    return $file->generateFilename();
  }

  public function postDelete($event)
  {
    $sFiles = $this->getImage();
    //Delete files
    if (file_exists(sfConfig::get("sf_upload_dir").DIRECTORY_SEPARATOR.sfConfig::get('app_images_profil').DIRECTORY_SEPARATOR.$sFiles)) {
      @unlink(sfConfig::get("sf_upload_dir").DIRECTORY_SEPARATOR.sfConfig::get('app_images_profil').DIRECTORY_SEPARATOR.$sFiles);
    }
    //Delete thumbnail
    myGenerique::deleteThumbnail($sFiles, sfConfig::get("sf_upload_dir").DIRECTORY_SEPARATOR.sfConfig::get('app_images_thumbnail'));
  }

  public function getPhotoUrl()
  {
    if ($this->getImage() == '')
    {
      return '/images/default_photo.jpg';
    } else {
      return '/uploads'.DIRECTORY_SEPARATOR.sfConfig::get('app_images_profil').DIRECTORY_SEPARATOR.$this->getImage();
    }
  }
}
