<?php $nId = $form['id']->getValue(); ?>
<?php $oLicence = Doctrine::getTable('tbl_licence')->find($nId) ?>
<?php
if ($oLicence->getTblProfil()->getImage() <> '')
{
    $sFileThumbnail = '/uploads/'.sfConfig::get('app_images_profil').DIRECTORY_SEPARATOR.$oLicence->getTblProfil()->getImage();
    echo "<img src='".$sFileThumbnail."' class='image_profil'/>";
}
?>
