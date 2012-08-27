<?php

class InitParam {

    private $sFichier;

    public function __construct()
    {
        $this->sFichier  = sfConfig::get('sf_upload_dir').DIRECTORY_SEPARATOR.'param.ini';
    }

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
                if ($ligne_propre != '') {

                    if(preg_match("#^\[(.+)\]$#",$ligne_propre,$matches))
                    {
                        $groupe=$matches[1];

                    } elseif($ligne_propre[0]!=';') {
                        if($groupe==$sGroup)
                        {
                            if(strpos($ligne,$sKey."=")===0)
                            {
                                $aValeur=explode("=",$ligne,2);
                                $valeur = $aValeur[1];
                            } elseif($ligne==$sKey) {
                                $valeur='';
                            }

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

    public function addValue($sGroup, $sKey, $sValue)
    {
        $aValues = $this->getArray();
        if ($aValues == null || empty($aValues)) {
            $aValues = array();
        }
        $aValues[$sGroup][$sKey]=$sValue;
        $this->saveArray($aValues);
    }

    private function saveArray($aValues)
    {
        if (file_exists($this->sFichier)) unlink($this->sFichier);
        $fichier_save='';
        foreach($aValues as $key => $groupe_n)
        {
            $fichier_save.="\n[".$key."]"; // \n est une entrée à la ligne
            foreach($groupe_n as $key => $item_n)
            {
                $fichier_save.="\n".$key."=".$item_n;
            }
        }

        $fichier_save=substr($fichier_save, 1); // On enlève le premier caractère qui est -si vous regardez bien- forcément une entrée à la ligne inutile
        if(false===@file_put_contents($this->sFichier, $fichier_save))
        {
            echo "Impossible d'écrire dans ce fichier";
        }
    }

    private function getArray()
    {
        if (!file_exists($this->sFichier)) {
            return null;
        }
        $array=array();
        if ($fichier_lecture=file($this->sFichier))
        {
           foreach($fichier_lecture as $ligne)
           {
             if(preg_match("#^\[(.*)\]\s+$#",$ligne,$matches))
             {
                $groupe=$matches[1];
                $array[$groupe]=array();
             }
             elseif($ligne[0]!=';' && $ligne!='')
             {
                $aValue = explode("=",$ligne,2);
                if (!empty($aValue))
                {
                    if (isset($aValue[1])) {
                        $array[$groupe][$aValue[0]]=$aValue[1];
                    }
                }

             }
           }
        }
        return $array;
    }


}