<?php
if ($tbl_club->getLogo() <> '')
{
    $sFileThumbnail = '/uploads/'.sfConfig::get('app_images_logo').DIRECTORY_SEPARATOR.$tbl_club->getLogo();
    echo "<img src='".$sFileThumbnail."' width='40px'/>";
}
?>
