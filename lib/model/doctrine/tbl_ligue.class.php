<?php

/**
 * tbl_ligue
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 *
 * @package    sf_sandbox
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class tbl_ligue extends Basetbl_ligue
{
  public function generateLogoFilename(sfValidatedFile $file, $objectSlug = null)
  {
    $sFiles = $this->getLogo();
    //Delete thumbnail
    if (!empty($sFiles)) {
      myGenerique::deleteThumbnail($sFiles, sfConfig::get("sf_upload_dir").DIRECTORY_SEPARATOR.sfConfig::get('app_images_logo'));
    }
    return $file->generateFilename();
  }

  public function postDelete($event)
  {
    $sFiles = $this->getLogo();
    //Delete files
    if (file_exists(sfConfig::get("sf_upload_dir").DIRECTORY_SEPARATOR.sfConfig::get('app_images_logo').DIRECTORY_SEPARATOR.$sFiles)) {
      @unlink(sfConfig::get("sf_upload_dir").DIRECTORY_SEPARATOR.sfConfig::get('app_images_logo').DIRECTORY_SEPARATOR.$sFiles);
    }
    //Delete thumbnail
    myGenerique::deleteThumbnail($sFiles, sfConfig::get("sf_upload_dir").DIRECTORY_SEPARATOR.sfConfig::get('app_images_thumbnail'));
  }
  public function __toString()
  {
    if ($this->getName()) {
      return $this->getName().' '.$this->getNum().'/'.$this->getAffiliation().'/'.$this->getTblAffectation()->getCode();
    } else {
      return '';
    }
  }
}
