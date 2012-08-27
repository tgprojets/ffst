<?php

class InitParam {

    $sFichier = sfConfig::get('sf_upload_dir').'param.ini';

    public function getValueByKey($sGroup, $sKey)
    {

        if (!file_exists($this->sFichier)) {
            return null;
        }
        $valeur=false;
        if ($fichier_lecture=file($this->sFichier))
        {
            foreach($fichier_lecture as $ligne)
            {
                $ligne_propre=trim($ligne);
                if(preg_match("#^\[(.+)\]$#",$ligne_propre,$matches))
                {
                    $groupe=$matches[1];
                } elseif($ligne_propre[0]!=';') {
                    if($groupe==$sGroup)
                    {
                        if(strpos($ligne,$sKey."=")===0)
                        {
                            $valeur=end(explode("=",$ligne,2));
                        } elseif($ligne==$sKey) {
                            $valeur='';
                        }

                    }

                }
           }
        }

        if ($valeur===false) {
            return null;
        } else {
            return $valeur;
        }
        return null;
    }

}