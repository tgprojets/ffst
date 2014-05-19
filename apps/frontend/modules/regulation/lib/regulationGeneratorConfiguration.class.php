<?php

/**
 * regulation module configuration.
 *
 * @package    sf_sandbox
 * @subpackage regulation
 * @author     Your name here
 * @version    SVN: $Id: configuration.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class regulationGeneratorConfiguration extends BaseRegulationGeneratorConfiguration
{
    public function getFilterDefaults()
    {
        $oSaison = Licence::getSaison();
        return array(
            'is_payed' => 0,
            'list_yearlicence' => $oSaison->getId()
            );
    }
}
