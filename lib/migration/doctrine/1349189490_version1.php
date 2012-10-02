<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version1 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('tbl_address', 'country', 'string', '2', array(
             ));
        $this->addColumn('tbl_address', 'cp_foreign', 'string', '50', array(
             ));
        $this->addColumn('tbl_address', 'city_foreign', 'string', '50', array(
             ));
        $this->changeColumn('tbl_ligue', 'sigle', 'string', '10', array(
             ));
    }

    public function down()
    {
        $this->removeColumn('tbl_address', 'country');
        $this->removeColumn('tbl_address', 'cp_foreign');
        $this->removeColumn('tbl_address', 'city_foreign');
    }
}