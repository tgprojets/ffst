<?php
if ($tbl_licence->getTblProfil()->getImage() <> '')
{
    $sFileThumbnail = '/uploads/'.sfConfig::get('app_images_profil').DIRECTORY_SEPARATOR.$tbl_licence->getTblProfil()->getImage();
    echo "<img src='".$sFileThumbnail."' width='40px'/>";
}
?>
