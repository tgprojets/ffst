<?php

/**
 * tbl_licence
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 *
 * @package    sf_sandbox
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class tbl_licence extends Basetbl_licence
{
    public function __toString()
    {
        return $this->getTblProfil()->getName();
    }

    public function delete(Doctrine_Connection $conn = null)
    {
        if ($this->getIsBrouillon()) {
            $oProfil = $this->getTblProfil();
            if ($oProfil->getTblLicence()->count() == 1) {
                $oProfil->delete();
            }
        }
        return parent::delete($conn);
    }
}
