<?php
/**
 * myGenerique
 *
 * @package ffst
 * @subpackage
 * @author Thomas GILBERT <tgilbert@tgprojets.fr>
 * @version 1.0 GIT
 */
class myGenerique
{
  /**
   * Créer un fichier image ds thumbnail (l'image provient d'upload) en fonction des dimensions
   * @param <string> $psOrigniRep Origine du fichier (Répertoire)
   * @param <string> $psExportFileRep Destination du fichier (Répertoire)
   * @param <string> $psFileName Nom du fichier d'origine
   * @param <string> $psExtension_out Extension du nom du fichier pour le retour
   * @param <integer> $pnDimx Dimension X max en pixel
   * @param <integer> $pnDimy Dimension Y max en pixel
   * @return <string>    Chemin du fichier thumbnail
   */
  public static function generateThumbnailSetNewFilename($psOrigniRep, $psExportFileRep, $psFileName, $psExtension_out, $pnDimx, $pnDimy)
  {
   if ($psFileName) {
       $psFileName = htmlspecialchars_decode($psFileName);
       $ext = explode(".", $psFileName);
       $sExtension = $ext[count($ext)-1];
       $ext = strtolower($sExtension);
       $sFilenamethumbnail = substr($psFileName, 0, -4);
       $sFileName_out = htmlspecialchars_decode($sFilenamethumbnail.$psExtension_out.".".$sExtension);


       $sUploadDir = sfConfig::get('sf_upload_dir');
       if (is_file($sUploadDir.DIRECTORY_SEPARATOR.$psOrigniRep.DIRECTORY_SEPARATOR.$psFileName)) {
           if (strtoupper($sExtension) == 'JPG' || strtoupper($sExtension) == 'PNG' || strtoupper($sExtension) == 'GIF') {

             //$oThumbnail = new sfThumbnail($x, $y);
            $oThumbnail = new sfThumbnail($pnDimx, $pnDimy);
             if (!file_exists($sUploadDir.DIRECTORY_SEPARATOR.$psExportFileRep.DIRECTORY_SEPARATOR.$sFileName_out)) {
                 $oThumbnail->loadFile($sUploadDir.DIRECTORY_SEPARATOR.$psOrigniRep.DIRECTORY_SEPARATOR.$psFileName);
                 if (strtoupper($sExtension) == 'JPG') {
                      $oThumbnail->save($sUploadDir.DIRECTORY_SEPARATOR.$psExportFileRep.DIRECTORY_SEPARATOR.$sFileName_out, 'image/jpeg');
                 } elseif (strtoupper($sExtension) == 'PNG') {
                      $oThumbnail->save($sUploadDir.DIRECTORY_SEPARATOR.$psExportFileRep.DIRECTORY_SEPARATOR.$sFileName_out, 'image/png');
                 } elseif (strtoupper($sExtension) == 'GIF') {
                      $oThumbnail->save($sUploadDir.DIRECTORY_SEPARATOR.$psExportFileRep.DIRECTORY_SEPARATOR.$sFileName_out, 'image/gif');
                 }
             }
             return '/uploads'.DIRECTORY_SEPARATOR.$psExportFileRep.DIRECTORY_SEPARATOR.$sFileName_out;
           }
       } else {
           return $sUploadDir.DIRECTORY_SEPARATOR.$psOrigniRep.DIRECTORY_SEPARATOR.$psFileName;
       }
    }

    return false;
  }
  /**
   * Créer un fichier image ds thumbnail (l'image provient d'upload) en fonction des dimensions
   * @param <string> $psOrigniRep Origine du fichier (Répertoire)
   * @param <string> $psExportFileRep Destination du fichier (Répertoire)
   * @param <string> $psFileName Nom du fichier d'origine
   * @param <string> $psExtension_out Extension du nom du fichier pour le retour
   * @param <integer> $pnDimx Dimension X max en pixel
   * @param <integer> $pnDimy Dimension Y max en pixel
   * @return <string>    Chemin du fichier thumbnail
   */
  public static function generateThumbnailSetNewFilenamePrint($psOrigniRep, $psExportFileRep, $psFileName, $psExtension_out, $pnDimx, $pnDimy)
  {
   if ($psFileName) {
       $psFileName = htmlspecialchars_decode($psFileName);
       $ext = explode(".", $psFileName);
       $sExtension = $ext[count($ext)-1];
       $ext = strtolower($sExtension);
       $sFilenamethumbnail = substr($psFileName, 0, -4);
       $sFileName_out = htmlspecialchars_decode($sFilenamethumbnail.$psExtension_out.".".$sExtension);


       $sUploadDir = sfConfig::get('sf_upload_dir');
       if (is_file($sUploadDir.DIRECTORY_SEPARATOR.$psOrigniRep.DIRECTORY_SEPARATOR.$psFileName)) {
          if (strtoupper($sExtension) == 'JPG' || strtoupper($sExtension) == 'PNG' || strtoupper($sExtension) == 'GIF') {

             //$oThumbnail = new sfThumbnail($x, $y);
            $oThumbnail = new sfThumbnail($pnDimx, $pnDimy);
             if (!file_exists($sUploadDir.DIRECTORY_SEPARATOR.$psExportFileRep.DIRECTORY_SEPARATOR.$sFileName_out)) {
                 $oThumbnail->loadFile($sUploadDir.DIRECTORY_SEPARATOR.$psOrigniRep.DIRECTORY_SEPARATOR.$psFileName);
                 if (strtoupper($sExtension) == 'JPG') {
                      $oThumbnail->save($sUploadDir.DIRECTORY_SEPARATOR.$psExportFileRep.DIRECTORY_SEPARATOR.$sFileName_out, 'image/jpeg');
                 } elseif (strtoupper($sExtension) == 'PNG') {
                      $oThumbnail->save($sUploadDir.DIRECTORY_SEPARATOR.$psExportFileRep.DIRECTORY_SEPARATOR.$sFileName_out, 'image/png');
                 } elseif (strtoupper($sExtension) == 'GIF') {
                      $oThumbnail->save($sUploadDir.DIRECTORY_SEPARATOR.$psExportFileRep.DIRECTORY_SEPARATOR.$sFileName_out, 'image/gif');
                 }
             }
          }
          return $sUploadDir.DIRECTORY_SEPARATOR.$psExportFileRep.DIRECTORY_SEPARATOR.$sFileName_out;
       } else {
          return $sUploadDir.DIRECTORY_SEPARATOR.$psOrigniRep.DIRECTORY_SEPARATOR.$psFileName;
       }

    }

    return false;
  }
  public static function generatePassword() {
    $chaine = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@";
    $nb_caract = 8;
    $pass = "";
    for($u = 1; $u <= $nb_caract; $u++) {
      $nb = strlen($chaine);
      $nb = mt_rand(0,($nb-1));
      $pass.=$chaine[$nb];
    }

    return $pass;
  }
  public static function deleteThumbnail($psFilename, $psFolder) {
     $ext = explode(".", $psFilename);
     if (is_dir($psFolder)) {
       $objects = scandir($psFolder);
       foreach ($objects as $object) {
         if ($object != "." && $object != "..") {
          if (!is_dir($object)) {
            $sName = substr($object, 0, strlen($ext[0]));
            if ($sName == $ext[0]) {
              unlink($psFolder.DIRECTORY_SEPARATOR.$object);
            }
          }
         }
       }
       reset($objects);
     }
  }
}
