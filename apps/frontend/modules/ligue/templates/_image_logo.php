<?php
if ($tbl_ligue->getLogo() <> '')
{
    $sFileThumbnail = '/uploads/'.sfConfig::get('app_images_logo').DIRECTORY_SEPARATOR.$tbl_ligue->getLogo();
    echo "<img src='".$sFileThumbnail."' width='40px'/>";
}
?>
