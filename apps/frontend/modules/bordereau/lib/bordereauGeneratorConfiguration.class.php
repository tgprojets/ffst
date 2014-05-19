<?php

/**
 * bordereau module configuration.
 *
 * @package    sf_sandbox
 * @subpackage bordereau
 * @author     Your name here
 * @version    SVN: $Id: configuration.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class bordereauGeneratorConfiguration extends BaseBordereauGeneratorConfiguration
{
    public function getFilterDefaults()
    {
        return array(
            'is_payed' => 0,
            );
    }
}
